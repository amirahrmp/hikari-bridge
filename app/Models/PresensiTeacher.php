<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiTeacher extends Model
{
    use HasFactory;
    protected $table = 'presensi_teacher'; 
    protected $primaryKey = 'id_presensi_teacher'; 
    public $timestamps = false; 

    protected $fillable = ['id_presensi_teacher','tgl_presensi','id_card', 'nama_teacher', 'waktu_masuk', 'waktu_keluar', 'keterangan'];
}
