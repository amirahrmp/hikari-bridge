<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'komponen',
        'jumlah',
    ]; 

    /**
     * Relasi ke Payment.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}