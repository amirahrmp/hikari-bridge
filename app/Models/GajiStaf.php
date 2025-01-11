<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiStaf extends Model
{
    use HasFactory;
    protected $table = 'gaji_staf'; 
    protected $fillable = ['id_gaji','tgl_gaji','keterangan','bulan_tahun','total_gaji_dibayarkan'];

    public function detailGajiStaf()
    {
        return $this->hasMany(DetailGajiStaf::class, 'id_gaji');
    }
}
