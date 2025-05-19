<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzClub;
use App\Models\PaketHkc;
use App\Models\PesertaHikariKidz;

class RegistrationHikariKidzClubController extends Controller
{
    public function create()
    {
        $paket_hkc = PaketHkc::all();
        return view('registerkidzclub.create', compact('paket_hkc'));
    }

    public function store(Request $request)
    {
        
        // Validasi data
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'file_upload' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'parent_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'address' => 'required|string',
            'agama' => 'required|in:islam,kristen,hindu,budha,konghucu',
            'nonmuslim' => 'nullable|in:paket1,paket2',
            'member' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'information_source' => 'nullable|string|max:255',
            'information_source_other' => 'nullable|required_if:information_source,other|string|max:255',
            'promotor' => 'required|string|max:255',
        ]);
        
        // Proses file upload
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kidzclub'), $filename);
            $validatedData['file_upload'] = $filename;
        }


        // Ambil data paket dan hitung total_bayar
    if ($request->member && $request->kelas) {
        $paket_hkc = PaketHkc::where('member', $request->member)
                             ->where('kelas', $request->kelas)
                             ->first();

        if ($paket_hkc) {
            $validatedData['member'] = $paket_hkc->member;
            $validatedData['kelas'] = $paket_hkc->kelas;

            // Hitung total_bayar berdasarkan paket
            $total_bayar = ($paket_hkc->u_pendaftaran ?? 0) +
                           ($paket_hkc->u_perlengkapan ?? 0) +
                           ($paket_hkc->u_sarana ?? 0) +
                           ($paket_hkc->u_spp ?? 0);

            $validatedData['total_bayar'] = $total_bayar;
        }
    }

        // Jika informasi sumber adalah 'other', ganti information_source dengan isi dari information_source_other
      // Setelah kamu override information_source:
        if ($request->input('information_source') === 'other') {
            $validatedData['information_source'] = $request->input('information_source_other');
        }

        // Buang information_source_other agar tidak ikut di-insert
        unset($validatedData['information_source_other']);
        // Simpan data ke database
        $registration = RegistrationHikariKidzClub::create($validatedData);


        // ⏬ Tambahkan peserta ke tabel peserta_hikari_kidz
        PesertaHikariKidz::create([
            'id_anak' => $registration->id,
            'full_name' => $registration->full_name,
            'nickname' => $registration->nickname,
            'birth_date' => $registration->birth_date,
            'parent_name' => $registration->parent_name,
            'address' => $registration->address,
            'whatsapp_number' => $registration->whatsapp_number,
            'tipe' => 'HKC',
            'file_upload' => $registration->file_upload,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('registerkidzclub.create')->with('success', 'Data berhasil disimpan!');
    }
}
