<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'status',
        'max_participants',
        'current_participants'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'max_participants' => 'integer',
        'current_participants' => 'integer'
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
