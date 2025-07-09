<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentComponent;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\SppBill;
use App\Models\OvertimeBill;
use App\Models\MealBill;

class LaporanPemasukan extends Model
{
    // Gunakan tabel 'payments' (read-only untuk laporan)
    protected $table = 'payments';

    // Tidak pakai timestamps karena ini untuk keperluan laporan
    public $timestamps = false;

    // Tidak perlu fillable karena model hanya digunakan untuk baca data
    protected $guarded = [];

    /**
     * Tambahkan casting untuk kolom tanggal agar otomatis menjadi objek Carbon
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime', // Tambahkan juga jika ada kolom updated_at di tabel payments
    ];

    /**
     * Relasi ke komponen pembayaran manual
     */
    public function components()
    {
        return $this->hasMany(PaymentComponent::class, 'payment_id');
    }

    /**
     * Relasi ke tagihan SPP bulanan (jika pembayaran berasal dari tagihan spp)
     */
    public function sppBulanan()
    {
        return $this->belongsTo(SppBill::class, 'spp_bulanan_id');
    }

    /**
     * Relasi ke tagihan overtime (jika pembayaran berasal dari denda overtime)
     */
    public function overtimeBill()
    {
        return $this->belongsTo(OvertimeBill::class, 'overtime_bill_id');
    }

    /**
     * Relasi ke tagihan uang makan (jika pembayaran berasal dari meal bill)
     */
    public function mealBill()
    {
        return $this->belongsTo(MealBill::class, 'meal_bill_id');
    }

    /**
     * Scope untuk ambil pembayaran yang sudah diverifikasi
     */
    public function scopeTerverifikasi($query)
    {
        return $query->where('status', 'terverifikasi');
    }

    /**
     * Scope untuk filter berdasarkan range tanggal
     */
    public function scopeFilterTanggal($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Akses data peserta berdasarkan jenis program & user_id
     * Ini adalah accessor yang akan mengambil detail peserta dari tabel registrasi
     */
    public function getPesertaAttribute()
    {
        $userId = $this->user_id;

        switch ($this->registration_type) {
            case RegistrationHikariKidzClub::class: // Menggunakan FQCN
                return RegistrationHikariKidzClub::where('id', $this->registration_id)
                                                 ->where('user_id', $userId)
                                                 ->first();

            case RegistrationHikariKidzDaycare::class: // Menggunakan FQCN
                return RegistrationHikariKidzDaycare::where('id', $this->registration_id)
                                                     ->where('user_id', $userId)
                                                     ->first();
            // Tambahkan case untuk Hikari Quran jika ada
            // case RegistrationHikariQuran::class:
            //     return RegistrationHikariQuran::where('id', $this->registration_id)
            //                                    ->where('user_id', $userId)
            //                                    ->first();

            default:
                return null;
        }
    }

    /**
     * Accessor untuk mendapatkan nama program yang lebih user-friendly
     * Ini akan mengubah FQCN menjadi string yang diinginkan
     */
    public function getProgramDisplayNameAttribute()
    {
        switch ($this->registration_type) {
            case RegistrationHikariKidzClub::class:
                return 'Hikari Kidz Club';
            case RegistrationHikariKidzDaycare::class:
                return 'Hikari Kidz Daycare';
            // Tambahkan case untuk Hikari Quran jika ada
            // case RegistrationHikariQuran::class:
            //     return 'Hikari Quran';
            default:
                return '-';
        }
    }

    /**
     * Accessor untuk mendapatkan nama paket
     * Ini akan mencoba mendapatkan nama paket dari relasi peserta yang sesuai
     */
    public function getNamaPaketAttribute()
    {
        $peserta = $this->peserta; // Menggunakan accessor peserta yang sudah ada

        if ($peserta) {
            switch ($this->registration_type) {
                case RegistrationHikariKidzClub::class:
                    // Memanggil method getPaketHkc() dari model RegistrationHikariKidzClub
                    // Pastikan method ini mengembalikan objek PaketHkc atau null
                    $paket = $peserta->getPaketHkc();
                    return $paket->nama_paket ?? '-'; // Menggunakan null coalescing operator untuk nilai default
                case RegistrationHikariKidzDaycare::class:
                    // Asumsi ada relasi 'paket' di model RegistrationHikariKidzDaycare
                    // Pastikan relasi ini sudah didefinisikan di model RegistrationHikariKidzDaycare
                    return $peserta->paket->nama_paket ?? '-'; // Ganti 'nama_paket' jika nama kolomnya berbeda
                // Tambahkan case untuk Hikari Quran jika ada
                // case RegistrationHikariQuran::class:
                //     return $peserta->pakethq->nama_paket ?? '-'; // Ganti 'nama_paket' jika nama kolomnya berbeda
                default:
                    return '-';
            }
        }
        return '-';
    }
}
