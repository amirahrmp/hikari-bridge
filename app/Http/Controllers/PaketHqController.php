<?php

namespace App\Http\Controllers;

use App\Models\PaketHq;
use App\Http\Requests\StorepaketRequest;
use App\Http\Requests\UpdatepaketRequest;
use Illuminate\Http\Request;
use App\Imports\PaketHqImport;
use Maatwebsite\Excel\Facades\Excel;

class PaketHqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $pakethq = PaketHq::all(); // Mengambil semua data paket
        return view('paket_hq.index', [
            'paket_hq' => $pakethq
        ]);
    }
    
    public function create()
    {
        $paket_hq = PaketHq::all(); // Mengambil semua data paket Hikari Quran
        return view('registerquran.create', compact('paket_hq'));
    }
    

    public function store(Request $request)
    
    {

        // Validasi data input
        $request->validate([
            'id_pakethq' => 'required',
            'kelas' => 'required',
            'kapasitas' => 'required',
            'durasi' => 'required',
            'u_pendaftaran' => 'required|integer',
            'u_modul' => 'required|integer',
            'u_spp' => 'nullable|integer',
        ]);

        // Membuat data paket baru
        $pakethq = new PaketHq();
        $pakethq->id_pakethq = $request->input('id_pakethq');
        $pakethq->kelas = $request->input('kelas');
        $pakethq->kapasitas = $request->input('kapasitas');
        $pakethq->durasi = $request->input('durasi');
        $pakethq->u_pendaftaran = $request->input('u_pendaftaran');
        $pakethq->u_modul = $request->input('u_modul');
        $pakethq->u_spp = $request->input('u_spp');
        $pakethq->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data paket berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman paket dengan pesan sukses
        return redirect()->route('paket_hq.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new PaketHqImport, $file);

        $notification = array(
            'message' => 'Data paket berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pakethq' => 'required',
            'kelas' => 'required',
            'kapasitas' => 'required',
            'durasi' => 'required',
            'u_pendaftaran' => 'required|integer',
            'u_modul' => 'required|integer',
            'u_spp' => 'nullable|integer',
        ]);

        // Cari paket berdasarkan ID
        $pakethq = PaketHq::findOrFail($id);

        // Update data paket
        $pakethq->id_pakethq = $request->input('id_pakethq');
        $pakethq->kelas = $request->input('kelas');
        $pakethq->kapasitas = $request->input('kapasitas');
        $pakethq->durasi = $request->input('durasi');
        $pakethq->u_pendaftaran = $request->input('u_pendaftaran');
        $pakethq->u_modul = $request->input('u_modul');
        $pakethq->u_spp = $request->input('u_spp');
        
        // Simpan perubahan
        $pakethq->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data paket berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman paket dengan pesan sukses
        return redirect()->route('paket_hq.index')->with($notification);
    }

    public function getByKelas($kelas)
    {
        $pakethq = PaketHq::where('kelas', $kelas)->first(); // kamu bisa ubah ke get() kalau banyak
        return response()->json($pakethq);
    }
    public function getPaketHqById($id)
    {
        $pakethq = PaketHq::find($id);
        return response()->json($pakethq);
    }

    public function destroy(PaketHq $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data paket berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }
}
