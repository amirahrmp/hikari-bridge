<?php

namespace App\Models;

use App\Models\KegiatanTambahan;
use App\Models\PesertaHikariKidz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramLain extends Model
{
    use HasFactory;

     protected $table = 'program_lain';
    protected $fillable = ['nama_kegiatan', 'biaya', 'deskripsi'];

   public function kegiatanTambahan()
    {
        return $this->hasMany(KegiatanTambahan::class, 'program_id');
    }
}
