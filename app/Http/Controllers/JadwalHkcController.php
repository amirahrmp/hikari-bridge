<?php

namespace App\Http\Controllers;

use App\Models\JadwalHkc;
use App\Models\TemaHkc;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalHkcController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalHkc::with('tema');

        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas', $request->kelas);
        }

        $jadwalhkc = $query->get();
        $temaList = TemaHkc::all();

        return view('jadwal_hkc.index', compact('jadwalhkc', 'temaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'hari' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'kegiatan' => 'required',
            'tema_id' => 'nullable|exists:tema_hkc,id'
        ]);

        $data = $request->all();

        // Jika tema_id kosong, isi otomatis berdasarkan bulan saat ini
        if (!$data['tema_id']) {
            $currentMonth = Carbon::now()->format('F');
            $tema = TemaHkc::where('bulan', $currentMonth)->first();
            if ($tema) {
                $data['tema_id'] = $tema->id;
            }
        }

        JadwalHkc::create($data);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalHkc::findOrFail($id);

        $request->validate([
            'kelas' => 'required',
            'hari' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'kegiatan' => 'required',
            'tema_id' => 'nullable|exists:tema_hkc,id'
        ]);

        $data = $request->all();

        // Tambahkan fallback tema jika kosong
        if (!$data['tema_id']) {
            $currentMonth = Carbon::now()->format('F');
            $tema = TemaHkc::where('bulan', $currentMonth)->first();
            if ($tema) {
                $data['tema_id'] = $tema->id;
            }
        }

        $jadwal->update($data);

        return redirect()->back()->with('success', 'Jadwal berhasil diubah.');
    }

    public function destroy($id)
    {
        JadwalHkc::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function userView()
    {
        $jadwalhkc = JadwalHkc::all();
        return view('jadwal_hkc_user.index', [
            'jadwal_hkc_user' => $jadwalhkc
        ]);
    }
}
