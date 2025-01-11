<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $table = 'teacher';
    // list kolom yang bisa diisi
    protected $fillable = ['id','id_card','nik','nama_teacher','tipe_pengajar','alamat','jk','telp','email','tmp_lahir','tgl_lahir'];
}
