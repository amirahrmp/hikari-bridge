<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationProgramHkcw;

class PesertaHikariKidz extends Model
{
    use HasFactory;
    protected $table = 'peserta_hikari_kidz';

     protected $primaryKey = 'id_anak';

    public $incrementing = false; // jika id_anak bukan auto-increment

    protected $keyType = 'string'; // jika string. Kalau integer, gunakan 'int'

    // list kolom yang bisa diisi
    protected $fillable = [
        'id',
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

    public function getFotoPathAttribute()
    {
        $folder = strtolower($this->tipe); // hkc, hkd, hq
        return asset("storage/uploads/{$folder}/{$this->file_upload}");
    }
}
