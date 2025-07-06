<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan'; // Nama tabel di database

    protected $fillable = [
        'peserta_id',
        'nama_anak',
        'tanggal',
        'kegiatan', // Menyimpan tema dan nama kegiatan dalam bentuk JSON atau array string
        'catatan',
        'foto', // Menyimpan multiple foto sebagai array JSON
        'tipe', // Membedakan antara 'HKD' dan 'HKC'
    ];

    protected $casts = [
        'kegiatan' => 'array', // Penting untuk mengelola data tema dan nama kegiatan sebagai array
        'tanggal'  => 'date',    // Mengubah kolom tanggal menjadi objek Carbon
        'foto'     => 'array',   // Penting untuk menyimpan multiple foto sebagai array
    ];

    /**
     * Relasi ke peserta_hikari_kidz
     * Asumsi ada model PesertaHikariKidz dan kolom id_anak di tabel tersebut
     */
    public function peserta()
    {
        return $this->belongsTo(PesertaHikariKidz::class, 'peserta_id', 'id_anak');
    }
}
