<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AbsensiHkc;
use Carbon\Carbon;
use PDF;

class AbsensiHkcController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));

        $peserta = DB::table('peserta_hikari_kidz as p')
            ->leftJoin('registration_hikari_kidz_clubs as r', 'p.id_anak', '=', 'r.id_anak')
            ->where('p.status', 'Terverifikasi')
            ->where('p.tipe', 'HKC')
            ->select(
                'p.id_anak',
                'p.full_name',
                DB::raw("'HKC' as program"),
                'r.member',
                'r.kelas'
            )
            ->orderByRaw('CAST(p.id_anak AS UNSIGNED) ASC')
            ->get()
            ->map(function ($item) {
                $paket = DB::table('paket_hkc')
                    ->where('member', $item->member)
                    ->where('kelas', $item->kelas)
                    ->first();

                $item->nama_paket = $paket ? $paket->tipe : '-';
                return $item;
            });

        $absensiHarian = AbsensiHkc::whereDate('created_at', $date)->get()->keyBy('id_anak');

        $totalHadir = $absensiHarian->where('keterangan', 'Hadir')->count();
        $totalIzin = $absensiHarian->where('keterangan', 'Izin')->count();
        $totalAlfa = $absensiHarian->where('keterangan', 'Alfa')->count();

        return view('absensi_hkc.index', compact(
            'peserta',
            'absensiHarian',
            'date',
            'totalHadir',
            'totalIzin',
            'totalAlfa'
        ));
    }

    public function store(Request $request)
    {
        $tanggal = Carbon::parse($request->input('date'))->startOfDay();

        foreach ($request->input('peserta') as $id_anak => $data) {
            if (!empty($data['keterangan'])) {
                $absensiData = [
                    'id_anak' => $id_anak,
                    'nama_anak' => $data['nama_anak'],
                    'program' => $data['program'],
                    'nama_paket' => $data['nama_paket'],
                    'keterangan' => $data['keterangan'],
                ];

                $existing = AbsensiHkc::where('id_anak', $id_anak)
                    ->whereDate('created_at', $tanggal)
                    ->first();

                if ($existing) {
                    $existing->update($absensiData);
                } else {
                    AbsensiHkc::create($absensiData);
                }
            }
        }

        return redirect()->route('absensi_hkc.index', ['date' => $tanggal->format('Y-m-d')])->with([
            'message' => 'Absensi berhasil disimpan!',
            'alert-type' => 'success',
        ]);
    }

    public function history(Request $request)
    {
        $historyDate = $request->input('history_date');
        $historyMonth = $request->input('history_month');

        $historicalAbsensi = AbsensiHkc::query();

        // Determine default if no filters are applied
        if (empty($historyDate) && empty($historyMonth)) {
            $historyDate = Carbon::now()->format('Y-m-d'); // Default to today
        }

        if (!empty($historyDate)) {
            // If a specific date is selected, prioritize it
            $historicalAbsensi->whereDate('created_at', $historyDate);
            $historyMonth = null; // Ensure month is null if date is set
        } elseif (!empty($historyMonth)) {
            // If a month is selected, filter by month and year
            $historicalAbsensi->whereYear('created_at', Carbon::parse($historyMonth)->year)
                              ->whereMonth('created_at', Carbon::parse($historyMonth)->month);
            $historyDate = null; // Ensure date is null if month is set
        }

        $historicalAbsensi = $historicalAbsensi->orderBy('created_at', 'desc')
                                                ->orderBy('nama_anak')
                                                ->get();

        return view('absensi_hkc.riwayat', compact(
            'historyDate',
            'historyMonth',
            'historicalAbsensi'
        ));
    }

    public function edit(AbsensiHkc $absensi)
    {
        return view('absensi_hkc.edit', compact('absensi'));
    }

    public function update(Request $request, AbsensiHkc $absensi)
    {
        $request->validate([
            'keterangan' => 'required|in:Hadir,Izin,Alfa',
        ]);

        $absensi->update([
            'keterangan' => $request->input('keterangan'),
        ]);

        return redirect()->route('absensi_hkc.riwayat', [
            'history_date' => Carbon::parse($absensi->created_at)->format('Y-m-d')
        ])->with([
            'message' => 'Absensi berhasil diperbarui!',
            'alert-type' => 'success',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $historyDate = $request->input('history_date');
        $historyMonth = $request->input('history_month');

        $absensiRecordsQuery = AbsensiHkc::query();
        $reportTitle = "Laporan Absensi Hikari Kidz Club";
        $reportPeriod = "";

        if (!empty($historyDate)) {
            $absensiRecordsQuery->whereDate('created_at', $historyDate);
            $reportPeriod = "Tanggal: " . Carbon::parse($historyDate)->format('d-m-Y');
        } elseif (!empty($historyMonth)) {
            $absensiRecordsQuery->whereYear('created_at', Carbon::parse($historyMonth)->year)
                                ->whereMonth('created_at', Carbon::parse($historyMonth)->month);
            $reportPeriod = "Bulan: " . Carbon::parse($historyMonth)->isoFormat('MMMM YYYY', 'id');
        } else {
            // Default to today if no date or month is specified (e.g., direct PDF link without filters)
            $defaultDate = Carbon::now()->format('Y-m-d');
            $absensiRecordsQuery->whereDate('created_at', $defaultDate);
            $reportPeriod = "Tanggal: " . Carbon::parse($defaultDate)->format('d-m-Y');
        }

        $absensiRecords = $absensiRecordsQuery->orderBy('created_at')
                                             ->orderBy('nama_anak')
                                             ->get();

        $pdf = PDF::loadView('absensi_hkc.pdf', compact('absensiRecords', 'reportPeriod', 'reportTitle'));
        
        // Define filename based on the filter used
        $filename = 'absensi_hkc_';
        if (!empty($historyDate)) {
            $filename .= $historyDate;
        } elseif (!empty($historyMonth)) {
            $filename .= Carbon::parse($historyMonth)->format('Y-m');
        } else {
            $filename .= Carbon::now()->format('Y-m-d');
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }
}