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
        switch ($this->registration_type) {
            case 'Hikari Kidz Club':
                // Pastikan model RegistrationHikariKidzClub sudah ada dan benar
                return RegistrationHikariKidzClub::find($this->registration_id);
            case 'Hikari Kidz Daycare':
                // Pastikan model RegistrationHikariKidzDaycare sudah ada dan benar
                return RegistrationHikariKidzDaycare::find($this->registration_id);
            case 'Hikari Quran':
                // Pastikan model RegistrationHikariQuran sudah ada dan benar
                return RegistrationHikariQuran::find($this->registration_id);
            default:
                return null;
        }
    }
}