<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeBill extends Model
{
    use HasFactory;

    protected $table = 'overtime_bills'; // Pastikan nama tabel ini sesuai dengan database Anda

    protected $fillable = [
        'registration_id',
        'registration_type', // Contoh: App\Models\RegistrationHikariKidzDaycare::class
        'id_anak',           // ID anak dari tabel peserta_hikari_kidz atau registration_hikari_kidz_daycares
        'full_name',         // Nama lengkap anak
        'program',           // Nama program (e.g., Hikari Kidz Daycare)
        'package_name',      // Kolom baru untuk menyimpan nama paket
        'bulan',
        'tahun',
        'total_overtime_minutes',
        'total_denda',
        'status',            // 'belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak'
        'user_id',           // User ID pemilik tagihan
        'notes',             // Catatan tambahan jika diperlukan
        'bukti_bayar_path',
        'tanggal_bayar',
    ];

    protected $dates = ['tanggal_bayar'];

    public function registration()
    {
        return $this->morphTo('registration', 'registration_type', 'registration_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'overtime_bill_id');
    }
}