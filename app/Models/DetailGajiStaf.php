<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGajiStaf extends Model
{
    use HasFactory;
    protected $table = 'detail_gaji_staf';
    public $timestamps = false;

    protected $fillable = ['id_gaji', 'id_staf', 'gaji_pokok', 'uang_makan', 'tunjangan', 'uang_transport', 'bonus', 'potongan_pph21', 'potongan', 'total_gaji'];
}
