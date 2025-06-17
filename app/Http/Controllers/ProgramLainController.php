<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramLain;
use App\Models\KegiatanTambahan;
use App\Models\PesertaHikariKidz;

class ProgramLainController extends Controller
{
    public function index()
    {
        $kegiatan = ProgramLain::all();
        $anak = PesertaHikariKidz::all();
        $pendaftaran = KegiatanTambahan::with(['program', 'anak'])->get();

        return view('program_lain.index', compact('kegiatan', 'anak', 'pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required',
            'id_anak' => 'required',
            'status_pembayaran' => 'required'
        ]);

        KegiatanTambahan::create($request->all());
        return redirect()->back()->with('success', 'Pendaftaran berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $data = KegiatanTambahan::findOrFail($id);
        $data->update($request->all());

        return redirect()->back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        KegiatanTambahan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}

