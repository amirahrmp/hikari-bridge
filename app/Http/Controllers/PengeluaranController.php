<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Coa;

use App\Http\Requests\StorePengeluaranRequest;
use App\Http\Requests\UpdatePengeluaranRequest;
use Illuminate\Support\Facades\DB; // untuk query 
use Illuminate\Support\Facades\Validator;
// https://www.fundaofwebit.com/post/laravel-8-ajax-crud-with-example

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public static function index()
     {
          // // mengambil data pengeluaran dan pengeluaran dari database
         $pengeluaran = Pengeluaran::getPengeluaranDetailCoa();
         $coa = Coa::orderBy('nama_akun')->get(); 
 
         return view('pengeluaran/view2',
             [
                 'pengeluaran' => $pengeluaran,
                 'coa' => $coa
             ]
         );
 
 
         // // akses data dari obyek coa
         // $coa = Coa::all();
         // Menggunakan klausa where untuk mencari produk berdasarkan nama
         // $coa = Coa::where('header_akun', '1')
         //             ->where('nama_akun', 'Kas')
         //             ->get();
         // // var_dump($coa);
         // // dd;
         // return view('coa.view',
         //              [
         //                 'coa'=>$coa,
         //                 'title'=>'contoh m2',
         //                 'nama'=>'Farel Prayoga'
         //              ]   
         //             );
     }
 
// handle fetch all coas ajax request
public static function fetchAll() {
    // $coas = Coa::all();
    $pengeluarans = Pengeluaran::all();
    $output = '';
    if (count($pengeluarans) > 0) {
        $output .= '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="thead-dark">
          <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Kode Akun</th>
            <th>Keterangan</th>
            <th>Harga</th>
            <th>Quantity</th>
            <th>Total</th>
            <th style="text-align: center">Aksi</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($pengeluarans as $pengeluaran) {
            $output .= '<tr>
            <td>' . $pengeluaran->kode_pengeluaran . '</td>
            <td>' . $pengeluaran->tanggal_bayar . '</td>
            <td>' . $pengeluaran->nama_akun .'</td>
            <td>' . $pengeluaran->keterangan. '</td>
            <td>Rp. ' . number_format($pengeluaran->harga). '</td>
            <td>' . $pengeluaran->quantity. '</td>
            <td>Rp. ' . number_format($pengeluaran->total) . '</td>
            <td style="text-align: center">
                <a href="#" onclick="updateConfirm(this); return false;" class="btn btn-success btn-icon-split btn-sm editbtn" value="'.$pengeluaran->id.'" data-id="'.$pengeluaran->id.'" ><span class="icon text-white-50"><i class="ti ti-pencil"></i></span></a>
                <a href="#" onclick="deleteConfirm(this); return false;" href="#" value="'.$pengeluaran->id.'" data-id="'.$pengeluaran->id.'" class="btn btn-danger btn-icon-split btn-sm deletebtn"><span class="icon text-white-50"><i class="ti ti-trash"></i></span>
            </td>
          </tr>';
        }
        $output .= '</tbody></table>';
        echo $output;
    } else {
        echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
    }
}



  // fetch data purchase ke dalam format json
  public static function fetchpurchase()
  {
      $pengeluaran = Pengeluaran::getPengeluaranDetailCoa();
      return response()->json([
          'pengeluaran'=>$pengeluaran,
      ]);
  }

      // untuk API view data
      public static function view($id)
      {
          $pengeluaran = Pengeluaran::findOrFail($id);
          echo json_encode($pengeluaran);    
      }
   
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public static function create()
     {
        $pengeluaran = new \App\Models\Pengeluaran();
         return view('pengeluaran/create',
                     [
                         'kode_pengeluaran' => $pengeluaran->getKodePengeluaran()
                     ]
                   );
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \App\Http\Requests\StoreCoaRequest  $request
      * @return \Illuminate\Http\Response
      */
     public static function store(StorePengeluaranRequest $request)
     {
          //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
          $validator = Validator::make(
             $request->all(),
             [
                 'kode_pengeluaran' => 'required|min:3',
                 'keterangan' => 'required',
                 'tanggal_bayar' => 'required',
                 'harga' => 'required',
                 'quantity' => 'required',
             ]
         );
         
         if($validator->fails()){
             // gagal
             return response()->json(
                 [
                     'status' => 400,
                     'errors' => $validator->messages(),
                 ]
             );
         }else{
             // berhasil
 
             // cek apakah tipenya input atau update
             // input => tipeproses isinya adalah tambah
             // update => tipeproses isinya adalah ubah
             
             if($request->input('tipeproses')=='tambah'){
                 // simpan ke db
                 Pengeluaran::create($request->all());
                 //catat ke jurnal
                 DB::table('jurnal')->insert([
                     'id_transaksi' => $request->kode_pengeluaran,
                     'id_perusahaan' => 1, //bisa diganti kalau sudah live
                     'kode_akun' => $request->nama_akun,
                     'tgl_jurnal' => $request->tanggal_bayar,
                     'posisi_d_c' => 'd',
                     'nominal' => $request->total,
                     'kelompok' => 1,
                     'transaksi' => 'beban',
                 ]);
 
                 DB::table('jurnal')->insert([
                     'id_transaksi' => $request->kode_pengeluaran,
                     'id_perusahaan' => 1, //bisa diganti kalau sudah live
                     'kode_akun' => '101',
                     'tgl_jurnal' => $request->tanggal_bayar,
                     'posisi_d_c' => 'c',
                     'nominal' => $request->total,
                     'kelompok' => 1,
                     'transaksi' => 'beban',
                 ]);
                 return response()->json(
                     [
                         'status' => 200,
                         'message' => 'Sukses Input Data',
                     ]
                 );
             }else{
                 // update ke db
                 $pengeluaran = Pengeluaran::find($request->input('idpengeluaranhidden'));
             
                 // proses update dari inputan form data
                 $pengeluaran->kode_pengeluaran = $request->input('kode_pengeluaran');
                 $pengeluaran->tanggal_bayar = $request->input('tanggal_bayar');
                 $pengeluaran->keterangan = $request->input('keterangan');
                 $pengeluaran->harga = $request->input('harga');
                 $pengeluaran->quantity = $request->input('quantity');
                 $pengeluaran->total = $request->input('total');
                 $pengeluaran->nama_akun = $request->input('nama_akun');
                 $pengeluaran->update(); //proses update ke db
 
                 return response()->json(
                     [
                         'status' => 200,
                         'message' => 'Sukses Update Data',
                     ]
                 );
             }
         }
     
     }
 
     /**
      * Display the specified resource.
      *
      * @param  \App\Models\Coa  $coa
      * @return \Illuminate\Http\Response
      */
     public static function show(Pengeluaran $pengeluaran)
     {
         //
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Models\Coa  $coa
      * @return \Illuminate\Http\Response
      */
     //public static function edit(Coa $coa)
     public static function edit($id)
     {
         $pengeluaran = Pengeluaran::find($id);
         if($pengeluaran)
         {
             return response()->json([
                 'status'=>200,
                 'pengeluaran'=> $pengeluaran,
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
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \App\Http\Requests\UpdateCoaRequest  $request
      * @param  \App\Models\Coa  $coa
      * @return \Illuminate\Http\Response
      */
     public static function update(UpdatePengeluaranRequest $request, Pengeluaran $pengeluaran)
     {
         //
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  \App\Models\Coa  $coa
      * @return \Illuminate\Http\Response
      */
    // public static function destroy(Coa $coa)
    public static function destroy($id)
    {
        //hapus dari database
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();
        
        // mengambil data pengeluaran dan perusahaan dari database
        $pengeluaran = Pengeluaran::all();
        return redirect('pengeluaran');
        
    }
}
