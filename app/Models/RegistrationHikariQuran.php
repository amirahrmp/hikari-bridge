<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationHikariQuran extends Model
{
    use HasFactory;

    // Perbarui nama tabel untuk mencocokkan dengan tabel di database
    protected $table = 'registration_hikari_quran';  // tanpa 's' di akhir

    protected $fillable = [
        'id_anak',
        'full_name',
        'nickname',
        'birth_date',
        'file_upload',
        'parent_name',
        'whatsapp_number',
        'address',
        'kelas',
        'tipe',
        'sumberinfo',
        'promotor',
    ];

    public function pakethq()
    {
        return $this->belongsTo(PaketHq::class, 'kelas', 'kelas'); // 'kelas' adalah foreign key di registration_hikari_quran, 'id' adalah primary key di paket_hq
    }

    public function peserta()
    {
        return $this->hasOne(PesertaHikariKidz::class, 'id_anak', 'id_anak');
    }
}

