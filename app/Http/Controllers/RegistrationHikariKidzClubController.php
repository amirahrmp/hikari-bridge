<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzClub;

class RegistrationHikariKidzClubController extends Controller
{
    public function create()
    {
        return view('registerkidzclub.create');
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
            'member' => 'required|in:tetap,harian',
            'kelas' => 'required|in:himawari,sakura,bara',
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

        // Jika informasi sumber adalah 'other', ganti information_source dengan isi dari information_source_other
      // Setelah kamu override information_source:
if ($request->input('information_source') === 'other') {
    $validatedData['information_source'] = $request->input('information_source_other');
}

// Buang information_source_other agar tidak ikut di-insert
unset($validatedData['information_source_other']);
        // Simpan data ke database
        RegistrationHikariKidzClub::create($validatedData);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('registerkidzclub.create')->with('success', 'Data berhasil disimpan!');
    }
}
