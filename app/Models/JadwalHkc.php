<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalHkc extends Model
{
    use HasFactory;
    protected $table = 'jadwal_hkc';
    protected $fillable = [
        'kelas',   // baru
        'hari',
        'waktu_mulai',    // 09:00
        'waktu_selesai',  // 09:20
        'tema_id',
        'kegiatan',       // Book Corner, Ibadah Corner, dll
    ];

    public function tema()
    {
        return $this->belongsTo(TemaHkc::class, 'tema_id');
    }
}
