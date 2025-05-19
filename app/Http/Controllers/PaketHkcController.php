<?php

namespace App\Http\Controllers;

use App\Models\PaketHkc;
use App\Http\Requests\StorepaketRequest;
use App\Http\Requests\UpdatepaketRequest;
use Illuminate\Http\Request;
use App\Imports\PaketHkcImport;
use Maatwebsite\Excel\Facades\Excel;

class PaketHkcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $pakethkc = PaketHkc::all();
        return view('paket_hkc.index',
                    [
                        'paket_hkc' => $pakethkc
                    ]
                  );
    }

    public function store(Request $request)
    
    {

        // Validasi data input
        $request->validate([
            'id_pakethkc' => 'required',
            'member' => 'required',
            'kelas' => 'required',
            'u_pendaftaran' => 'required|integer',
            'u_perlengkapan' => 'required|integer',
            'u_sarana' => 'nullable|integer',
            'u_spp' => 'nullable|integer',
            'tipe' => 'required',
        ]);

        // Membuat data paket baru
        $pakethkc = new PaketHkc();
        $pakethkc->id_pakethkc = $request->input('id_pakethkc');
        $pakethkc->member = $request->input('member');
        $pakethkc->kelas = $request->input('kelas');
        $pakethkc->u_pendaftaran = $request->input('u_pendaftaran');
        $pakethkc->u_perlengkapan = $request->input('u_perlengkapan');
        $pakethkc->u_sarana = $request->input('u_sarana');
        $pakethkc->u_spp = $request->input('u_spp');
        $pakethkc->tipe = $request->input('tipe');
        $pakethkc->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data paket berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman paket dengan pesan sukses
        return redirect()->route('paket_hkc.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new PaketHkcImport, $file);

        $notification = array(
            'message' => 'Data paket berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pakethkc' => 'required',
            'member' => 'required',
            'kelas' => 'required',
            'u_pendaftaran' => 'required|integer',
            'u_perlengkapan' => 'required|integer',
            'u_sarana' => 'nullable|integer',
            'u_spp' => 'nullable|integer',
            'tipe' => 'required',
        ]);

        // Cari paket berdasarkan ID
        $pakethkc = PaketHkc::findOrFail($id);

        // Update data paket
        $pakethkc->id_pakethkc = $request->input('id_pakethkc');
        $pakethkc->member = $request->input('member');
        $pakethkc->kelas = $request->input('kelas');
        $pakethkc->u_pendaftaran = $request->input('u_pendaftaran');
        $pakethkc->u_perlengkapan = $request->input('u_perlengkapan');
        $pakethkc->u_sarana = $request->input('u_sarana');
        $pakethkc->u_spp = $request->input('u_spp');
        $pakethkc->tipe = $request->input('tipe');
        
        // Simpan perubahan
        $pakethkc->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data paket berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman paket dengan pesan sukses
        return redirect()->route('paket_hkc.index')->with($notification);
    }

    public function getByMemberAndKelas($member, $kelas)
    {
        // Cari paket berdasarkan member dan kelas
        $pakethkc = PaketHkc::where('member', $member)
                            ->where('kelas', $kelas)
                            ->get();

        // Cek jika data ditemukan
        if ($pakethkc->isNotEmpty()) {
            return response()->json($pakethkc);
        }

        // Jika tidak ada data ditemukan
        return response()->json(['message' => 'Paket tidak ditemukan'], 404);
    }
    public function getPaketHkcById($id)
    {
        $pakethkc = PaketHkc::find($id);
        return response()->json($pakethkc);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaketHkc $id)
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
