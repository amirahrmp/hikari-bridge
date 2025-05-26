<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PesertaHikariKidz;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'registration_type',
        'komponen',
        'jumlah',
        'bukti_transfer',
    ];

    // Relasi ke tabel peserta umum
    public function registration()
    {
        return $this->belongsTo(PesertaHikariKidz::class, 'registration_id');
    }
}
