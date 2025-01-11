<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    use HasFactory;
    protected $table = 'staf';
    // list kolom yang bisa diisi
    protected $fillable = ['id','id_card','nik','nama_staf','jabatan','departemen','tipe_staf','staf','status','ptkp','npwp','tgl_masuk_kerja','alamat','jk','telp','email','tmp_lahir','tgl_lahir'];

    public function presensiStaf()
    {
        return $this->hasMany(PresensiStaf::class, 'id_card', 'id_card');
    }
}
