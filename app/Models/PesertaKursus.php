<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaKursus extends Model
{
    use HasFactory;
    protected $table = 'peserta_kursus';
    // list kolom yang bisa diisi
    protected $fillable = ['id','id_peserta','nama_peserta','nama_ortu','alamat','jk','telp','email','tgl_lahir'];

    public function kursus()
    {
        return $this->belongsToMany(Kursus::class, 'detail_kursus', 'id_peserta', 'id_kursus')
                    ->withPivot('id','status','tgl_masuk_kursus')
                    ->withTimestamps(); // Menyertakan timestamps
    }

    public function jadwalKursus()
    {
        return $this->belongsToMany(JadwalKursus::class, 'detail_jadwal_kursus', 'id_peserta', 'id_jadwal_kursus')
                    ->withTimestamps();
    }
}
