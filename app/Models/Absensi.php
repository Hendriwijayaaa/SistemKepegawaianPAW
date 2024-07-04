<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
    public function pelatihan()
    {
        return $this->hasMany(PelatihanPegawai::class);
    }
}