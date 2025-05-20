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

        // Menyimpan data pendaftaran ke dalam database
        $registration = RegistrationHikariQuran::create($validatedData);

        // ⏬ Tambahkan peserta ke tabel peserta_hikari_kidz
        PesertaHikariKidz::create([
            'id_anak' => $registration->id,
            'full_name' => $registration->full_name,
            'nickname' => $registration->nickname,
            'birth_date' => $registration->birth_date,
            'parent_name' => $registration->parent_name,
            'address' => $registration->address,
            'whatsapp_number' => $registration->whatsapp_number,
            'tipe' => 'HQ',
            'file_upload' => $registration->file_upload,
        ]);

        // Redirect kembali ke form dengan pesan sukses
        return redirect()->route('registerquran.create')->with('success', 'Data berhasil disimpan!');
    }
}

