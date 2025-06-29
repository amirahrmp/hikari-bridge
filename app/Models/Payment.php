<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Pastikan untuk mengimpor semua model pendaftaran yang relevan
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\PaymentComponent;

class Payment extends Model
{
    protected $table = 'payments'; // ⬅️ PENTING: tambahkan ini
    protected $fillable = [
        'registration_id',
        'registration_type',
        'bukti_transfer',
        'jumlah',
        'status', // Status pembayaran: 'menunggu_verifikasi', 'terverifikasi'
    ];

    /**
     * Relasi ke PaymentComponents.
     */
    public function components()
    {
        return $this->hasMany(PaymentComponent::class);
    }

    /**
     * Accessor untuk mendapatkan model pendaftaran terkait (peserta).
     * Ini akan secara dinamis mengembalikan instance pendaftaran yang benar
     * berdasarkan 'registration_type'.
     */
    public function getPesertaAttribute()
{
    $userId = auth()->id();

    switch ($this->registration_type) {
        case 'Hikari Kidz Club':
            return RegistrationHikariKidzClub::where('id', $this->registration_id)
                                                         ->where('user_id', $userId)
                                                         ->first();
        case 'Hikari Kidz Daycare':
            return RegistrationHikariKidzDaycare::where('id', $this->registration_id)
                                                             ->where('user_id', $userId)
                                                             ->first();
        case 'Hikari Quran':
            return RegistrationHikariQuran::where('id', $this->registration_id)
                                                       ->where('user_id', $userId)
                                                       ->first();
        default:
            return null;
    }
}



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}