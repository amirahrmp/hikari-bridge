<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarKursus;
use App\Models\Paket;    // Pastikan model Paket sudah diimport
use App\Models\PaketHkc; // Pastikan model PaketHkc sudah diimport

class DaftarKursusController extends Controller
{
    public function create()
    {
        // Ambil semua data paket Daycare dari tabel 'paket'
        $daycarePackages = Paket::all();

        // Ambil semua data paket Hikari Kidz Club dari tabel 'paket_hkc'
        $hkcPackages = PaketHkc::all();

        // Ambil semua data Daftar Kursus dari tabel 'daftar_kursus'
        $daftarKursus = DaftarKursus::all();

        // Teruskan semua koleksi data ke view daftar_kursus.index
        return view('daftar_kursus.index', compact('daycarePackages', 'hkcPackages', 'daftarKursus'));
    }

    public function store(Request $request)
    {
        // Metode store ini tetap sama, untuk menyimpan data kursus baru
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