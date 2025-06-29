<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan';

    protected $fillable = [
        'peserta_id',
        'nama_anak',
        'tanggal',
        'kegiatan', // Ini akan menyimpan tema dan nama kegiatan dalam bentuk JSON
        'catatan',
        'foto',
        'tipe', // Ini akan membedakan antara 'HKD' dan 'HKC'
    ];

    protected $casts = [
        'kegiatan' => 'array', // Penting untuk mengelola data tema dan nama kegiatan
        'tanggal'  => 'date',
        'foto'     => 'array', // Penting untuk menyimpan multiple foto
    ];

    /**
     * Relasi ke peserta_hikari_kidz
     */
    public function peserta()
    {
        return $this->belongsTo(PesertaHikariKidz::class, 'peserta_id', 'id_anak');
    }
}