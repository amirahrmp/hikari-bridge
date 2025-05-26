<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiDaycare extends Model
{
    use HasFactory;
    protected $table = 'absensi_daycare';
    protected $fillable = [
        'id',
        'id_anak', 
        'jam_datang', 
        'jam_pulang', 
        'overtime',
        'denda'
    ];
}
