<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    // list kolom yang bisa diisi
    protected $fillable = ['id','code','gambar','nama_item','harga_item','quantity','deskripsi'];

    // query nilai max dari kode item untuk generate otomatis kode item
    public static function getid()
    {
        // query kode item
        $sql = "SELECT IFNULL(MAX(id), '000') as id
                FROM items";
        $id = DB::select($sql);

        // cacah hasilnya
        foreach ($id as $kdit) {
            $kd = $kdit->id;
        }
        // Mengambil substring tiga digit akhir dari string IT-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string IT-001
        $noakhir = ''.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;

    }
}
