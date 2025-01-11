<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKursus extends Model
{
    use HasFactory;
    protected $table = 'jadwal_kursus';
    protected $fillable = [
        'id_kursus', 'id_teacher', 'hari', 'waktu', 'tipe_kursus'
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_teacher');
    }

    public function peserta()
    {
        return $this->belongsToMany(PesertaKursus::class, 'detail_jadwal_kursus', 'id_jadwal_kursus', 'id_peserta')
                    ->withTimestamps();
    }
}
