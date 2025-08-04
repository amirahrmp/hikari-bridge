<?php

namespace App\Http\Controllers;

use App\Models\JadwalHikariKidz;
use App\Models\RegistrationHikariKidzDaycare;
use Illuminate\Support\Facades\Auth;
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

    public function userView()
{
    $userId = Auth::id();

    // Cek apakah user sudah mendaftar Daycare
    $sudahDaftar = RegistrationHikariKidzDaycare::where('user_id', $userId)->exists();

    if (!$sudahDaftar) {
        return view('jadwal_hikari_kidz_user.index', [
            'belumDaftar' => true,
            'jadwal_hikari_kidz_user' => collect()
        ]);
    }

    // Ambil jadwal yang sesuai dengan tipe_daycare user
    $registration = RegistrationHikariKidzDaycare::where('user_id', $userId)->first();
    $tipeDaycare = $registration->tipe_daycare;

    $jadwalhikarikidz = JadwalHikariKidz::where('tipe_daycare', $tipeDaycare)->get();

    return view('jadwal_hikari_kidz_user.index', [
        'belumDaftar' => false,
        'jadwal_hikari_kidz_user' => $jadwalhikarikidz
    ]);
}
}