<?php

namespace App\Models;

use Filament\Pages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTanam extends Model
{
    protected $fillable = [
        'nama_tanaman', 
        'lokasi_lahan', 
        'tanggal_tanam', 
        'status', 
        'catatan'
    ];
}
