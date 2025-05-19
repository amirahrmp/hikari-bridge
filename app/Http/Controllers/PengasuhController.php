<?php

namespace App\Http\Controllers;

use App\Models\Pengasuh;
use App\Http\Requests\StorepengasuhRequest;
use App\Http\Requests\UpdatepengasuhRequest;
use Illuminate\Http\Request;
use App\Imports\PengasuhImport;
use Maatwebsite\Excel\Facades\Excel;

class PengasuhController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $pengasuh = Pengasuh::all();
        return view('pengasuh.index',
                    [
                        'pengasuh' => $pengasuh
                    ]
                  );
    }

    public function store(Request $request)
    
    {

        // Validasi data input
        $request->validate([
            'id_card' => 'required|numeric',
            'nik' => 'required|numeric',
            'nama_pengasuh' => 'required',
            'tipe_pengasuh' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Membuat data pengasuh baru
        $pengasuh = new Pengasuh();
        $pengasuh->id_card = $request->input('id_card');
        $pengasuh->nik = $request->input('nik');
        $pengasuh->nama_pengasuh = $request->input('nama_pengasuh');
        $pengasuh->tipe_pengasuh = $request->input('tipe_pengasuh');
        $pengasuh->alamat = $request->input('alamat');
        $pengasuh->jk = $request->input('jk');
        $pengasuh->telp = $request->input('telp');
        $pengasuh->email = $request->input('email');
        $pengasuh->tmp_lahir = $request->input('tmp_lahir');
        $pengasuh->tgl_lahir = $request->input('tgl_lahir');
        $pengasuh->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data tenaga pengasuh berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman pengasuh dengan pesan sukses
        return redirect()->route('pengasuh.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new PengasuhImport, $file);

        $notification = array(
            'message' => 'Data tenaga pengasuh berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_card' => 'required|numeric',
            'nik' => 'required|numeric',
            'nama_pengasuh' => 'required',
            'tipe_pengasuh' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Cari pengasuh berdasarkan ID
        $pengasuh = Pengasuh::findOrFail($id);

        // Update data pengasuh
        $pengasuh->id_card = $request->input('id_card');
        $pengasuh->nik = $request->input('nik');
        $pengasuh->nama_pengasuh = $request->input('nama_pengasuh');
        $pengasuh->tipe_pengasuh = $request->input('tipe_pengasuh');
        $pengasuh->alamat = $request->input('alamat');
        $pengasuh->jk = $request->input('jk');
        $pengasuh->telp = $request->input('telp');
        $pengasuh->email = $request->input('email');
        $pengasuh->tmp_lahir = $request->input('tmp_lahir');
        $pengasuh->tgl_lahir = $request->input('tgl_lahir');
        
        // Simpan perubahan
        $pengasuh->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data tenaga pengasuh berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman pengasuh dengan pesan sukses
        return redirect()->route('pengasuh.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengasuh $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data tenaga pengasuh berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }
}
