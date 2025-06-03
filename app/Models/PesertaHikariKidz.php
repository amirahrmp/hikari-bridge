<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaHikariKidz extends Model
{
    use HasFactory;
    protected $table = 'peserta_hikari_kidz';
    // list kolom yang bisa diisi
    protected $fillable = [
        'id',
        'id_anak',
        'status',
        'full_name',
        'nickname',
        'birth_date',
        'parent_name',
        'address',
        'whatsapp_number',
        'tipe',
        'file_upload'
    ];

    public function hikarikidz()
    {
        return $this->belongsToMany(HikariKidz::class, 'detail_hikari_kidz', 'id_anak', 'id_hikari_kidz')
                    ->withPivot('id','status','tgl_masuk_hikari_kidz')
                    ->withTimestamps(); // Menyertakan timestamps
    }

    public function jadwalHikariKidz()
    {
        return $this->belongsToMany(JadwalHikariKidz::class, 'detail_jadwal_hikari_kidz', 'id_anak', 'id_jadwal_hikari_kidz')
                    ->withTimestamps();
    }

    public function registration()
    {
        return $this->belongsTo(RegistrationHikariKidzClub::class, 'id_anak', 'id_anak');
    }
}
