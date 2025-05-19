<?php

namespace App\Http\Controllers;

use App\Models\PesertaHikariKidz;
use App\Http\Requests\StorePesertaHikariKidzRequest;
use App\Http\Requests\UpdatePesertaHikariKidzRequest;
use Illuminate\Support\Facades\Storage;  // <<--- tambahkan ini
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
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'whatsapp_number' => 'required|numeric|digits_between:5,15',
            'tipe' => 'required|string|max:255',
            'file_upload' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Membuat Data Anak Peserta Hikari Kidz baru
        $peserta_hikari_kidz = new PesertaHikariKidz();
        $peserta_hikari_kidz->id_anak = $request->input('id_anak');
        $peserta_hikari_kidz->full_name = $request->input('full_name');
        $peserta_hikari_kidz->nickname = $request->input('nickname');
        $peserta_hikari_kidz->birth_date = $request->input('birth_date');
        $peserta_hikari_kidz->parent_name = $request->input('parent_name');
        $peserta_hikari_kidz->address = $request->input('address');
        $peserta_hikari_kidz->whatsapp_number = $request->input('whatsapp_number');
        $peserta_hikari_kidz->tipe = $request->input('tipe');
        $peserta_hikari_kidz->file_upload = $request->input('file_upload');

        // Proses file upload
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/peserta', $filename); // tersimpan di storage/app/public/uploads
            $peserta_hikari_kidz->file_upload = $filename;
        }

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
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'whatsapp_number' => 'required|numeric|digits_between:5,15',
            'tipe' => 'required|string|max:255',
            'file_upload' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cari peserta hikarikidz berdasarkan ID
        $peserta_hikari_kidz = PesertaHikariKidz::findOrFail($id);

        // Update data peserta_hikari_kidz
       $peserta_hikari_kidz->id_anak = $request->input('id_anak');
        $peserta_hikari_kidz->full_name = $request->input('full_name');
        $peserta_hikari_kidz->nickname = $request->input('nickname');
        $peserta_hikari_kidz->birth_date = $request->input('birth_date');
        $peserta_hikari_kidz->parent_name = $request->input('parent_name');
        $peserta_hikari_kidz->address = $request->input('address');
        $peserta_hikari_kidz->whatsapp_number = $request->input('whatsapp_number');
        $peserta_hikari_kidz->tipe = $request->input('tipe');
        $peserta_hikari_kidz->file_upload = $request->input('file_upload');
         // ✔️ Cek apakah ada file baru diupload
        if ($request->hasFile('file_upload')) {
            // Hapus file lama jika ada
            if ($peserta_hikari_kidz->file_upload && Storage::exists('uploads/peserta' . $peserta_hikari_kidz->file_upload)) {
                Storage::delete('uploads/peserta' . $peserta_hikari_kidz->file_upload);
            }

            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads', $filename);
            $peserta_hikari_kidz->file_upload = $filename;
             } else {
            // Kalau tidak ada upload file baru, tetap pakai file lama
            $validatedData['file_upload'] = $peserta_hikari_kidz->file_upload;
            }

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
