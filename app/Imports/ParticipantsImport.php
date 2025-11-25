<?php

namespace App\Imports;

use App\Models\Participant;
use App\Models\Pegawai;
use App\Models\Skpd;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ParticipantsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $eventId;
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $validationErrors = [];

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function model(array $row)
    {
        try {
            // Normalize row keys (handle different column name formats)
            $normalizedRow = $this->normalizeRowKeys($row);
            
            // Check if NIP exists
            if (empty($normalizedRow['nip'])) {
                $this->skippedCount++;
                $error = 'Baris dilewati: Kolom NIP wajib diisi';
                $this->validationErrors[] = $error;
                Log::warning('Skipped row due to missing NIP: ' . json_encode($row));
                return null;
            }

            // Find pegawai data based on NIP
            $pegawai = Pegawai::where('nip', $normalizedRow['nip'])->first();
            
            if (!$pegawai) {
                $this->skippedCount++;
                $error = 'Baris dilewati: Pegawai dengan NIP ' . $normalizedRow['nip'] . ' tidak ditemukan di database';
                $this->validationErrors[] = $error;
                Log::warning('Skipped row due to pegawai not found: ' . $normalizedRow['nip']);
                return null;
            }

            // Check if participant already exists for this event
            $existingParticipant = Participant::where('event_id', $this->eventId)
                ->where('nip', $pegawai->nip)
                ->first();

            if ($existingParticipant) {
                $this->skippedCount++;
                $error = 'Peserta dengan NIP ' . $pegawai->nip . ' sudah terdaftar di event ini';
                $this->validationErrors[] = $error;
                Log::info('Participant already exists: ' . $pegawai->nip);
                return null;
            }

            // Get SKPD name from database
            $skpd = Skpd::find($pegawai->skpd_id);
            $skpdName = $skpd ? $skpd->nama : '';

            $participant = new Participant([
                'event_id' => $this->eventId,
                'nip' => $pegawai->nip,
                'nama' => $pegawai->nama,
                'jabatan' => $pegawai->jabatan,
                'skpd' => $skpdName,
                'status_kehadiran' => 'terdaftar',
            ]);

            $this->importedCount++;
            return $participant;

        } catch (\Exception $e) {
            $this->skippedCount++;
            $error = 'Error memproses baris: ' . $e->getMessage();
            $this->validationErrors[] = $error;
            Log::error('Error processing row: ' . $e->getMessage() . ' Row data: ' . json_encode($row));
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|string|max:50',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nip.required' => 'NIP wajib diisi',
            'nip.string' => 'NIP harus berupa string',
            'nip.max' => 'NIP maksimal 50 karakter',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function hasValidationErrors()
    {
        return !empty($this->validationErrors);
    }

    /**
     * Normalize row keys to handle different column name formats
     */
    private function normalizeRowKeys(array $row): array
    {
        $normalized = [];
        
        // Map various possible column names to standard ones
        $columnMapping = [
            'nip' => ['nip', 'NIP', 'Nip'],
            'nama' => ['nama', 'Nama', 'NAMA', 'name', 'Name'],
            'jabatan' => ['jabatan', 'Jabatan', 'JABATAN', 'position', 'Position'],
            'skpd' => ['skpd', 'Skpd', 'SKPD', 'instansi', 'Instansi', 'INSTANSI', 'unit', 'Unit']
        ];
        
        foreach ($row as $key => $value) {
            $found = false;
            
            // Try to match the key with our mapping
            foreach ($columnMapping as $standard => $variants) {
                if (in_array($key, $variants)) {
                    $normalized[$standard] = $value;
                    $found = true;
                    break;
                }
            }
            
            // If no mapping found, use the original key
            if (!$found) {
                $normalized[$key] = $value;
            }
        }
        
        return $normalized;
    }

    /**
     * Override the default behavior to collect all validation failures
     */
    public function onFailure(\Throwable $e)
    {
        if ($e instanceof ValidationException) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->validationErrors[] = $message;
                    $this->skippedCount++;
                }
            }
        } else {
            $this->validationErrors[] = 'Error: ' . $e->getMessage();
            $this->skippedCount++;
        }
    }
}
