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
        'full_name',
        'birth_date',
        'file_upload',
        'parent_name',
        'whatsapp_number',
        'address',
        'education',
        'sumberinfo',
        'promotor',
    ];
}

