<?php

namespace App\Http\Controllers;

use App\Models\JadwalHikariKidz;
use App\Models\PesertaHikariKidz;
use App\Models\HikariKidz;
use App\Models\Pengasuh;
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
    public function index()
    {
        $hikarikidzs = HikariKidz::all();
        $pengasuhs = Pengasuh::all();
        $jadwalHikariKidz = JadwalHikariKidz::with(['hikari_kidz','pengasuh','peserta'])->get();
        return view('jadwal_hikari_kidz.index', compact('jadwalHikariKidz','hikarikidzs', 'pengasuhs'));
    }

    public function show($id)
    {
        $jadwalHikariKidz = JadwalHikariKidz::with('peserta')->findOrFail($id);
        $pesertaList = PesertaHikariKidz::all();
        return view('jadwal_hikari_kidz.show', compact('jadwalHikariKidz', 'pesertaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hikari_kidz' => 'required|exists:hikari_kidz,id',
            'id_pengasuh' => 'required|exists:pengasuh,id',
            'hari' => 'required',
            'waktu' => 'required',
            'tipe_hikari_kidz' => 'required|in:online,offline',
        ]);

        JadwalHikariKidz::create($validated);

        $notification = array(
            'message' => 'Jadwal hikari kidz berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('jadwal_hikari_kidz.index')->with($notification);
    }

    public function addPeserta(Request $request, $id)
    {
        $validated = $request->validate([
            'id_anak' => 'required|exists:peserta_hikari_kidz,id',
        ]);

        $jadwalHikariKidz = JadwalHikariKidz::findOrFail($id);
        $pesertaExists = $jadwalHikariKidz->peserta()
                                   ->where('detail_jadwal_hikari_kidz.id_anak', $request->id_anak)
                                   ->exists();


        if ($pesertaExists) {
            $notification = array(
                'message' => 'Peserta sudah terdaftar di jadwal!',
                'alert-type' => 'error'
            );
            // Jika peserta sudah terdaftar, kirim pesan error
            return redirect()->route('jadwal_hikari_kidz.show', $id)
                            ->with($notification);
        }

        $jadwalHikariKidz->peserta()->attach($validated['id_anak']); // Menambahkan peserta ke jadwal kursus

        $notification = array(
            'message' => 'Peserta berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->route('jadwal_hikari_kidz.show', $id)->with($notification);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_hikari_kidz' => 'required|exists:hikari_kidz,id',
            'id_pengasuh' => 'required|exists:pengasuh,id',
            'hari' => 'required',
            'waktu' => 'required',
            'tipe_hikari_kidz' => 'required|in:online,offline',
        ]);

        $jadwalHikariKidz = JadwalHikariKidz::findOrFail($id);
        $jadwalHikariKidz->update($validated);

        $notification = array(
            'message' => 'Jadwal hikari kidz berhasil diperbarui!',
            'alert-type' => 'info'
        );
        return redirect()->route('jadwal_hikari_kidz.index')->with($notification);
    }

    public function destroy($id)
    {
        $jadwalHikariKidz = JadwalHikariKidz::findOrFail($id);
        $jadwalHikariKidz->delete();
        $notification = array(
            'message' => 'Jadwal hikari kidz berhasil dihapus!',
            'alert-type' => 'info'
        );
        return redirect()->route('jadwal_hikari_kidz.index')->with($notification);
    }

    public function removePeserta($jadwalId, $pesertaId)
    {
        $jadwalHikariKidz = JadwalHikariKidz::findOrFail($jadwalId);
        $jadwalHikariKidz->peserta()->detach($pesertaId);

        $notification = array(
            'message' => 'Peserta berhasil dihapus!',
            'alert-type' => 'info'
        );

        return redirect()->route('jadwal_hikari_kidz.show', $jadwalId)->with($notification);
    }

    // view untuk peserta kursus
    public function showByEmail(Request $request)
    {
        $email = $request->input('email');
        $peserta = PesertaHikariKidz::where('email', $email)->first();
        
        if (!$peserta) {
            return view('jadwal_hikari_kidz.peserta', [
                'message' => 'Anda belum memiliki jadwal hikari kidz. Ayo daftar sekarang!',
            ]);
        }

        $jadwalHikariKidz = $peserta->jadwalHikariKidz;

        return view('jadwal_hikari_kidz.peserta', compact('peserta', 'jadwalHikariKidz'));
    }

    // view untuk teacher
    public function showPengasuhSchedule(Request $request)
    {
        $email = $request->input('email');
        $pengasuh = Pengasuh::where('email', $email)->first();

        if (!$pengasuh) {
            return view('jadwal_hikari_kidz.pengasuh', ['message' => 'Data jadwal tidak ditemukan.']);
        }

        $jadwalHikariKidz = JadwalHikariKidz::where('id_pengasuh', $pengasuh->id)->get();

        return view('jadwal_hikari_kidz.teacher', [
            'jadwalHikarikidz' => $jadwalHikariKidz,
            'pengasuh' => $pengasuh,
            'message' => null,
        ]);
    }

}
