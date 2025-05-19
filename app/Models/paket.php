<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $fillable = [
        'id',
        'id_paket',
        'nama_paket',
        'durasi_jam',
        'u_pendaftaran',
        'u_pangkal',
        'u_kegiatan',
        'u_spp',
        'u_makan',
        'tipe',
        'biaya_penitipan'
    ];
}
