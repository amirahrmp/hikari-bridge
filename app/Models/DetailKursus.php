<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKursus extends Model
{
    use HasFactory;
    protected $table = 'detail_kursus';

    protected $fillable = ['id','id_kursus', 'id_peserta','status','tgl_masuk_kursus'];

    // Tentukan relasi jika ada
}
