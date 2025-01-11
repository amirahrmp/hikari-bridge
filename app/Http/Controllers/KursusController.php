<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\PesertaKursus;
use App\Http\Requests\StoreKursusRequest;
use App\Http\Requests\UpdateKursusRequest;
use Illuminate\Http\Request;
use App\Imports\KursusImport;
use Maatwebsite\Excel\Facades\Excel;

class KursusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kursus = Kursus::all();
        return view('kursus.index',
                    [
                        'kursus' => $kursus
                    ]
                  );
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_kursus' => 'required',
            'nama_kursus' => 'required',
            'jenis_kursus' => 'required',
            'level' => 'required',
            'kategori' => 'required',
            'kelas' => 'required',
            'kapasitas' => 'required',
            'waktu' => 'required',
            'biaya' => 'required',
        ]);

        $duplicate = Kursus::where('id_kursus', $request->input('id_kursus'))->exists();

        if ($duplicate) {
            // Jika kode akun sudah ada, redirect kembali dengan pesan error
            $notification = array(
                'message' => 'ID Kursus sudah ada!',
                'alert-type' => 'error'
            );
            return redirect()->route('kursus.index')->with($notification);
        }

        // Membuat data kursus baru
        $kursus = new Kursus();
        $kursus->id_kursus = $request->input('id_kursus');
        $kursus->nama_kursus = $request->input('nama_kursus');
        $kursus->jenis_kursus = $request->input('jenis_kursus');
        $kursus->level = $request->input('level');
        $kursus->kategori = $request->input('kategori');
        $kursus->kelas = $request->input('kelas');
        $kursus->kapasitas = $request->input('kapasitas');
        $kursus->waktu = $request->input('waktu');
        $kursus->biaya = $request->input('biaya');
        $kursus->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data kursus berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman kursus dengan pesan sukses
        return redirect()->route('kursus.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new KursusImport, $file);

        $notification = array(
            'message' => 'Data kursus berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kursus' => 'required',
            'nama_kursus' => 'required',
            'jenis_kursus' => 'required',
            'level' => 'required',
            'kategori' => 'required',
            'kelas' => 'required',
            'kapasitas' => 'required',
            'waktu' => 'required',
            'biaya' => 'required',
        ]);

        $duplicate = Kursus::where('id_kursus', $request->input('id_kursus'))->exists();

        if ($duplicate) {
            // Jika kode akun sudah ada, redirect kembali dengan pesan error
            $notification = array(
                'message' => 'ID Kursus sudah ada!',
                'alert-type' => 'error'
            );
            return redirect()->route('kursus.index')->with($notification);
        }

        // Cari kursus berdasarkan ID
        $kursus = Kursus::findOrFail($id);

        // Update data kursus
        $kursus->id_kursus = $request->input('id_kursus');
        $kursus->nama_kursus = $request->input('nama_kursus');
        $kursus->jenis_kursus = $request->input('jenis_kursus');
        $kursus->level = $request->input('level');
        $kursus->kategori = $request->input('kategori');
        $kursus->kelas = $request->input('kelas');
        $kursus->kapasitas = $request->input('kapasitas');
        $kursus->waktu = $request->input('waktu');
        $kursus->biaya = $request->input('biaya');
        
        // Simpan perubahan
        $kursus->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data kursus berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman kursus dengan pesan sukses
        return redirect()->route('kursus.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kursus $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data kursus berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }

    public function show(Request $request)
    {
        // Ambil daftar kursus dan peserta
        $kursusList = Kursus::all();
        $pesertaList = PesertaKursus::all();

        // Jika ada filter kursus_id, ambil peserta berdasarkan kursus yang dipilih
        if ($request->has('kursus_id') && !empty($request->kursus_id)) {
            $kursus = Kursus::with('pesertaKursus')->find($request->kursus_id);
            $peserta = $kursus ? $kursus->pesertaKursus : collect();
        } else {
            $peserta = collect(); // Kosongkan peserta jika tidak ada filter
        }

        return view('detail_kursus.index', compact('kursusList', 'peserta', 'pesertaList'));
    }
}
