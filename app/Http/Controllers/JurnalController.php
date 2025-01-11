<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Http\Requests\StoreJurnalRequest;
use App\Http\Requests\UpdateJurnalRequest;


class JurnalController extends Controller
{
    // jurnal umum
    public function jurnalumum(){
        return view('laporan/jurnalumum');
    }

    // view data jurnal umum
    public function viewdatajurnalumum($periode){
        
        $jurnal = Jurnal::viewjurnalumum($periode);
        if($jurnal)
        {
            return response()->json([
                'status'=>200,
                'jurnal'=> $jurnal,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // buku besar
    public function bukubesar(){
        $akun = Jurnal::viewakunbukubesar();
        return view('laporan/bukubesar',
                        [
                            'akun' => $akun
                        ]
                    );
    }

    // view data buku besar
    public function viewdatabukubesar($periode,$akun){
        //query data
        $saldoawal = Jurnal::viewsaldobukubesar($periode,$akun);
        $posisi = Jurnal::viewposisisaldonormalakun($akun);

        $bukubesar = Jurnal::viewdatabukubesar($periode,$akun);
            return response()->json([
                'status'=>200,
                'bukubesar'=> $bukubesar,
                'saldoawal' => $saldoawal,
                'posisi' => $posisi
            ]);
       
    }
}
