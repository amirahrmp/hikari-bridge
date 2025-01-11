<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Purchase extends Model
{
     // use HasFactory;
     protected $table = 'purchase';

    // untuk melist kolom yang dapat diisi
    protected $fillable = [
        'kode_bahanbaku',
        'tanggal_beli',
        'nama_bahanbaku',
        'harga',
        'quantity',
        'total',
        'nama_perusahaan',
    ];

    // untuk melihat data coa dan nama perusahaan
    public static function getPurchaseDetailSupplier()
    {
        // query kode perusahaan
        $sql = "SELECT a.*, b.nama_perusahaan, (a.harga * a.quantity) AS total
                FROM purchase a
                JOIN supplier b
                ON (a.nama_perusahaan = b.id)";
        $purchase = DB::select($sql);

        return $purchase;
    }

    public function getKodePurchase()
    {
        // query kode supplier
        $sql = "SELECT IFNULL(MAX(kode_bahanbaku), 'BB-000') as kode_bahanbaku 
                FROM supplier";
        $kodebahanbaku = DB::select($sql);

        // cacah hasilnya
        foreach ($kodebahanbaku as $kdbhnbk) {
            $kd = $kdbhnbk->kode_bahanbaku;
        }
        // Mengambil substring tiga digit akhir dari string SP-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string SP-001
        $noakhir = 'SP-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;
    }
}
