<?php

namespace App\Http\Controllers;

use App\Models\PesertaKursus;
use App\Http\Requests\StorePesertaKursusRequest;
use App\Http\Requests\UpdatePesertaKursusRequest;
use Illuminate\Http\Request;
use App\Imports\PesertaKursusImport;
use Maatwebsite\Excel\Facades\Excel;

class PesertaKursusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peserta_kursus = PesertaKursus::all();
        return view('peserta_kursus.index',
                    [
                        'peserta_kursus' => $peserta_kursus
                    ]
                  );
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_peserta' => 'required|numeric',
            'nama_peserta' => 'required',
            'nama_ortu' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tgl_lahir' => 'required|date',
        ]);

        // Membuat data peserta kursus baru
        $peserta_kursus = new PesertaKursus();
        $peserta_kursus->id_peserta = $request->input('id_peserta');
        $peserta_kursus->nama_peserta = $request->input('nama_peserta');
        $peserta_kursus->nama_ortu = $request->input('nama_ortu');
        $peserta_kursus->alamat = $request->input('alamat');
        $peserta_kursus->jk = $request->input('jk');
        $peserta_kursus->telp = $request->input('telp');
        $peserta_kursus->email = $request->input('email');
        $peserta_kursus->tgl_lahir = $request->input('tgl_lahir');
        $peserta_kursus->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data peserta kursus berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman peserta kursus dengan pesan sukses
        return redirect()->route('peserta_kursus.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new PesertaKursusImport, $file);

        $notification = array(
            'message' => 'Data peserta kursus berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_peserta' => 'required|numeric',
            'nama_peserta' => 'required',
            'nama_ortu' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tgl_lahir' => 'required|date',
        ]);

        // Cari peserta kursus berdasarkan ID
        $peserta_kursus = PesertaKursus::findOrFail($id);

        // Update data peserta_kursus
        $peserta_kursus->id_peserta = $request->input('id_peserta');
        $peserta_kursus->nama_peserta = $request->input('nama_peserta');
        $peserta_kursus->nama_ortu = $request->input('nama_ortu');
        $peserta_kursus->alamat = $request->input('alamat');
        $peserta_kursus->jk = $request->input('jk');
        $peserta_kursus->telp = $request->input('telp');
        $peserta_kursus->email = $request->input('email');
        $peserta_kursus->tgl_lahir = $request->input('tgl_lahir');
        
        // Simpan perubahan
        $peserta_kursus->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data peserta kursus berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman peserta_kursus dengan pesan sukses
        return redirect()->route('peserta_kursus.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PesertaKursus $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data peserta kursus berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }

    public function show($id)
    {
        // Ambil peserta berdasarkan ID dan sertakan kursus yang mereka ikuti
        $peserta = PesertaKursus::with('kursus')->findOrFail($id);

        // Tampilkan view dengan data peserta dan kursus yang diikuti
        return view('peserta_kursus.detail', compact('peserta'));
    }
}
