<?php

namespace App\Http\Controllers;

use App\Models\JadwalHikariKidz;
use App\Http\Requests\StoreJadwalHikariKidzRequest;
use App\Http\Requests\UpdateJadwalHikariKidzRequest;
use Illuminate\Http\Request;

class JadwalHikariKidzController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $query = JadwalHikariKidz::query();

    if ($request->has('tipe_daycare') && $request->tipe_daycare != '') {
        $query->where('tipe_daycare', $request->tipe_daycare);
    }

    $jadwalhikarikidz = $query->get();

        return view('jadwal_hikari_kidz.index', compact('jadwalhikarikidz'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_daycare' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'kegiatan' => 'required',
        ]);

        JadwalHikariKidz::create($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalHikariKidz::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil diubah.');
    }

    public function destroy($id)
    {
        JadwalHikariKidz::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }
}