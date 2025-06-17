<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMakanDaycare extends Model
{
    use HasFactory;
    protected $table = 'jadwal_makan_daycare';
    protected $fillable = [
        //'id', 
        'bulan',
        'pekan',
        'is_libur',
        'hari', 
        'snack_pagi', 
        'makan_siang',
        'snack_sore'
    ];

     protected $casts = [
        'is_libur' => 'boolean',
    ];
}
