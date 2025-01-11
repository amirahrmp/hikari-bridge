<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Grafik;

class GrafikController extends Controller
{
    // view bulan berjalan
    public function viewBulanBerjalan()
    {
        // query kode perusahaan
        $sql = "
            SELECT a.waktu, IFNULL(b.total, 0) AS total FROM 
            v_waktu a 
            LEFT OUTER JOIN
            (
            SELECT DATE_FORMAT(created_at,'%Y-%m') AS waktu,
                SUM(total_price) AS total
            FROM transactions
            GROUP BY DATE_FORMAT(created_at,'%Y-%m')
            ) b
            ON (a.waktu=b.waktu) ";
        $hasil = DB::select($sql);

        // Format hasil query ke dalam bentuk yang bisa diproses oleh chart
        $labels = [];
        $data = [];
        foreach ($hasil as $item) {
            $labels[] = $item->waktu;
            $data[] = $item->total;
        }

        return view('grafik.bulanberjalan', compact('labels', 'data'));
    }

    // view status penjualan
    public function viewJmlPenjualan(){
        $grafik = Grafik::viewJmlPenjualan();
        return view('grafik.jmlpenjualan',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }

    // view jml barang terjual
    public function viewJmlBarangTerjual(){
        $grafik = Grafik::viewJmlBarangTerjual();
        return view('grafik.bulanberjalanperbarang',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }

    // view jml penjualan select option
    public function viewPenjualanSelectOption($tahun){
        $daftartahun = Grafik::viewTahun();
        $grafik = Grafik::viewPenjualanSelectOption($tahun);
        // return $grafik;
        return view('grafik.penjualan',
                        [
                            'grafik' => $grafik,
                            'daftartahun' => $daftartahun
                        ]
                    );
    }

    // viewDataPenjualanSelectOption
    public function viewDataPenjualanSelectOption($tahun){
        $grafik = Grafik::viewPenjualanSelectOption($tahun);
        return response()->json([
            'grafik'=>$grafik,
        ]);
    }
}
