<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKinerja extends Model
{
    use HasFactory;

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
