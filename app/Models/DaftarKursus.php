<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarKursus extends Model
{
    use HasFactory;

    // Jika nama tabel berbeda dengan nama model (misalnya 'kursus' bukan 'kursuses')
    protected $table = 'daftar_kursus'; // Ganti 'kursus' dengan nama tabel yang benar di database

    protected $fillable = [
        'nama_kursus',
        'kategori',
        'deskripsi',
        'foto',
    ];

   
}
