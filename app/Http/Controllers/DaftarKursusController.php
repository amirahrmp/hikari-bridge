<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarKursus;

class DaftarKursusController extends Controller
{
    public function create()
    {
        $daftarkursus = DaftarKursus::all();
        return view('daftar_kursus.index', compact('daftarkursus'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_kursus' => 'required|integer',
            'nama_kursus' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'required|string|max:255',
        ]);

        DaftarKursus::create($validatedData);

        return redirect()->route('daftarkursus.index')->with('success', 'Data berhasil disimpan!');
    }
}
