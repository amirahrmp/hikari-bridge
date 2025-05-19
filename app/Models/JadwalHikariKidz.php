<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalHikariKidz extends Model
{
    use HasFactory;
    protected $table = 'jadwal_hikari_kidz';
    protected $fillable = [
        'id_hikari_kidz', 'id_pengasuh', 'hari', 'waktu', 'tipe_hikari_kidz'
    ];

    public function hikari_kidz()
    {
        return $this->belongsTo(HikariKidz::class, 'id_hikari_kidz');
    }

    public function pengasuh()
    {
        return $this->belongsTo(Pengasuh::class, 'id_pengasuh');
    }

    public function peserta()
    {
        return $this->belongsToMany(PesertaHikariKidz::class, 'detail_jadwal_hikari_kidz', 'id_jadwal_hikari_kidz', 'id_anak')
                    ->withTimestamps();
    }
}
