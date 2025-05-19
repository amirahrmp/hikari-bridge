<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HikariKidz extends Model
{
    use HasFactory;
    protected $table = 'hikari_kidz';
    // list kolom yang bisa diisi
    protected $fillable = [
        'id',
        'id_hikari_kidz',
        'nama_hikari_kidz',
        'jenis_hikari_kidz',
        'id_paket',
        'kelas'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    // public function pesertaHikariKidz()
    // {
    //     return $this->belongsToMany(PesertaHikariKidz::class, 'detail_hikari_kidz', 'id_hikari_kidz', 'id_anak')
    //                 ->withPivot('id','status','tgl_masuk_hikari_kidz')
    //                 ->withTimestamps(); // Menyertakan timestamps
    // }
}