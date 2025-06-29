<?php

namespace App\Models;

use App\Models\PesertaHikariKidz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanTambahan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_tambahan';
    protected $primaryKey = 'id'; // Asumsi 'id' adalah primary key
    public $incrementing = true; // Asumsi auto-incrementing
    protected $fillable = [
        'id_anak',
        'nama_kegiatan',
        'biaya',
        'deskripsi',
        'status_pembayaran',
        'bukti_pembayaran', // Pastikan nama kolom ini di DB Anda
        'payment_method',
        'payment_date',
    ];

    public function anak()
    {
        return $this->belongsTo(PesertaHikariKidz::class, 'id_anak', 'id_anak');
    }
}