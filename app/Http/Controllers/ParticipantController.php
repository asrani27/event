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
        return view('admin.events.scan', compact('event'));
    }

    public function processScan(Request $request, Event $event)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $qrData = $request->qr_data;

        // Extract UUID from QR code URL
        // Expected format: https://idcardpegawai.banjarmasinkota.go.id/captcha/{uuid}/pns
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

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai dengan NIP ' . $nip . ' tidak terdaftar di event ini!'
            ], 404);
        }

        // Update attendance status to "hadir"
        $participant->update([
            'status_kehadiran' => 'hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berhasil dicatat!',
            'participant' => [
                'nip' => $participant->nip,
                'nama' => $participant->nama,
                'jabatan' => $participant->jabatan,
                'skpd' => $participant->skpd,
                'status_kehadiran' => $participant->status_kehadiran,
            ]
        ]);
    }

    /**
     * Extract UUID from QR code URL
     */
    private function extractUuidFromQrCode($qrData)
    {
        // Pattern to match: https://idcardpegawai.banjarmasinkota.go.id/captcha/{uuid}/pns
        $pattern = '/https:\/\/idcardpegawai\.banjarmasinkota\.go\.id\/captcha\/([a-f0-9\-]{36})\/pns/';
        
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
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $import = new ParticipantsImport($event->id);
            
            // Use import with error handling
            Excel::import($import, $request->file('excel_file'));

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
