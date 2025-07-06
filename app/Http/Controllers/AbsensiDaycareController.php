<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AbsensiDaycare;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PDF; // Pastikan ini diimpor

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

    // Mengecek apakah jam datang sudah diisi hari ini untuk anak tertentu
    public function cekJamDatang($id)
    {
        $jamDatang = AbsensiDaycare::where('id_anak', $id)
            ->whereDate('created_at', Carbon::today())
            ->value('jam_datang');

        return response()->json([
            'jam_datang_terisi' => !is_null($jamDatang)
        ]);
    }


    // Simpan jam pulang dan hitung overtime
    public function storeJamPulang(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_anak' => 'required',
            'jam_pulang' => 'required|date_format:H:i',
        ]);

        // Ambil absensi hari ini berdasarkan anak
        $absensi = AbsensiDaycare::where('id_anak', $request->id_anak)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();

        // Jika belum ada jam datang
        if (!$absensi) {
            return redirect()->back()->with('error', 'Jam datang belum diisi.');
        }

        // Jika jam pulang sudah diisi sebelumnya
        if ($absensi->jam_pulang !== null) {
            return redirect()->back()->with('error', 'Jam pulang sudah diisi sebelumnya.');
        }

        // Ambil waktu datang dan waktu pulang
        $jamDatang = Carbon::parse($absensi->jam_datang);
        $jamPulang = Carbon::parse($request->jam_pulang);

        // Ambil jenis paket dari registrasi daycare
        $registrasi = DB::table('registration_hikari_kidz_daycares')
            ->where('id_anak', $request->id_anak)
            ->select('package_type')
            ->first();

        // Mapping nama paket menjadi lowercase tanpa spasi
        $namaPaket = Str::of($registrasi->package_type ?? 'fullday')->lower()->replace(' ', '');

        // Durasi maksimum berdasarkan jenis paket
        switch ($namaPaket) {
            case 'halfday':
                $durasiPaketMenit = 310; // 08.00–13.10
                break;
            case 'fulldaylong':
                $durasiPaketMenit = 670; // 08.00–19.10
                break;
            case 'fullday':
            default:
                $durasiPaketMenit = 490; // 08.00–16.10
                break;
        }

        // Hitung batas waktu pulang
        $batasPulang = $jamDatang->copy()->addMinutes($durasiPaketMenit);

        // Hitung overtime
        $overtimeMenit = $jamPulang->greaterThan($batasPulang)
            ? $jamPulang->diffInMinutes($batasPulang)
            : 0;

        // Hitung denda (per 30 menit, Rp10.000)
        $denda = $overtimeMenit > 0
            ? ceil($overtimeMenit / 30) * 10000
            : 0;

        // Update absensi dengan jam pulang, overtime, dan denda
        $absensi->update([
            'jam_pulang' => $request->jam_pulang,
            'overtime' => $overtimeMenit,
            'denda' => $denda,
        ]);

        return redirect()->back()->with('success', 'Jam pulang berhasil disimpan.');
    }

    // Riwayat absensi dengan informasi durasi dan overtime
    public function riwayat_absensi(Request $request)
    {
        $tanggal = $request->input('tanggal'); // format: YYYY-MM-DD
        $bulan = $request->input('bulan');     // format: YYYY-MM

        $query = DB::table('absensi_daycare as a')
            ->join('peserta_hikari_kidz as p', 'a.id_anak', '=', 'p.id_anak')
            ->leftJoin('registration_hikari_kidz_daycares as r', 'a.id_anak', '=', 'r.id_anak')
            ->select('a.*', 'p.full_name', 'r.package_type');

        if ($tanggal) {
            $query->whereDate('a.created_at', $tanggal);
        } elseif ($bulan) {
            $query->whereYear('a.created_at', Carbon::parse($bulan)->year)
                ->whereMonth('a.created_at', Carbon::parse($bulan)->month);
        } else {
            // Default: hanya hari ini
            $query->whereDate('a.created_at', Carbon::today());
        }

        $riwayat = $query->orderByDesc('a.created_at')->get();

        // Tambahan: hitung durasi & overtime
        foreach ($riwayat as $item) {
            $jamDatang = $item->jam_datang ? Carbon::parse($item->jam_datang) : null;
            $jamPulang = $item->jam_pulang ? Carbon::parse($item->jam_pulang) : null;

            $paket = strtolower(str_replace([' ', '-'], '', $item->package_type ?? 'fullday'));

            switch ($paket) {
                case 'halfday':
                    $maksMenit = 310;
                    break;
                case 'fullday':
                    $maksMenit = 490;
                    break;
                case 'fulldaylong':
                    $maksMenit = 670;
                    break;
                default:
                    $maksMenit = 490;
            }

            if ($jamDatang && $jamPulang) {
                $durasiMenit = $jamPulang->diffInMinutes($jamDatang);
                $item->durasi_hadir = floor($durasiMenit / 60) . ' jam ' . ($durasiMenit % 60) . ' menit';

                $overtimeMenit = max(0, $durasiMenit - $maksMenit);
                $item->overtime_display = $overtimeMenit > 0
                    ? floor($overtimeMenit / 60) . ' jam ' . ($overtimeMenit % 60) . ' menit'
                    : '-';
            } else {
                $item->durasi_hadir = '-';
                $item->overtime_display = '-';
            }
        }

        return view('absensi_daycare.riwayat_absensi', compact('riwayat', 'tanggal', 'bulan'));
    }

    // NEW: Metode untuk mencetak PDF riwayat absensi Daycare
    public function exportPdf(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $bulan = $request->input('bulan');

        $query = DB::table('absensi_daycare as a')
            ->join('peserta_hikari_kidz as p', 'a.id_anak', '=', 'p.id_anak')
            ->leftJoin('registration_hikari_kidz_daycares as r', 'a.id_anak', '=', 'r.id_anak')
            ->select('a.*', 'p.full_name', 'r.package_type');

        $reportTitle = "Laporan Absensi Hikari Kidz Daycare";
        $reportPeriod = "";

        if (!empty($tanggal)) {
            $query->whereDate('a.created_at', $tanggal);
            $reportPeriod = "Tanggal: " . Carbon::parse($tanggal)->format('d-m-Y');
        } elseif (!empty($bulan)) {
            $query->whereYear('a.created_at', Carbon::parse($bulan)->year)
                ->whereMonth('a.created_at', Carbon::parse($bulan)->month);
            $reportPeriod = "Bulan: " . Carbon::parse($bulan)->isoFormat('MMMM YYYY', 'id');
        } else {
            // Default to today if no date or month is specified
            $defaultDate = Carbon::now()->format('Y-m-d');
            $query->whereDate('a.created_at', $defaultDate);
            $reportPeriod = "Tanggal: " . Carbon::parse($defaultDate)->format('d-m-Y');
        }

        $absensiRecords = $query->orderByDesc('a.created_at')->get();

        // Recalculate duration and overtime for PDF as well
        foreach ($absensiRecords as $item) {
            $jamDatang = $item->jam_datang ? Carbon::parse($item->jam_datang) : null;
            $jamPulang = $item->jam_pulang ? Carbon::parse($item->jam_pulang) : null;

            $paket = Str::of($item->package_type ?? 'fullday')->lower()->replace(' ', '');

            switch ($paket) {
                case 'halfday':
                    $maksMenit = 310;
                    break;
                case 'fullday':
                    $maksMenit = 490;
                    break;
                case 'fulldaylong':
                    $maksMenit = 670;
                    break;
                default:
                    $maksMenit = 490;
            }

            if ($jamDatang && $jamPulang) {
                $durasiMenit = $jamPulang->diffInMinutes($jamDatang);
                $item->durasi_hadir = floor($durasiMenit / 60) . ' jam ' . ($durasiMenit % 60) . ' menit';

                $overtimeMenit = max(0, $durasiMenit - $maksMenit);
                $item->overtime_display = $overtimeMenit > 0
                    ? floor($overtimeMenit / 60) . ' jam ' . ($overtimeMenit % 60) . ' menit'
                    : '-';
            } else {
                $item->durasi_hadir = '-';
                $item->overtime_display = '-';
            }
        }

        $pdf = PDF::loadView('absensi_daycare.pdf', compact('absensiRecords', 'reportPeriod', 'reportTitle'));

        // Define filename based on the filter used
        $filename = 'absensi_daycare_';
        if (!empty($tanggal)) {
            $filename .= $tanggal;
        } elseif (!empty($bulan)) {
            $filename .= Carbon::parse($bulan)->format('Y-m');
        } else {
            $filename .= Carbon::now()->format('Y-m-d');
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
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