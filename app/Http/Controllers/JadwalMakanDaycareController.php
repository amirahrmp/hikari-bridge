<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalMakanDaycare;
use DateTime; // Pastikan DateTime di-use jika belum

class JadwalMakanDaycareController extends Controller
{
    /**
     * Tampilkan daftar jadwal (admin).
     */
    public function index()
    {
        $jadwal_makan_daycare = JadwalMakanDaycare::orderBy('bulan')
            ->orderBy('pekan')
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat')")
            ->get();

        return view('jadwal_makan_daycare.index', compact('jadwal_makan_daycare'));
    }

    /**
     * Simpan jadwal pekanan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bulan'              => 'required|integer|min:1|max:12',
            'pekan'              => 'required|integer|min:1|max:4',
            'data'               => 'required|array|min:1',
            'data.*.hari'        => 'required|string|max:50',
            'data.*.snack_pagi'  => 'nullable|string|max:255',
            'data.*.makan_siang' => 'nullable|string|max:255',
            'data.*.snack_sore'  => 'nullable|string|max:255',
            'data.*.libur'       => 'required|boolean', // Pastikan ini sebagai boolean (0 atau 1)
        ]);

        $bulan = $request->input('bulan');
        $pekan = $request->input('pekan');
        $dataHarian = $request->input('data');

        foreach ($dataHarian as $data) {
            $isLibur = $data['libur'];

            // Tentukan nilai snack/makan berdasarkan status libur
            $snackPagi = $isLibur ? 'LIBUR' : ($data['snack_pagi'] ?? null);
            $makanSiang = $isLibur ? 'LIBUR' : ($data['makan_siang'] ?? null);
            $snackSore = $isLibur ? 'LIBUR' : ($data['snack_sore'] ?? null);

            JadwalMakanDaycare::create([
                'bulan'       => $bulan,
                'pekan'       => $pekan,
                'hari'        => $data['hari'],
                'is_libur'    => $isLibur, // Simpan status libur ke database
                'snack_pagi'  => $snackPagi,
                'makan_siang' => $makanSiang,
                'snack_sore'  => $snackSore,
            ]);
        }

        return redirect()->route('jadwal_makan_daycare.index')
            ->with('message', 'Jadwal pekanan berhasil ditambahkan!');
    }

    /**
     * Update 1 baris jadwal (per-hari).
     */
    public function update(Request $request, JadwalMakanDaycare $jadwal_makan_daycare)
    {
        $request->validate([
            'hari'          => 'required|string|max:50',
            'snack_pagi'    => 'nullable|string|max:255',
            'makan_siang'   => 'nullable|string|max:255',
            'snack_sore'    => 'nullable|string|max:255',
            'is_libur'      => 'required|boolean', // Validasi untuk checkbox libur di modal edit
        ]);

        $isLibur = $request->input('is_libur');

        // Tentukan nilai snack/makan berdasarkan status libur dari form edit
        $snackPagi = $isLibur ? 'LIBUR' : ($request->input('snack_pagi') ?? null);
        $makanSiang = $isLibur ? 'LIBUR' : ($request->input('makan_siang') ?? null);
        $snackSore = $isLibur ? 'LIBUR' : ($request->input('snack_sore') ?? null);

        $jadwal_makan_daycare->update([
            'hari'          => $request->input('hari'),
            'is_libur'      => $isLibur, // Update status libur di database
            'snack_pagi'    => $snackPagi,
            'makan_siang'   => $makanSiang,
            'snack_sore'    => $snackSore,
        ]);

        return redirect()->route('jadwal_makan_daycare.index')
            ->with('message', 'Baris jadwal berhasil diubah!');
    }

    /**
     * Hapus 1 baris.
     */
    public function destroy($id)
    {
        JadwalMakanDaycare::findOrFail($id)->delete();
        return back()->with('message','Baris jadwal dihapus.');
    }

    /**
     * Hapus seluruh jadwal berdasarkan bulan & pekan.
     */
    public function deleteByPeriode(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'pekan' => 'required|integer',
        ]);

        JadwalMakanDaycare::where('bulan',$request->bulan)
            ->where('pekan',$request->pekan)
            ->delete();

        return back()->with('message','Seluruh jadwal pekan tersebut dihapus!');
    }

    /**
     * Tampilan user (read-only).
     */
    // app/Http/Controllers/JadwalMakanDaycareController.php
    public function userView()
    {
        // Ambil semua jadwal, urutkan agar rapih
        $jadwal = JadwalMakanDaycare::orderBy('bulan')
                ->orderBy('pekan')
                ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat')")
                ->get();

        // Kelompokkan: $grouped[bulan][pekan] = collection
        $grouped = $jadwal->groupBy(['bulan','pekan']);

        return view('jadwal_makan_daycare_user.index', [
            'jadwalGrouped' => $grouped   // ← kita kirim yg sudah dikelompokkan
        ]);
    }

}