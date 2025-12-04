<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\Event;
use App\Models\Pegawai;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
use App\Exports\ParticipantsExport;

class ParticipantController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
        ]);

        // Check if event is full
        if ($event->current_participants >= $event->max_participants) {
            return response()->json([
                'success' => false,
                'message' => 'Event sudah penuh!'
            ], 400);
        }

        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        // Check if participant already exists
        $existingParticipant = Participant::where('event_id', $event->id)
            ->where('nip', $pegawai->nip)
            ->first();

        if ($existingParticipant) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah terdaftar di event ini!'
            ], 400);
        }

        // Create participant
        $participant = Participant::create([
            'event_id' => $event->id,
            'nip' => $pegawai->nip,
            'nama' => $pegawai->nama,
            'jabatan' => $pegawai->jabatan,
            'skpd' => Skpd::find($pegawai->skpd_id)->nama,
            'status_kehadiran' => 'terdaftar',
        ]);

        // Update current participants count
        $event->current_participants = $event->participants()->count();
        $event->save();

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil ditambahkan!',
            'participant_id' => $participant->id,
            'current_participants' => $event->current_participants,
            'max_participants' => $event->max_participants
        ]);
    }

    public function destroy(Event $event, Participant $participant)
    {
        $participant->delete();

        // Update current participants count
        $event->current_participants = $event->participants()->count();
        $event->save();

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dihapus!',
            'current_participants' => $event->current_participants,
            'max_participants' => $event->max_participants
        ]);
    }

    public function updateStatus(Request $request, Event $event, Participant $participant)
    {
        $request->validate([
            'status_kehadiran' => 'required|in:terdaftar,hadir,tidak_hadir',
        ]);

        $participant->update([
            'status_kehadiran' => $request->status_kehadiran,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil diperbarui!',
            'current_status' => $participant->status_kehadiran
        ]);
    }

    public function searchPegawai(Request $request)
    {
        $search = $request->get('q');

        $pegawai = Pegawai::with('skpd')
            ->where('nama', 'LIKE', "%{$search}%")
            ->orWhere('nip', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nip', 'nama', 'jabatan', 'skpd_id']);

        // Transform data to include skpd_nama
        $pegawai->transform(function ($item) {
            $item->skpd_nama = $item->skpd ? $item->skpd->nama : '-';
            return $item;
        });

        return response()->json($pegawai);
    }

    public function showScanPage(Event $event)
    {
        // Load event with participants relationship
        $event->load('participants');

        return view('admin.events.scan', compact('event'));
    }

    public function processScan(Request $request, Event $event)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $qrData = $request->qr_data;

        // Extract UUID from QR code URL
        // Expected format: https://idcardpegawai.banjarmasinkota.go.id/captcha/{uuid}/pns OR https://idcardpegawai.banjarmasinkota.go.id/captcha/{uuid}/pppk
        $uuid = $this->extractUuidFromQrCode($qrData);

        if (!$uuid) {
            return response()->json([
                'success' => false,
                'message' => 'Format QR code tidak valid!'
            ], 400);
        }

        // Get employee data from API
        $pegawaiData = $this->getPegawaiDataFromApi($uuid);

        if (!$pegawaiData) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan data pegawai dari API!'
            ], 500);
        }

        $nip = $pegawaiData['nip'];

        // Find participant by NIP for this event
        $participant = Participant::where('event_id', $event->id)
            ->where('nip', $nip)
            ->first();

        // Handle different event types
        if (!$participant) {
            // If participant not found
            if ($event->jenis === 'tertutup') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pegawai dengan NIP ' . $nip . ' tidak terdaftar di event tertutup ini!'
                ], 404);
            } else {
                // For open events, create new participant automatically
                $participant = Participant::create([
                    'event_id' => $event->id,
                    'nip' => $pegawaiData['nip'],
                    'nama' => $pegawaiData['nama'],
                    'jabatan' => $pegawaiData['jabatan'] ?? '-',
                    'skpd' => $pegawaiData['skpd'] ?? $pegawaiData['unit_kerja'] ?? '-',
                    'status_kehadiran' => 'hadir',
                    'check_in' => now(),
                ]);

                // Update current participants count
                $event->current_participants = $event->participants()->count();
                $event->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Peserta baru berhasil ditambahkan dan kehadiran dicatat!',
                    'already_scanned' => false,
                    'is_new_participant' => true,
                    'participant' => [
                        'nip' => $participant->nip,
                        'nama' => $participant->nama,
                        'jabatan' => $participant->jabatan,
                        'skpd' => $participant->skpd,
                        'status_kehadiran' => $participant->status_kehadiran,
                        'check_in' => $participant->check_in->format('H:i:s'),
                    ]
                ]);
            }
        }

        // Check if participant has already scanned (status is 'hadir' and check_in is not null)
        if ($participant->status_kehadiran === 'hadir' && !is_null($participant->check_in)) {
            return response()->json([
                'success' => true,
                'message' => 'Anda sudah scan!',
                'already_scanned' => true,
                'participant' => [
                    'nip' => $participant->nip,
                    'nama' => $participant->nama,
                    'jabatan' => $participant->jabatan,
                    'skpd' => $participant->skpd,
                    'status_kehadiran' => $participant->status_kehadiran,
                    'check_in' => $participant->check_in->format('H:i:s'),
                ]
            ]);
        }

        // Update attendance status to "hadir" and record check-in time
        $participant->update([
            'status_kehadiran' => 'hadir',
            'check_in' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berhasil dicatat!',
            'already_scanned' => false,
            'participant' => [
                'nip' => $participant->nip,
                'nama' => $participant->nama,
                'jabatan' => $participant->jabatan,
                'skpd' => $participant->skpd,
                'status_kehadiran' => $participant->status_kehadiran,
                'check_in' => $participant->check_in->format('H:i:s'),
            ]
        ]);
    }

    /**
     * Extract UUID from QR code URL
     */
    private function extractUuidFromQrCode($qrData)
    {
        // Pattern to match: https://idcardpegawai.banjarmasinkota.go.id/captcha/{uuid}/pns
        // Pattern to match: https: //idcardpegawai.banjarmasinkota.go.id/captcha/{uuid}/pppk

        // Single pattern that matches both pns and pppk
        $pattern = '/https:\/\/idcardpegawai\.banjarmasinkota\.go\.id\/captcha\/([a-f0-9\-]{36})\/(pns|pppk)/';

        if (preg_match($pattern, $qrData, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Import participants from Excel file
     */
    public function importExcel(Request $request, Event $event)
    {
        // Enhanced validation with security checks
        $request->validate([
            'excel_file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:2048', // 2MB max
                // Custom validation to ensure file is actually Excel
                function ($attribute, $value, $fail) {
                    // Additional security checks
                    $file = $value;

                    // Check file signature (magic bytes)
                    $fileContent = file_get_contents($file->getRealPath());
                    $signatures = [
                        'xlsx' => ["PK\x03\x04", "PK\x05\x06", "PK\x07\x08"], // ZIP signature for XLSX
                        'xls' => ["\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1"], // OLE2 signature for XLS
                        'csv' => false // CSV doesn't have specific signature
                    ];

                    $extension = strtolower($file->getClientOriginalExtension());
                    $isValidSignature = false;

                    if ($extension === 'csv') {
                        // For CSV, check if it's actually text and contains comma/semicolon
                        $isValidSignature = true;
                    } elseif (isset($signatures[$extension])) {
                        foreach ($signatures[$extension] as $signature) {
                            if (strpos($fileContent, $signature) === 0) {
                                $isValidSignature = true;
                                break;
                            }
                        }
                    }

                    if (!$isValidSignature) {
                        $fail('File yang diupload bukan file Excel yang valid atau telah dimodifikasi.');
                    }

                    // Check for malicious patterns in filename
                    $filename = $file->getClientOriginalName();
                    $maliciousPatterns = ['..', '/', '\\', '<', '>', ':', '"', '|', '?', '*', '\0'];
                    foreach ($maliciousPatterns as $pattern) {
                        if (strpos($filename, $pattern) !== false) {
                            $fail('Nama file mengandung karakter yang tidak diperbolehkan.');
                        }
                    }

                    // Check file size again (additional security)
                    if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                        $fail('Ukuran file terlalu besar. Maksimal 2MB.');
                    }

                    // Additional check: ensure file doesn't contain executable content
                    $this->validateFileContent($fileContent, $fail);
                },
            ],
        ]);

        try {
            $import = new ParticipantsImport($event->id);

            // Store file temporarily with secure naming
            $file = $request->file('excel_file');
            $secureFileName = 'import_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tempPath = $file->storeAs('temp/imports', $secureFileName, ['disk' => 'local']);

            // Import directly from stored file path
            Excel::import($import, $tempPath);

            // Clean up temporary file
            @unlink(storage_path('app/' . $tempPath));

            // Update current participants count
            $event->current_participants = $event->participants()->count();
            $event->save();

            $response = [
                'success' => true,
                'message' => 'Data peserta berhasil diimport!',
                'imported_count' => $import->getImportedCount(),
                'skipped_count' => $import->getSkippedCount(),
                'current_participants' => $event->current_participants,
                'max_participants' => $event->max_participants
            ];

            // Add validation errors if any
            if ($import->hasValidationErrors()) {
                $response['validation_errors'] = $import->getValidationErrors();

                // If there are validation errors but some records were imported, show partial success
                if ($import->getImportedCount() > 0) {
                    $response['message'] = 'Data peserta sebagian berhasil diimport dengan beberapa error!';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Gagal mengimport file: ' . implode(', ', array_unique($import->getValidationErrors()));
                }
            }

            return response()->json($response);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error('Excel validation error: ' . json_encode($e->errors()));

            $errors = [];
            foreach ($e->failures() as $failure) {
                $errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport file: ' . implode(', ', $errors),
                'validation_errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            Log::error('Excel import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export participants to Excel
     */
    public function exportExcel(Event $event)
    {
        // Sanitize event title for filename by removing problematic characters
        $sanitizedTitle = preg_replace('/[\/\\\\]/', '', $event->title);
        $sanitizedTitle = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $sanitizedTitle);
        $sanitizedTitle = trim($sanitizedTitle);

        $fileName = 'Kehadiran_' . str_replace(' ', '_', $sanitizedTitle) . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ParticipantsExport($event), $fileName);
    }

    /**
     * Validate file content for malicious patterns
     */
    private function validateFileContent($content, $failCallback)
    {
        // Check for common malicious patterns
        $maliciousPatterns = [
            // PHP tags
            '<?php',
            '<?',
            '?>',
            // JavaScript tags
            '<script',
            '</script>',
            'javascript:',
            // Executable patterns
            'eval(',
            'exec(',
            'system(',
            'shell_exec(',
            'passthru(',
            // Base64 encoded PHP
            'base64_decode',
            // Common attack patterns
            '$_POST',
            '$_GET',
            '$_REQUEST',
            '$_COOKIE',
            '$_SESSION',
            // SQL injection patterns
            'union select',
            'drop table',
            'insert into',
            'delete from',
            'update set',
        ];

        $contentLower = strtolower($content);
        foreach ($maliciousPatterns as $pattern) {
            if (strpos($contentLower, $pattern) !== false) {
                $failCallback('File mengandung konten yang mencurigakan atau berbahaya.');
                return false;
            }
        }

        return true;
    }

    /**
     * Show riwayat scan page for an event
     */
    public function showRiwayatScan(Event $event)
    {
        // Load event with participants relationship
        $event->load('participants');

        return view('admin.events.riwayat_scan', compact('event'));
    }

    /**
     * Get pegawai data from external API
     */
    private function getPegawaiDataFromApi($uuid)
    {
        $apiUrl = "https://lakasi.banjarmasinkota.go.id/api/pegawai/{$uuid}";
        $bearerToken = "hqWMtGwhdqll4oK4gXhxl0qDKLPCOGufvkF4glNU";

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false, // Disable SSL verification if needed
            ]);

            $response = $client->request('GET', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);

                if (isset($data['nip'])) {
                    return $data;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to fetch pegawai data from API: ' . $e->getMessage());
            return null;
        }
    }
}
