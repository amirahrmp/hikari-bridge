<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AbsensiDaycare;
use Carbon\Carbon;

class AbsensiDaycareController extends Controller
{
    // Menampilkan form jam datang
    public function createJamDatang()
    {
        $peserta = DB::table('peserta_hikari_kidz')
            ->where('tipe', 'HKD')
            ->select('id_anak', 'full_name')
            ->get();

        return view('absensi_daycare.jam_datang', compact('peserta'));
    }

    // Simpan jam datang
    public function storeJamDatang(Request $request)
    {
        $request->validate([
            'id_anak' => 'required',
            'jam_datang' => 'required',
        ]);

        AbsensiDaycare::create([
            'id_anak' => $request->id_anak,
            'jam_datang' => $request->jam_datang,
        ]);

        return redirect()->back()->with('success', 'Jam datang berhasil disimpan.');
    }

    // Menampilkan form jam pulang
    public function createJamPulang()
    {
        $peserta = DB::table('peserta_hikari_kidz')
            ->where('tipe', 'HKD')
            ->select('id_anak', 'full_name')
            ->get();

        return view('absensi_daycare.jam_pulang', compact('peserta'));
    }

    // Simpan jam pulang dan hitung overtime
    public function storeJamPulang(Request $request)
    {
        $request->validate([
            'id_anak' => 'required',
            'jam_pulang' => 'required',
        ]);

        // Cek apakah sudah ada jam datang hari ini
        $absensi = AbsensiDaycare::where('id_anak', $request->id_anak)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if (!$absensi) {
            return redirect()->back()->with('error', 'Jam datang belum diisi.');
        }

        $jamDatang = Carbon::parse($absensi->jam_datang);
        $jamPulang = Carbon::parse($request->jam_pulang);

        $durasiAktualMenit = $jamPulang->diffInMinutes($jamDatang);

        // Ambil package_type dari tabel registrasi_hikari_kidz_daycare
        $registrasi = DB::table('registration_hikari_kidz_daycares')
            ->where('id_anak', $request->id_anak)
            ->select('package_type')
            ->first();

        $namaPaket = strtolower($registrasi->package_type ?? 'full day'); // fallback jika tidak ada

        // Tentukan durasi berdasarkan package_type
        if (str_contains($namaPaket, 'full day') && str_contains($namaPaket, 'long')) {
            $durasiPaketMenit = 670; // 11 jam 10 menit
        } elseif (str_contains($namaPaket, 'full day')) {
            $durasiPaketMenit = 490; // 8 jam 10 menit
        } elseif (str_contains($namaPaket, 'half day')) {
            $durasiPaketMenit = 310; // 5 jam 10 menit
        } else {
            $durasiPaketMenit = 0;
        }

        // Hitung overtime
        $overtimeMenit = max(0, $durasiAktualMenit - $durasiPaketMenit);

        // Hitung denda (Rp10.000 per 30 menit)
        $denda = ceil($overtimeMenit / 30) * 10000;

        $absensi->update([
            'jam_pulang' => $request->jam_pulang,
            'overtime' => $overtimeMenit,
            'denda' => $overtimeMenit > 0 ? $denda : 0,
        ]);

        return redirect()->back()->with('success', 'Jam pulang & overtime berhasil disimpan.');
    }

    // Riwayat absensi
    public function riwayat_absensi()
    {
        $riwayat = DB::table('absensi_daycare as a')
            ->join('peserta_hikari_kidz as p', 'a.id_anak', '=', 'p.id_anak')
            ->select('a.*', 'p.full_name')
            ->orderByDesc('a.created_at')
            ->get();

        return view('absensi_daycare.riwayat_absensi', compact('riwayat'));
    }

    // Ambil nama anak untuk autofill
    public function getPackageTypeAnak($id)
    {
        $data = DB::table('peserta_hikari_kidz as p')
            ->leftJoin('registration_hikari_kidz_daycares as r', 'p.id_anak', '=', 'r.id_anak')
            ->where('p.id_anak', $id)
            ->where('p.tipe', 'HKD')
            ->select('p.full_name', 'r.package_type')
            ->first();

        return response()->json($data ?? ['full_name' => null, 'package_type' => null]);
    }
}
