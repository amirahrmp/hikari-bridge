<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Http\Controllers\Controller;
use App\Http\Requests\JabatanRequest;
use Illuminate\Http\Request;
use App\Imports\JabatanImport;
use Maatwebsite\Excel\Facades\Excel;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatan = Jabatan::all();
        return view('jabatan.index',
                    [
                        'jabatan' => $jabatan
                    ]
                  );
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nama_jabatan' => 'required',
            'gaji_pokok' => 'required',
            'transportasi' => 'required',
            'uang_makan' => 'required',
        ]);

        // Membuat data jabatan baru
        $jabatan = new Jabatan();
        $jabatan->nama_jabatan = $request->input('nama_jabatan');
        $jabatan->gaji_pokok = $request->input('gaji_pokok');
        $jabatan->transportasi = $request->input('transportasi');
        $jabatan->uang_makan = $request->input('uang_makan');
        $jabatan->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data jabatan berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman jabatan dengan pesan sukses
        return redirect()->route('jabatan.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new JabatanImport, $file);

        $notification = array(
            'message' => 'Data jabatan berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required',
            'gaji_pokok' => 'required',
            'transportasi' => 'required',
            'uang_makan' => 'required',
        ]);

        // Cari jabatan berdasarkan ID
        $jabatan = Jabatan::findOrFail($id);

        // Update data jabatan
        $jabatan->nama_jabatan = $request->input('nama_jabatan');
        $jabatan->gaji_pokok = $request->input('gaji_pokok');
        $jabatan->transportasi = $request->input('transportasi');
        $jabatan->uang_makan = $request->input('uang_makan');
        
        // Simpan perubahan
        $jabatan->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data jabatan berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman jabatan dengan pesan sukses
        return redirect()->route('jabatan.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data jabatan berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }
}
