<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import semua model yang dibutuhkan
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationProgramHkcw;
use App\Models\HikariKidz;
use App\Models\JadwalHikariKidz;
use App\Models\KegiatanTambahan;
use App\Models\User;
use App\Models\Anak;
use App\Models\LaporanKegiatan;   // ⬅️  Tambahkan import ini

class PesertaHikariKidz extends Model
{
    use HasFactory;

    protected $table = 'peserta_hikari_kidz';

    /**
     * PRIMARY KEY
     * -------------------------------------------------
     * Jika `id_anak` memang integer auto-increment
     * (sesuai screenshot MySQL Anda), ubah properti
     * berikut supaya cocok dan menghindari error
     * foreign-key di model lain.
     */
    protected $primaryKey  = 'id_anak';
    public    $incrementing = true;   // ubah ke true jika auto-increment
    protected $keyType      = 'int';  // 'int' agar cocok dengan unsigned INT

    /**
     * Kolom yang bisa di-mass-assign
     */
    protected $fillable = [
        'id_anak',
        'status',
        'status_keaktifan',
        'full_name',
        'nickname',
        'birth_date',
        'parent_name',
        'address',
        'whatsapp_number',
        'tipe',
        'file_upload',
        'user_id',
    ];

    /* ==========================================================
     |  RELASI MANY-TO-MANY & ONE-TO-MANY YANG SUDAH ADA
     |==========================================================*/
    public function hikarikidz()
    {
        return $this->belongsToMany(
            HikariKidz::class,
            'detail_hikari_kidz',
            'id_anak',
            'id_hikari_kidz'
        )->withPivot(
            'id', 
            'status', 
            'tgl_masuk_hikari_kidz'
        )->withTimestamps();
    }

    public function jadwalHikariKidz()
    {
        return $this->belongsToMany(
            JadwalHikariKidz::class,
            'detail_jadwal_hikari_kidz',
            'id_anak',
            'id_jadwal_hikari_kidz'
        )->withTimestamps();
    }

    public function registration()
    {
        return $this->belongsTo(
            RegistrationHikariKidzClub::class,
            'id_anak',
            'id_anak'
        );
    }

    public function kegiatanTambahan()
    {
        return $this->hasMany(KegiatanTambahan::class, 'id_anak', 'id_anak');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporanKegiatan()
    {
        return $this->hasMany(LaporanKegiatan::class, 'peserta_id', 'id_anak');
    }

    public function getFotoPathAttribute()
    {
        $folder = strtolower($this->tipe); // hkc, hkd, hq
        return asset("storage/uploads/{$folder}/{$this->file_upload}");
    }
}
