<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketHq extends Model
{
    use HasFactory;

    protected $table = 'paket_hq';

    protected $fillable = [
        'id',
        'id_pakethq',
        'kelas',
        'kapasitas',
        'durasi',
        'u_pendaftaran',
        'u_modul',
        'u_spp',
    ];
}
