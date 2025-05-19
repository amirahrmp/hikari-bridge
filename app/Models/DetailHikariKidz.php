<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHikariKidz extends Model
{
    use HasFactory;
    protected $table = 'detail_hikari_kidz';

    protected $fillable = [
        'id',
        'id_hikari_kidz', 
        'id_anak',
        'status',
        'tgl_masuk_hikari_kidz'
    ];

    // Tentukan relasi jika ada
}
