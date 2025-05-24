<?php

namespace App\Http\Controllers;

use App\Models\PaketHq;
use Illuminate\Http\Request;
use App\Models\RegistrationHikariQuran;
use App\Models\PesertaHikariKidz;

class RegistrationHikariQuranController extends Controller
{
    // Menampilkan form pendaftaran
    public function create()
    {
        // Mengambil semua paket yang tersedia
        $paket_hq = PaketHq::all();
        return view('registerquran.create', ['pakethq' => $paket_hq]);
    }

    // Menyimpan data pendaftaran
    public function store(Request $request)
    {
        // Validasi input dari user
        $validatedData = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'file_upload' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'parent_name' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'kelas' => 'nullable|string|max:255',
            'tipe' => 'nullable|in:online,offline',
            'sumberinfo' => 'nullable|in:facebook,instagram,whatsapp,teman,kantor,spanduk,brosur,tetangga,other',
            'promotor' => 'nullable|string|max:255',
        ]);

        // Proses upload file jika ada
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/quran'), $filename);
            $validatedData['file_upload'] = $filename;
        }

        if ($request->kelas) {
            // Mencari paket berdasarkan ID kelas yang dipilih
        $paket_hq = PaketHq::find($request->kelas);
        if ($paket_hq) {
            $validatedData['kelas'] = $paket_hq->kelas;  // Nama kelas
            // Menghitung total biaya
            $total_bayar = $paket_hq->u_pendaftaran +
                           $paket_hq->u_modul +
                           $paket_hq->u_spp;
            $validatedData['total_bayar'] = $total_bayar; // Total biaya yang dihitung
        } 
    }

        
        // Menangani kasus 'sumberinfo' jika 'other' dipilih
        if ($request->input('sumberinfo') === 'other') {
            $validatedData['sumberinfo'] = $request->input('sumberinfo_other');
        }

        // ⏬ Cek apakah anak sudah ada di tabel peserta
        $peserta = PesertaHikariKidz::where('full_name', $validatedData['full_name'])
            ->where('birth_date', $validatedData['birth_date'])
            ->where('parent_name', $validatedData['parent_name'])
            ->first();

        // ⏬ Kalau belum ada, buat data anak baru
        if (!$peserta) {
            // Ambil ID anak terakhir dan tambah 1
            $lastIdAnak = PesertaHikariKidz::max('id_anak');
            $newIdAnak = $lastIdAnak ? $lastIdAnak + 1 : 1;

        // ⏬ Kalau belum ada, buat data anak baru
            $peserta = PesertaHikariKidz::create([
                'id_anak' => $newIdAnak, // Tambahkan ini
                'full_name' => $validatedData['full_name'],
                'nickname' => $validatedData['nickname'],
                'birth_date' => $validatedData['birth_date'],
                'parent_name' => $validatedData['parent_name'],
                'address' => $validatedData['address'],
                'whatsapp_number' => $validatedData['whatsapp_number'],
                'tipe' => 'HQ',
                'file_upload' => $validatedData['file_upload'],
            ]);
        }

        // ⏬ Simpan data registrasi ke tabel RegistrationHikariKidzDaycare
        $registration = new RegistrationHikariQuran($validatedData);
        $registration->id_anak = $peserta->id_anak;
        $registration->save();

        // Redirect kembali ke form dengan pesan sukses
        return redirect()->route('registerquran.create')->with('success', 'Data berhasil disimpan!');
    }
}

