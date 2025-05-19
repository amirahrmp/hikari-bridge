<?php

namespace App\Http\Controllers;

use App\Models\PesertaHikariKidz;
use App\Http\Requests\StorePesertaHikariKidzRequest;
use App\Http\Requests\UpdatePesertaHikariKidzRequest;
use Illuminate\Http\Request;
use App\Imports\PesertaHikariKidzImport;
use Maatwebsite\Excel\Facades\Excel;

class PesertaHikariKidzController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peserta_hikari_kidz = PesertaHikariKidz::all();
        return view('peserta_hikari_kidz.index',
                    [
                        'peserta_hikari_kidz' => $peserta_hikari_kidz
                    ]
                  );
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_anak' => 'required|numeric',
            'nama_anak' => 'required',
            'nama_ortu' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Membuat Data Anak Peserta Hikari Kidz baru
        $peserta_hikari_kidz = new PesertaHikariKidz();
        $peserta_hikari_kidz->id_anak = $request->input('id_anak');
        $peserta_hikari_kidz->nama_anak = $request->input('nama_anak');
        $peserta_hikari_kidz->nama_ortu = $request->input('nama_ortu');
        $peserta_hikari_kidz->alamat = $request->input('alamat');
        $peserta_hikari_kidz->jk = $request->input('jk');
        $peserta_hikari_kidz->telp = $request->input('telp');
        $peserta_hikari_kidz->email = $request->input('email');
        $peserta_hikari_kidz->tmp_lahir = $request->input('tmp_lahir');
        $peserta_hikari_kidz->tgl_lahir = $request->input('tgl_lahir');
        $peserta_hikari_kidz->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data Anak Peserta Hikari Kidz berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman peserta hikarikidz dengan pesan sukses
        return redirect()->route('peserta_hikari_kidz.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new PesertaHikariKidzImport, $file);

        $notification = array(
            'message' => 'Data Anak Peserta Hikari Kidz berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_anak' => 'required|numeric',
            'nama_anak' => 'required',
            'nama_ortu' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Cari peserta hikarikidz berdasarkan ID
        $peserta_hikari_kidz = PesertaHikariKidz::findOrFail($id);

        // Update data peserta_hikari_kidz
        $peserta_hikari_kidz->id_anak = $request->input('id_anak');
        $peserta_hikari_kidz->nama_anak = $request->input('nama_anak');
        $peserta_hikari_kidz->nama_ortu = $request->input('nama_ortu');
        $peserta_hikari_kidz->alamat = $request->input('alamat');
        $peserta_hikari_kidz->jk = $request->input('jk');
        $peserta_hikari_kidz->telp = $request->input('telp');
        $peserta_hikari_kidz->email = $request->input('email');
        $peserta_hikari_kidz->tmp_lahir = $request->input('tmp_lahir');
        $peserta_hikari_kidz->tgl_lahir = $request->input('tgl_lahir');
        
        // Simpan perubahan
        $peserta_hikari_kidz->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data Anak Peserta Hikari Kidz berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman peserta_hikari_kidz dengan pesan sukses
        return redirect()->route('peserta_hikari_kidz.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PesertaHikariKidz $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data Anak Peserta Hikari Kidz berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }

    public function show($id)
    {
        // Ambil peserta berdasarkan ID dan sertakan hikarikidz yang mereka ikuti
        $peserta = PesertaHikariKidz::with('hikarikidz')->findOrFail($id);

        // Tampilkan view dengan data peserta dan hikarikidz yang diikuti
        return view('peserta_hikari_kidz.detail', compact('peserta'));
    }
}
