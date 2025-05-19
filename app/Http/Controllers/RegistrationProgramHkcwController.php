<?php

namespace App\Http\Controllers;

use App\Models\PaketHkc;
use App\Models\RegistrationProgramHkcw;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRegistrationProgramHkcwRequest;
use App\Http\Requests\UpdateRegistrationProgramHkcwRequest;

class RegistrationProgramHkcwController extends Controller
{
    public function create()
    {
        // Mengambil semua paket yang tersedia
        $paket_hkc = PaketHkc::all();
        return view('registerprogramhkcw.create', ['pakethkc' => $paket_hkc]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegistrationProgramHkcwRequest  $request
     * @return \Illuminate\Http\Response
     */
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
        RegistrationProgramHkcw::create($validatedData);

        // Redirect kembali ke form dengan pesan sukses
        return redirect()->route('registerprogramhkcw.create')->with('success', 'Data berhasil disimpan!');
    }

}