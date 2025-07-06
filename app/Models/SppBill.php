<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppBill extends Model
{
    use HasFactory;

    protected $table = 'spp_bills'; // Pastikan nama tabelnya ini

    protected $fillable = [
        'registration_id',
        'registration_type',
        'id_anak',
        'full_name',      // Nama lengkap anak
        'program',           // Nama program (e.g., Hikari Kidz Daycare, Hikari Kidz Club, Hikari Quran)
        'package_name',             // Kolom baru untuk menyimpan nama paket (e.g., 'Paket Full Day Bulanan', 'Member (Sakura 3 tahun)')
        'bulan',
        'tahun',
        'nominal_uang_spp',           // Nominal SPP dari paket
        'status',            // 'belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak'
        'user_id',           // User ID pemilik tagihan (bisa NULLABLE di DB jika diizinkan)
        'notes',             // Catatan tambahan jika diperlukan (optional)
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
        return $this->hasOne(Payment::class, 'meal_bill_id');
    }
}