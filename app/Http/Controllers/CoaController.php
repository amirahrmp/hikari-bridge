<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Http\Requests\StoreCoaRequest;
use App\Http\Requests\UpdateCoaRequest;
use Illuminate\Http\Request;
use App\Imports\CoaImport;
use Maatwebsite\Excel\Facades\Excel;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $coa = Coa::all();
        return view('coa.index',
                    [
                        'coa' => $coa
                    ]
                );
    }
 
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'header_akun' => 'required',
        ]);
        $duplicate = Coa::where('kode_akun', $request->input('kode_akun'))->exists();

        if ($duplicate) {
            // Jika kode akun sudah ada, redirect kembali dengan pesan error
            $notification = array(
                'message' => 'Kode akun sudah ada!',
                'alert-type' => 'error'
            );
            return redirect()->route('coa.index')->with($notification);
        }
 
        // Membuat data coa baru
        $coa = new Coa();
        $coa->kode_akun = $request->input('kode_akun');
        $coa->nama_akun = $request->input('nama_akun');
        $coa->header_akun = $request->input('header_akun');
        $coa->save();
 
        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data coa berhasil ditambahkan!',
            'alert-type' => 'success'
        );
 
        // Redirect ke halaman coa dengan pesan sukses
        return redirect()->route('coa.index')->with($notification);
    }
 
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);
 
        $file = $request->file('excel_file');
 
        // Mengimpor data ke dalam database
        Excel::import(new CoaImport, $file);
 
        $notification = array(
            'message' => 'Data coa berhasil ditambahkan!',
            'alert-type' => 'success'
        );
 
        return redirect()->back()->with($notification);
    }
 
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'header_akun' => 'required',
        ]);

        // Cek apakah kode akun sudah ada, kecuali untuk akun yang sedang diupdate
        $duplicate = Coa::where('kode_akun', $request->input('kode_akun'))
                        ->where('id', '!=', $id) // Pastikan tidak memeriksa akun yang sedang diupdate
                        ->exists();

        if ($duplicate) {
            // Jika kode akun sudah ada, redirect kembali dengan pesan error
            $notification = array(
                'message' => 'Kode akun sudah ada!',
                'alert-type' => 'error'
            );
            return redirect()->route('coa.index')->with($notification);
        }
 
        // Cari coa berdasarkan ID
        $coa = Coa::findOrFail($id);
 
        // Update data coa
        $coa->kode_akun = $request->input('kode_akun');
        $coa->nama_akun = $request->input('nama_akun');
        $coa->header_akun = $request->input('header_akun');
         
        // Simpan perubahan
        $coa->save();  // Hanya satu kali save, karena sudah memodifikasi data
 
        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data coa berhasil diubah!',
            'alert-type' => 'success'
        );
 
        // Redirect kembali ke halaman coa dengan pesan sukses
        return redirect()->route('coa.index')->with($notification);
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coa $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data coa berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }
}
