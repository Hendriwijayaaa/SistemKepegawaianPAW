<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelatihanPegawai extends Model
{
    use HasFactory;

        public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
        public function absen()
    {
        return $this->belongsTo(Absensi::class);
    }

}