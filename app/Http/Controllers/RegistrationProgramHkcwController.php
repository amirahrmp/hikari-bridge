<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationProgramHkcw;
use App\Models\PaketHkc;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRegistrationProgramHkcwRequest;

class RegistrationProgramHkcwController extends Controller
{
     public function index()
    {
        $registrasi = RegistrationProgramHkcw::where('user_id', Auth::id())->get();
        return view('registerprogramhkcw.index', compact('registrasi'));
    }
    public function create()
    {
        // Mengambil semua paket yang tersedia
        $paket_hkc = PaketHkc::all();
        return view('registerprogramhkcw.create', ['pakethkc' => $paket_hkc]);
    }

    public function store(StoreRegistrationProgramHkcwRequest $request)
    {
        $validatedData = $request->validate([
            'nama_kegiatan' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'nama_panggilan' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'kelas' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        // Proses upload file jika ada
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/hkcw bukti bayar'), $filename);
            $validatedData['bukti_bayar'] = $filename;
        }

        $validatedData = $request->validated();
    // Menyimpan data pendaftaran ke dalam database
        $validatedData['user_id'] = Auth::id(); // Menyimpan ID user yang sedang login
        RegistrationProgramHkcw::create($validatedData);

        // Redirect kembali ke form dengan pesan sukses
        return redirect()->route('registerprogramhkcw.create')->with('success', 'Data berhasil disimpan!');
    }

}