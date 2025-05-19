<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationProgramHkcw extends Model
{
    use HasFactory;

    // Perbarui nama tabel untuk mencocokkan dengan tabel di database
    protected $table = 'registration_program_hkcw';  // tanpa 's' di akhir

    protected $fillable = [
        'nama_kegiatan',
        'full_name',
        'nama_panggilan',
        'parent_name',
        'whatsapp_number',
        'address',
        'kelas',
        'tipe',
        'bukti_bayar',
    ];
}
