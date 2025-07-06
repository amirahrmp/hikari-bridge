<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiHkc extends Model
{
    use HasFactory;

    protected $table = 'absensi_hkc'; // Nama tabel sesuai migrasi 

    protected $fillable = [
        'id_anak',
        'nama_anak',
        'program',
        'nama_paket',
        'keterangan',
    ];

    public function peserta()
    {
        return $this->belongsTo(PesertaHikariKidz::class, 'id_anak', 'id_anak');
    }
}
