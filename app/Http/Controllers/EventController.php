<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'jenis' => 'required|in:terbuka,tertutup',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'max_participants' => 'required|integer|min:1'
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'max_participants' => $request->max_participants,
            'current_participants' => 0
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('participants');
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'jenis' => 'required|in:terbuka,tertutup',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'max_participants' => 'required|integer|min:1',
            'current_participants' => 'required|integer|min:0|max:'.$request->max_participants
        ]);

        $event->update($request->all());

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }

    /**
     * Get scan data for real-time updates.
     */
    public function getScanData(Event $event)
    {
        $event->load('participants');
        
        // Get recent participants who are present (hadir), ordered by check_in time (latest first)
        $scanHistory = $event->participants()
            ->where('status_kehadiran', 'hadir')
            ->orderBy('check_in', 'desc')
            ->take(10)
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'nama' => $participant->nama,
                    'nip' => $participant->nip,
                    'jabatan' => $participant->jabatan,
                    'skpd' => $participant->skpd,
                    'status_kehadiran' => $participant->status_kehadiran,
                    'check_in' => $participant->check_in ? $participant->check_in->toISOString() : null,
                ];
            });

        // Calculate statistics
        $statistics = [
            'hadir_count' => $event->participants->where('status_kehadiran', 'hadir')->count(),
            'total_participants' => $event->current_participants,
            'belum_hadir' => $event->participants->where('status_kehadiran', 'terdaftar')->count(),
            'tidak_hadir' => $event->participants->where('status_kehadiran', 'tidak_hadir')->count(),
            'present_percentage' => $event->current_participants > 0 
                ? round(($event->participants->where('status_kehadiran', 'hadir')->count() / $event->current_participants) * 100) 
                : 0,
            'scan_today' => $event->participants->where('status_kehadiran', 'hadir')
                ->where('check_in', '>=', now()->startOfDay())
                ->where('check_in', '<=', now()->endOfDay())
                ->count(),
            'last_scan' => $event->participants->where('check_in', '!=', null)
                ->sortByDesc('check_in')
                ->first()
                ?->check_in
                ?->format('H:i') ?? '-',
        ];

        return response()->json([
            'success' => true,
            'scanHistory' => $scanHistory,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Show scan page for the event.
     */
    public function scan(Event $event)
    {
        $event->load('participants');
        return view('admin.events.scan', compact('event'));
    }
}
