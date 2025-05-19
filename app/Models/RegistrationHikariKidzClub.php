<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationHikariKidzClub extends Model
{
    use HasFactory;

    protected $table = 'registration_hikari_kidz_clubs'; // Specify the table name if it's different from the pluralized model name.

    protected $fillable = [
        'full_name', 
        'nickname', 
        'birth_date', 
        'file_upload',
        'parent_name', 
        'whatsapp_number', 
        'address', 
        'agama', 
        'nonmuslim', 
        'member', 
        'kelas', 
        'information_source',
        'information_source_other',  // Tambahkan ini
        'promotor'
    ];
    
    
}
