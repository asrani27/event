<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'nip',
        'nama',
        'jabatan',
        'skpd',
        'status_kehadiran',
        'check_in',
    ];

    protected $casts = [
        'check_in' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
