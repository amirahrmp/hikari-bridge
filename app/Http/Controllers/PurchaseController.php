<?php

namespace App\Http\Controllers;

use App\Models\Purchase; //load model dari kelas model Purchase
use App\Models\Supplier; //load model dari kelas model Supplier

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use Illuminate\Support\Facades\DB; // untuk query 
use Illuminate\Support\Facades\Validator;
// https://www.fundaofwebit.com/post/laravel-8-ajax-crud-with-example

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static function index()
    {
         // // mengambil data purchase dan purchase dari database
    	$purchase = Purchase::getPurchaseDetailSupplier();
        $supplier = Supplier::orderBy('nama_perusahaan')->get(); 

        return view('purchase/view2',
            [
                'purchase' => $purchase,
                'supplier' => $supplier
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
    $purchases = Purchase::getPurchaseDetailSupplier();
    $output = '';
    if (count($purchases) > 0) {
        $output .= '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="thead-dark">
          <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Supplier</th>
            <th>Harga</th>
            <th>Quantity</th>
            <th>Total</th>
            <th style="text-align: center">Aksi</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($purchases as $purchase) {
            $output .= '<tr>
            <td>' . $purchase->kode_bahanbaku . '</td>
            <td>' . $purchase->tanggal_beli . '</td>
            <td>' . $purchase->nama_bahanbaku .'</td>
            <td>' . $purchase->nama_perusahaan. '</td>
            <td>Rp. ' . number_format($purchase->harga). '</td>
            <td>' . $purchase->quantity. '</td>
            <td>Rp. ' . number_format($purchase->total) . '</td>
            <td style="text-align: center">
                <a href="#" onclick="updateConfirm(this); return false;" class="btn btn-success btn-icon-split btn-sm editbtn" value="'.$purchase->id.'" data-id="'.$purchase->id.'" ><span class="icon text-white-50"><i class="ti ti-pencil"></i></span></a>
                <a href="#" onclick="deleteConfirm(this); return false;" href="#" value="'.$purchase->id.'" data-id="'.$purchase->id.'" class="btn btn-danger btn-icon-split btn-sm deletebtn"><span class="icon text-white-50"><i class="ti ti-trash"></i></span>
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
      $purchase = Purchase::getPurchaseDetailSupplier();
      return response()->json([
          'purchases'=>$purchase,
      ]);
  }

      // untuk API view data
      public static function view($id)
      {
          $purchase = Purchase::findOrFail($id);
          echo json_encode($purchase);    
      }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function create()
    {
        return view('purchase/create',
                    [
                        'kode_bahanbaku' => Purchase::getKodePurchase()
                    ]
                  );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCoaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(StorePurchaseRequest $request)
    {
         //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
         $validator = Validator::make(
            $request->all(),
            [
                'kode_bahanbaku' => 'required|min:3',
                'tanggal_beli' => 'required',
                'nama_bahanbaku' => 'required',
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
                Purchase::create($request->all());
                //catat ke jurnal
                DB::table('jurnal')->insert([
                    'id_transaksi' => $request->kode_bahanbaku,
                    'id_perusahaan' => 1, //bisa diganti kalau sudah live
                    'kode_akun' => '102',
                    'tgl_jurnal' => $request->tanggal_beli,
                    'posisi_d_c' => 'd',
                    'nominal' => $request->total,
                    'kelompok' => 1,
                    'transaksi' => 'pembelian',
                ]);

                DB::table('jurnal')->insert([
                    'id_transaksi' => $request->kode_bahanbaku,
                    'id_perusahaan' => 1, //bisa diganti kalau sudah live
                    'kode_akun' => '101',
                    'tgl_jurnal' => $request->tanggal_beli,
                    'posisi_d_c' => 'c',
                    'nominal' => $request->total,
                    'kelompok' => 1,
                    'transaksi' => 'pembelian',
                ]);
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Sukses Input Data',
                    ]
                );
            }else{
                // update ke db
                $purchase = Purchase::find($request->input('idpurchasehidden'));
            
                // proses update dari inputan form data
                $purchase->kode_bahanbaku = $request->input('kode_bahanbaku');
                $purchase->tanggal_beli = $request->input('tanggal_beli');
                $purchase->nama_bahanbaku = $request->input('nama_bahanbaku');
                $purchase->harga = $request->input('harga');
                $purchase->quantity = $request->input('quantity');
                $purchase->total = $request->input('total');
                $purchase->nama_perusahaan = $request->input('nama_perusahaan');
                $purchase->update(); //proses update ke db

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
    public static function show(Purchase $purchase)
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
        $purchase = Purchase::find($id);
        if($purchase)
        {
            return response()->json([
                'status'=>200,
                'purchase'=> $purchase,
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
    public static function update(UpdatePurchaseRequest $request, Purchase $purchase)
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
       $purchase = Purchase::findOrFail($id);
       $purchase->delete();
       
       // mengambil data purchase dan perusahaan dari database
       $purchase = Purchase::all();
       return redirect('purchase');
       
   }
}