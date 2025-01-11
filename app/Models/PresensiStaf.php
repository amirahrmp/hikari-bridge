<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiStaf extends Model
{
    use HasFactory;
    protected $table = 'presensi_staf'; 
    protected $primaryKey = 'id_presensi'; 
    public $timestamps = false; 

    protected $fillable = ['id_presensi','tgl_presensi','id_card','nama_staf', 'waktu_masuk', 'waktu_keluar', 'keterangan'];

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'id_card', 'id_card');
    }
}
