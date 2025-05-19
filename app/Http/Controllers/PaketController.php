<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Http\Requests\StorepaketRequest;
use App\Http\Requests\UpdatepaketRequest;
use Illuminate\Http\Request;
use App\Imports\PaketImport;
use Maatwebsite\Excel\Facades\Excel;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $paket = Paket::all();
        return view('paket.index',
                    [
                        'paket' => $paket
                    ]
                  );
    }

    public function store(Request $request)
    
    {

        // Validasi data input
        $request->validate([
            'id_paket' => 'required',
            'nama_paket' => 'required',
            'durasi_jam' => 'required',
            'u_pendaftaran' => 'required|integer',
            'u_pangkal' => 'required|integer',
            'u_kegiatan' => 'nullable|integer',
            'u_spp' => 'nullable|integer',
            'u_makan' => 'required|integer',
            'tipe' => 'required',
            'biaya_penitipan' => 'nullable|integer'
        ]);

        // Membuat data paket baru
        $paket = new Paket();
        $paket->id_paket = $request->input('id_paket');
        $paket->nama_paket = $request->input('nama_paket');
        $paket->durasi_jam = $request->input('durasi_jam');
        $paket->u_pendaftaran = $request->input('u_pendaftaran');
        $paket->u_pangkal = $request->input('u_pangkal');
        $paket->u_kegiatan = $request->input('u_kegiatan');
        $paket->u_spp = $request->input('u_spp');
        $paket->u_makan = $request->input('u_makan');
        $paket->tipe = $request->input('tipe');
        $paket->biaya_penitipan = $request->input('biaya_penitipan');
        $paket->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data paket berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman paket dengan pesan sukses
        return redirect()->route('paket.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new PaketImport, $file);

        $notification = array(
            'message' => 'Data paket berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_paket' => 'required',
            'nama_paket' => 'required',
            'durasi_jam' => 'required',
            'u_pendaftaran' => 'required|integer',
            'u_pangkal' => 'required|integer',
            'u_kegiatan' => 'nullable|integer',
            'u_spp' => 'nullable|integer',
            'u_makan' => 'required|integer',
            'tipe' => 'required',
            'biaya_penitipan' => 'nullable|integer',
        ]);

        // Cari paket berdasarkan ID
        $paket = Paket::findOrFail($id);

        // Update data paket
        $paket->id_paket = $request->input('id_paket');
        $paket->nama_paket = $request->input('nama_paket');
        $paket->durasi_jam = $request->input('durasi_jam');
        $paket->u_pendaftaran = $request->input('u_pendaftaran');
        $paket->u_pangkal = $request->input('u_pangkal');
        $paket->u_kegiatan = $request->input('u_kegiatan');
        $paket->u_spp = $request->input('u_spp');
        $paket->u_makan = $request->input('u_makan');
        $paket->tipe = $request->input('tipe');
        $paket->biaya_penitipan = $request->input('biaya_penitipan');
        
        // Simpan perubahan
        $paket->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data paket berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman paket dengan pesan sukses
        return redirect()->route('paket.index')->with($notification);
    }

    public function getByTipe($tipe)
    {
        $paket = Paket::where('tipe', $tipe)->first(); // kamu bisa ubah ke get() kalau banyak
        return response()->json($paket);
    }
    public function getPaketById($id)
{
    $paket = Paket::find($id);
    return response()->json($paket);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paket $id)
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
