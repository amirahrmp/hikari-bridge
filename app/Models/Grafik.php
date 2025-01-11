<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Grafik extends Model
{
    use HasFactory;

    // untuk mendapatkan view grafik per bulan berjalan
    public static function viewBulanBerjalan()
    {
        // query kode perusahaan
        $sql = "
            SELECT a.waktu,ifnull(b.total,0) as total FROM 
            v_waktu a 
            LEFT OUTER JOIN
            (
            SELECT DATE_FORMAT(created_at,'%Y-%m') as waktu,
                SUM(total_price) as total
            FROM transactions
            GROUP BY DATE_FORMAT(created_at,'%Y-%m')
            ) b
            ON (a.waktu=b.waktu) ";
        $hasil = DB::select($sql);

        return $hasil;

    }

    // untuk mendapatkan view grafik status penjualan
    public static function viewJmlPenjualan()
    {
        $sql = "SELECT b.nama_barang,sum(a.jml_barang) as jml_penjualan 
                FROM penjualan_detail a 
                    join barang b on (a.id_barang=b.id)
                    join penjualan c on (a.no_transaksi=c.no_transaksi)
                WHERE c.status = 'selesai'
                GROUP BY  b.nama_barang";
        $hasil = DB::select($sql);

        return $hasil;

    }

    // untuk mendapatkan view grafik jml barang terjual
    public static function viewJmlBarangTerjual()
    {
        $sql = "
            SELECT  ax.waktu,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 1
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_artiz,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 2
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_religius,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 3
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_modis,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 4
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_unyuy
            FROM 
            v_waktu ax 
                ";
        $hasil = DB::select($sql);

        return $hasil;

    }

    // untuk mendapatkan view grafik per bulan berjalan
    public static function viewPenjualan()
    {
        // query kode perusahaan
        $sql = "
                    SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as tgl, 
                            SUM(total_price) as total
                    FROM transactions
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
                    ORDER BY 1
               ";
        $hasil = DB::select($sql);

        return $hasil;

    }

    public static function viewTahun(){
        $sql = "
                    SELECT DISTINCT(DATE_FORMAT(created_at,'%Y')) as tahun
                    FROM transactions
                    ORDER BY 1";
        $hasil = DB::select($sql);

        return $hasil;
    }

    // untuk mendapatkan view grafik per bulan berjalan
    public static function viewPenjualanSelectOption($tahun)
    {
        // query kode perusahaan
        $sql = "
                    SELECT a.wkt,ifnull(b.total,0) as total FROM 
                        (SELECT concat(waktu,'-','".$tahun."') as wkt
                         FROM v_waktu_parameter 
                        ) a 
                    LEFT OUTER JOIN
                    (
                        SELECT DATE_FORMAT(created_at,'%m-%Y') as waktu,
                            SUM(total_price) as total
                        FROM transactions
                        WHERE DATE_FORMAT(created_at,'%Y') = ?
                        GROUP BY DATE_FORMAT(created_at,'%m-%Y')
                    ) b
                    ON (a.wkt=b.waktu) 
                    ";
        $hasil = DB::select($sql,[$tahun]);

        return $hasil;

    }
}
