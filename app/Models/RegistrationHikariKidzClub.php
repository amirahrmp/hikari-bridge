<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaketHkc; // Pastikan model PaketHkc sudah di-import di sini

class RegistrationHikariKidzClub extends Model
{
    use HasFactory;

    protected $table = 'registration_hikari_kidz_clubs'; // Specify the table name if it's different from the pluralized model name.

    protected $fillable = [
        'id_anak',
        'full_name',
        'nickname',
        'birth_date',
        'file_upload',
        'parent_name',
        'whatsapp_number',
        'address',
        'agama',
        'nonmuslim',
        'member',
        'kelas',
        'information_source',
        'information_source_other',
        'promotor'
    ];

    public function getPaketHkc()
    {
        // Debugging: Anda bisa mengaktifkan baris dd() di bawah ini untuk melihat
        // nilai 'member' dan 'kelas' yang digunakan dalam query, serta hasil query-nya.
        // Ini sangat membantu untuk mendiagnosis masalah ketidaksesuaian data.
        // dd([
        //     'member_dari_registrasi' => $this->member,
        //     'kelas_dari_registrasi' => $this->kelas,
        //     'nilai_query_member' => strtolower(trim($this->member)),
        //     'nilai_query_kelas' => strtolower(trim($this->kelas)),
        //     'hasil_pencarian_paket' => PaketHkc::whereRaw('LOWER(TRIM(member)) = ?', [strtolower(trim($this->member))])
        //                                         ->whereRaw('LOWER(TRIM(kelas)) = ?', [strtolower(trim($this->kelas))])
        //                                         ->first()
        // ]);

        // Mencari paket HKC berdasarkan kolom 'member' dan 'kelas'
        // Menggunakan LOWER(TRIM()) untuk memastikan pencarian tidak sensitif huruf besar/kecil dan spasi
        return PaketHkc::whereRaw('LOWER(TRIM(member)) = ?', [strtolower(trim($this->member))])
                       ->whereRaw('LOWER(TRIM(kelas)) = ?', [strtolower(trim($this->kelas))])
                       ->first();
    }


    public function peserta()
    {
        return $this->hasOne(PesertaHikariKidz::class, 'id_anak', 'id_anak');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
