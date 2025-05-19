<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    public function paketDaycare()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function paketHKC()
    {
        return $this->belongsTo(PaketHKC::class, 'paket_id');
    }

    public function paketQuran()
    {
        return $this->belongsTo(PaketHq::class, 'paket_id');
    }
}
