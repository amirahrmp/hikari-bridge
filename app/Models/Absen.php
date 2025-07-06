<!-- <?php
// app/Models/Absen.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'registration_id',
        'registration_type',
        'user_id',
        'full_name',
        'jam_datang',
        'jam_pulang',
        'durasi_hadir', // Jika disimpan di DB
        'overtime',     // Jika disimpan di DB
        'denda',
        'manual_denda', // <--- Pastikan ini ada di fillable
        // Tambahkan kolom lain yang relevan jika ada
    ];

    // Accessor untuk mendapatkan total denda gabungan (otomatis + manual)
    public function getTotalDendaAttribute()
    {
        return (float) $this->denda + (float) $this->manual_denda;
    }

    // Relasi ke pendaftaran Daycare
    public function daycareRegistration()
    {
        return $this->belongsTo(RegistrationHikariKidzDaycare::class, 'registration_id');
    }

    // Relasi ke pengguna (orang tua/wali)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi polymorphic ke pembayaran tambahan (untuk denda manual yang akan ditampilkan ke pelanggan)
    public function additionalPayments()
    {
        return $this->morphMany(AdditionalPayment::class, 'source');
    }
} 