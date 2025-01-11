<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory;
    protected $table = 'kursus';
    // list kolom yang bisa diisi
    protected $fillable = ['id','id_kursus','nama_kursus','jenis_kursus','level','kategori','kelas','kapasitas','waktu','biaya'];

    public function pesertaKursus()
    {
        return $this->belongsToMany(PesertaKursus::class, 'detail_kursus', 'id_kursus', 'id_peserta')
                    ->withPivot('id','status','tgl_masuk_kursus')
                    ->withTimestamps(); // Menyertakan timestamps
    }
}
