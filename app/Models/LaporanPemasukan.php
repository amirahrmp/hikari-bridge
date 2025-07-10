<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentComponent;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\SppBill;
use App\Models\OvertimeBill;
use App\Models\MealBill;
// use App\Models\RegistrationHikariQuran; // <-- DIHAPUS

class LaporanPemasukan extends Model
{
    protected $table = 'payments';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggal' => 'datetime', // PENTING: Tambahkan ini jika kolom 'tanggal' ada di tabel 'payments' dan digunakan untuk filter
    ];

    public function components()
    {
        return $this->hasMany(PaymentComponent::class, 'payment_id');
    }

    public function sppBulanan() { return $this->belongsTo(SppBill::class, 'spp_bulanan_id'); }
    public function overtimeBill() { return $this->belongsTo(OvertimeBill::class, 'overtime_bill_id'); }
    public function mealBill() { return $this->belongsTo(MealBill::class, 'meal_bill_id'); }

    public function scopeTerverifikasi($query) { return $query->where('status', 'terverifikasi'); }
    // Asumsi 'tanggal' adalah kolom yang digunakan untuk filter tanggal, sesuaikan jika Anda menggunakan 'created_at'
    public function scopeFilterTanggal($query, $start, $end) { return $query->whereBetween('tanggal', [$start, $end]); }

    public function getPesertaAttribute()
    {
        $userId = $this->user_id;
        switch ($this->registration_type) {
            case RegistrationHikariKidzClub::class:
                return RegistrationHikariKidzClub::where('id', $this->registration_id)->where('user_id', $userId)->first();
            case RegistrationHikariKidzDaycare::class:
                return RegistrationHikariKidzDaycare::where('id', $this->registration_id)->where('user_id', $userId)->first();
            // case RegistrationHikariQuran::class: // <-- DIHAPUS
            //     return RegistrationHikariQuran::where('id', $this->registration_id)->where('user_id', $userId)->first();
            default:
                return null;
        }
    }

    public function getProgramDisplayNameAttribute()
    {
        switch ($this->registration_type) {
            case RegistrationHikariKidzClub::class: return 'Hikari Kidz Club';
            case RegistrationHikariKidzDaycare::class: return 'Hikari Kidz Daycare';
            // case RegistrationHikariQuran::class: return 'Hikari Quran'; // <-- DIHAPUS
            default: return '-';
        }
    }

    public function getNamaPaketAttribute()
    {
        $peserta = $this->peserta;
        if ($peserta) {
            switch ($this->registration_type) {
                case RegistrationHikariKidzClub::class:
                    $paket = $peserta->getPaketHkc(); // panggil sebagai fungsi
                    $member = $paket?->member ?? '-';
                    $kelas = $paket?->kelas ?? '-';
                    return $member . ' - ' . $kelas;
                case RegistrationHikariKidzDaycare::class:
                    return $peserta->paket->nama_paket ?? '-';
                // case RegistrationHikariQuran::class: // <-- DIHAPUS
                //     return $peserta->paket->nama_paket ?? '-';
                default:
                    return '-';
            }
        }
        return '-';
    }

    /**
     * Accessor untuk menentukan apakah pembayaran ini adalah pendaftaran baru.
     * Asumsi: Pendaftaran baru tidak memiliki spp_bulanan_id, overtime_bill_id, atau meal_bill_id yang terisi.
     * @return bool
     */
    public function isNewRegistration(): bool
    {
        // Mendapatkan FQCN dari model registrasi yang valid
        $validRegistrationTypes = [
            RegistrationHikariKidzDaycare::class,
            RegistrationHikariKidzClub::class,
            // RegistrationHikariQuran::class, // <-- DIHAPUS
        ];

        return (
            in_array($this->registration_type, $validRegistrationTypes) &&
            is_null($this->spp_bulanan_id) &&
            is_null($this->overtime_bill_id) &&
            is_null($this->meal_bill_id)
        );
    }
}