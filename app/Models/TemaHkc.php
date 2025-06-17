<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemaHkc extends Model
{
    use HasFactory;
    protected $table = 'tema_hkc';
    protected $fillable = [
        'bulan', 
        'tema', 
        'tahun_ajaran'
    ];

    public function jadwal()
    {
        return $this->hasMany(JadwalHkc::class);
    }
}
