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
    protected $table = 'payments';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function components()
    {
        return $this->hasMany(PaymentComponent::class, 'payment_id');
    }

    public function sppBulanan() { return $this->belongsTo(SppBill::class, 'spp_bulanan_id'); }
    public function overtimeBill() { return $this->belongsTo(OvertimeBill::class, 'overtime_bill_id'); }
    public function mealBill() { return $this->belongsTo(MealBill::class, 'meal_bill_id'); }

    public function scopeTerverifikasi($query) { return $query->where('status', 'terverifikasi'); }
    public function scopeFilterTanggal($query, $start, $end) { return $query->whereBetween('created_at', [$start, $end]); }

    public function getPesertaAttribute()
    {
        $userId = $this->user_id;
        switch ($this->registration_type) {
            case RegistrationHikariKidzClub::class:
                return RegistrationHikariKidzClub::where('id', $this->registration_id)->where('user_id', $userId)->first();
            case RegistrationHikariKidzDaycare::class:
                return RegistrationHikariKidzDaycare::where('id', $this->registration_id)->where('user_id', $userId)->first();
            default:
                return null;
        }
    }

    public function getProgramDisplayNameAttribute()
    {
        switch ($this->registration_type) {
            case RegistrationHikariKidzClub::class: return 'Hikari Kidz Club';
            case RegistrationHikariKidzDaycare::class: return 'Hikari Kidz Daycare';
            default: return '-';
        }
    }

    public function getNamaPaketAttribute()
    {
        $peserta = $this->peserta;
        if ($peserta) {
            switch ($this->registration_type) {
                case RegistrationHikariKidzClub::class:
                    return $peserta->getPaketHkc()?->nama_paket ?? '-';
                case RegistrationHikariKidzDaycare::class:
                    return $peserta->paket->nama_paket ?? '-';
                default:
                    return '-';
            }
        }
        return '-';
    }
}
