<?php

namespace App\Http\Controllers;

use App\Models\JadwalHkc;
use App\Http\Requests\StoreJadwalHkcRequest;
use App\Http\Requests\UpdateJadwalHkcRequest;
use Illuminate\Http\Request;

class JadwalHkcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $query = JadwalHkc::query();

    if ($request->has('kelas') && $request->kelas != '') {
        $query->where('kelas', $request->kelas);
    }

    $jadwalhkc = $query->get();

        return view('jadwal_hkc.index', compact('jadwalhkc'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'kegiatan' => 'required',
        ]);

        JadwalHkc::create($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalHkc::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil diubah.');
    }

    public function destroy($id)
    {
        JadwalHkc::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }
}