<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationHikariKidzClub extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'nickname',
        'birth_date',
        'parent_name',
        'whatsapp_number',
        'address',
    ];
    
}
