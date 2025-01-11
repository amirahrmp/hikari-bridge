<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Pengeluaran extends Model
{
    // use HasFactory;
    protected $table = 'pengeluaran';

    // untuk melist kolom yang dapat diisi
    protected $fillable = [
        'kode_pengeluaran',
        'tanggal_bayar',
        'keterangan',
        'harga',
        'quantity',
        'total',
        'nama_akun',
    ];

    // untuk melihat data coa dan nama perusahaan
    public static function getPengeluaranDetailCoa()
    {
        // query kode perusahaan
        $sql = "SELECT a.*, b.nama_akun, (a.harga * a.quantity) AS total
                FROM pengeluaran a
                JOIN coa b
                ON (a.nama_akun = b.id)";
        $pengeluaran = DB::select($sql);

        return $pengeluaran;
    }

    public function getKodePengeluaran()
    {
        // query kode coa
        $sql = "SELECT IFNULL(MAX(kode_pengeluaran), 'KK-000') as kode_pengeluaran 
                FROM pengeluaran";
        $kodepengeluaran = DB::select($sql);

        // cacah hasilnya
        foreach ($kodepengeluaran as $kdplrn) {
            $kd = $kdplrn->kode_pengeluaran;
        }
        // Mengambil substring tiga digit akhir dari string SP-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string SP-001
        $noakhir = 'KK-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;
    }
}
