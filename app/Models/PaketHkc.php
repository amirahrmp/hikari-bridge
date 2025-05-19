<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketHkc extends Model
{
    use HasFactory;

    protected $table = 'paket_hkc';

    protected $fillable = [
        'id',
        'id_pakethkc',
        'member',
        'kelas',
        'u_pendaftaran',
        'u_perlengkapan',
        'u_sarana',
        'u_spp',
        'tipe',
    ];
}
