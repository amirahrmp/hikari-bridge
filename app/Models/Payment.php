<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $dates = ['tanggal'];

    protected $fillable = [
        'registration_id',
        'registration_type',
        'user_id',
        'jumlah',
        'bukti_transfer',
        'status',
        'spp_bill_id',
        'overtime_bill_id',
        'meal_bill_id',
        'tanggal',
    ];

    public function sppBill()
    {
        return $this->belongsTo(SppBill::class, 'spp_bill_id');
    }

    public function overtimeBill()
    {
        return $this->belongsTo(OvertimeBill::class, 'overtime_bill_id');
    }

    public function mealBill()
    {
        return $this->belongsTo(MealBill::class, 'meal_bill_id');
    }

    public function components()
    {
        return $this->hasMany(PaymentComponent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->morphTo('registration', 'registration_type', 'registration_id');
    }

    // Accessor untuk mendapatkan objek peserta terkait.
    // Ini mengasumsikan registration() sudah berfungsi dengan baik.
    public function getPesertaAttribute()
    {
        return $this->registration;
    }

    /**
     * Accessor untuk mendapatkan nama program yang akan ditampilkan di riwayat pembayaran.
     * Ini menyederhanakan pengambilan nama program yang benar di view, termasuk periode.
     * Konsisten dengan kebutuhan Anda.
     */
    public function getProgramDisplayAttribute()
    {
        // Prioritaskan tagihan spesifik (SPP, Overtime, Meal)
        if ($this->sppBulanan) {
            $period = Carbon::create()->month($this->sppBulanan->bulan)->format('M Y');
            return 'SPP Bulanan (' . ($this->sppBulanan->paket ?? $this->sppBulanan->program) . ' ' . $period . ')';
        } elseif ($this->overtimeBill) {
            $period = Carbon::create()->month($this->overtimeBill->bulan)->format('M Y');
            return 'Overtime (' . ($this->overtimeBill->package_name ?? $this->overtimeBill->program) . ' ' . $period . ')';
        } elseif ($this->mealBill) {
            $period = Carbon::create()->month($this->mealBill->bulan)->format('M Y');
            return 'Uang Makan (' . ($this->mealBill->package_name ?? $this->mealBill->program) . ' ' . $period . ')';
        }
        // Jika bukan tagihan spesifik, maka ini adalah pembayaran pendaftaran awal.
        elseif ($this->registration) {
            // Kita sudah eager load paket di PaymentController@index, jadi bisa langsung diakses
            if ($this->registration instanceof \App\Models\RegistrationHikariKidzDaycare) {
                return optional($this->registration->paket)->nama_paket ?? 'Hikari Kidz Daycare (Pendaftaran Awal)';
            } elseif ($this->registration instanceof \App\Models\RegistrationHikariKidzClub) {
                $paket = optional($this->registration)->getPaketHkc(); // Menggunakan method, pastikan di eager load atau ini akan memicu query N+1
                return ($paket->member ?? '') . ' (' . ($paket->kelas ?? '') . ' - Hikari Kidz Club (Pendaftaran Awal))';
            } elseif ($this->registration instanceof \App\Models\RegistrationHikariQuran) {
                return optional($this->registration->pakethq)->nama_paket ?? 'Hikari Quran (Pendaftaran Awal)';
            }
            return $this->registration->program ?? 'Pendaftaran Awal'; // Fallback
        }

        return 'N/A'; // Jika tidak ada tipe registrasi yang cocok
    }

//     public function components()
// {
//     return $this->hasMany(\App\Models\PaymentComponent::class);
// }


    // Acessors untuk program name dan payment period tidak lagi diperlukan
    // karena getProgramDisplayAttribute sudah menanganinya secara komprehensif.
    // public function getProgramNameAttribute() { ... }
    // public function getPaymentPeriodAttribute() { ... }
}