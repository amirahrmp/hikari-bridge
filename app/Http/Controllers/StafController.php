<?php

namespace App\Http\Controllers;

use App\Models\Staf;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorestafRequest;
use App\Http\Requests\UpdatestafRequest;
use Illuminate\Http\Request;
use App\Imports\StafImport;
use Maatwebsite\Excel\Facades\Excel;

class StafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staf = Staf::all();
        return view('staf.index',
                    [
                        'staf' => $staf
                    ]
                  );
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_card' => 'required|numeric',
            'status' => 'required',
            'nama_staf' => 'required',
            'jabatan' => 'required',
            'departemen' => 'required',
            'waktu_kerja' => 'required',
            'tipe_staf' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Membuat data staf baru
        $staf = new Staf();
        $staf->id_card = $request->input('id_card');
        $staf->status = $request->input('status');
        $staf->nik = $request->input('nik');
        $staf->nama_staf = $request->input('nama_staf');
        $staf->jabatan = $request->input('jabatan');
        $staf->departemen = $request->input('departemen');
        $staf->waktu_kerja = $request->input('waktu_kerja');
        $staf->tipe_staf = $request->input('tipe_staf');
        $staf->ptkp = $request->input('ptkp');
        $staf->npwp = $request->input('npwp');
        $staf->tgl_masuk_kerja = $request->input('tgl_masuk_kerja');
        $staf->alamat = $request->input('alamat');
        $staf->jk = $request->input('jk');
        $staf->telp = $request->input('telp');
        $staf->email = $request->input('email');
        $staf->tmp_lahir = $request->input('tmp_lahir');
        $staf->tgl_lahir = $request->input('tgl_lahir');
        $staf->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data staf berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman staf dengan pesan sukses
        return redirect()->route('staf.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new StafImport, $file);

        $notification = array(
            'message' => 'Data staf berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_card' => 'required|numeric',
            'status' => 'required',
            'nama_staf' => 'required',
            'jabatan' => 'required',
            'departemen' => 'required',
            'waktu_kerja' => 'required',
            'tipe_staf' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Cari staf berdasarkan ID
        $staf = Staf::findOrFail($id);

        // Update data staf
        $staf->id_card = $request->input('id_card');
        $staf->status = $request->input('status');
        $staf->nik = $request->input('nik');
        $staf->nama_staf = $request->input('nama_staf');
        $staf->jabatan = $request->input('jabatan');
        $staf->departemen = $request->input('departemen');
        $staf->waktu_kerja = $request->input('waktu_kerja');
        $staf->tipe_staf = $request->input('tipe_staf');
        $staf->ptkp = $request->input('ptkp');
        $staf->npwp = $request->input('npwp');
        $staf->tgl_masuk_kerja = $request->input('tgl_masuk_kerja');
        $staf->alamat = $request->input('alamat');
        $staf->jk = $request->input('jk');
        $staf->telp = $request->input('telp');
        $staf->email = $request->input('email');
        $staf->tmp_lahir = $request->input('tmp_lahir');
        $staf->tgl_lahir = $request->input('tgl_lahir');
        
        // Simpan perubahan
        $staf->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data staf berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman staf dengan pesan sukses
        return redirect()->route('staf.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staf $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data staf berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }
}
