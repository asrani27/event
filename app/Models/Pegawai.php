<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }
}
