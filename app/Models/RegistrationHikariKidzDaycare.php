<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationHikariKidzDaycare extends Model
{
    use HasFactory;

    protected $table = 'registration_hikari_kidz_daycares';  // Pastikan nama tabelnya benar    
    protected $fillable = [
        'full_name',
        'nickname',
        'birth_date',
        'file_upload',
        'child_order',
        'siblings_count',
        'height_cm',
        'weight_kg',
        'father_name',
        'mother_name',
        'father_job',
        'mother_job',
        'father_whatsapp',
        'mother_whatsapp',
        'address',
        'age_group',
        'package_type',
        'medical_history',
        'eating_habit',
        'favorite_food',
        'favorite_drink',
        'favorite_toy',
        'specific_habits',
        'caretaker',
        'trial_agreement',
        'trial_date',
        'start_date',
        'reason_for_choosing',
        'information_source',
    ];
}
