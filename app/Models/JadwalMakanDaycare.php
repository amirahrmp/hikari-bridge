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
        'hari', 
        'snack_pagi', 
        'makan_siang',
        'snack_sore'
    ];
}
