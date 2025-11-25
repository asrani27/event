<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\Event;
use App\Models\Pegawai;
use App\Models\Participant;
use Illuminate\Http\Request;

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

        // Assuming QR code contains NIP (Nomor Induk Pegawai)
        $nip = $request->qr_data;

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

        // Get employee data for response
        $pegawai = Pegawai::where('nip', $nip)->first();

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
}
