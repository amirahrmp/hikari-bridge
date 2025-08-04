<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalMakanDaycare;
use App\Models\RegistrationHikariKidzDaycare;

class JadwalMakanDaycareController extends Controller
{
    public function index()
    {
        $bulanOrder = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $jadwalMakan = JadwalMakanDaycare::all()->sort(function ($a, $b) use ($bulanOrder) {
            if ($a->bulan == $b->bulan) {
                return $a->pekan <=> $b->pekan;
            }

            return array_search($a->bulan, $bulanOrder) <=> array_search($b->bulan, $bulanOrder);
        });

        return view('jadwal_makan_daycare.index', compact('jadwalMakan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|string',
            'pekan' => 'required|integer',
            'is_libur' => 'required|boolean',
            'hari' => 'required|string',
            'snack_pagi' => 'nullable|string|max:255',
            'makan_siang' => 'nullable|string|max:255',
            'snack_sore' => 'nullable|string|max:255',
        ]);

        JadwalMakanDaycare::create($validated);

        return redirect()->route('jadwal_makan_daycare.index')->with('success', 'Jadwal makan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bulan' => 'required|string',
            'pekan' => 'required|integer',
            'is_libur' => 'required|boolean',
            'hari' => 'required|string',
            'snack_pagi' => 'nullable|string|max:255',
            'makan_siang' => 'nullable|string|max:255',
            'snack_sore' => 'nullable|string|max:255',
        ]);

        $jadwal = JadwalMakanDaycare::findOrFail($id);
        $jadwal->update($validated);

        return redirect()->route('jadwal_makan_daycare.index')->with('success', 'Jadwal makan berhasil diperbarui');
    }

    public function destroy($id)
    {
        JadwalMakanDaycare::findOrFail($id)->delete();

        return redirect()->route('jadwal_makan_daycare.index')->with('success', 'Jadwal makan berhasil dihapus');
    }

    public function userview()
    {
        $userId = Auth::id();

        $sudahDaftar = RegistrationHikariKidzDaycare::where('user_id', $userId)->exists();

        if (!$sudahDaftar) {
            return view('jadwal_makan_daycare_user.index', [
                'belumDaftar' => true,
                'jadwalGrouped' => collect()
            ]);
        }

        $jadwal = JadwalMakanDaycare::orderBy('bulan')
            ->orderBy('pekan')
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat')")
            ->get();

        $grouped = $jadwal->groupBy(['bulan', 'pekan']);

        return view('jadwal_makan_daycare_user.index', [
            'jadwalGrouped' => $grouped,
            'belumDaftar' => false
        ]);
    }
}
