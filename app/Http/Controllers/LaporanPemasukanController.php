<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment; // Pastikan ini mengacu ke model Payment Anda
use App\Models\LaporanPemasukan; // Pastikan ini mengacu ke model LaporanPemasukan Anda
use Carbon\Carbon;

class LaporanPemasukanController extends Controller
{
    private function getFilteredPayments(Request $request)
    {
        // Gunakan model LaporanPemasukan yang sudah dimodifikasi dengan accessors
        // Pastikan eager loading 'components' tetap ada
        $query = LaporanPemasukan::with(['components', 'sppBulanan', 'overtimeBill', 'mealBill'])
            ->where('status', 'terverifikasi')
            ->whereHas('components'); // Hanya jika Anda ingin memastikan ada komponen pembayaran

        if ($request->filled('program')) {
            // Filter berdasarkan nama program yang user-friendly
            // Ini mungkin memerlukan penyesuaian jika filter 'program' di form
            // masih mengirimkan FQCN. Jika form mengirimkan string seperti
            // "Hikari Kidz Daycare", maka ini akan bekerja.
            // Jika form mengirimkan FQCN, maka Anda harus mengubah nilai option di blade.
            switch ($request->program) {
                case 'Hikari Kidz Daycare':
                    $query->where('registration_type', \App\Models\RegistrationHikariKidzDaycare::class);
                    break;
                case 'Hikari Kidz Club':
                    $query->where('registration_type', \App\Models\RegistrationHikariKidzClub::class);
                    break;
                // Tambahkan case untuk Hikari Quran jika ada
                // case 'Hikari Quran':
                //     $query->where('registration_type', \App\Models\RegistrationHikariQuran::class);
                //     break;
                default:
                    // Jika 'Semua Program' atau nilai tidak dikenal, tidak perlu filter
                    break;
            }
        }

        if ($request->filled('bulan')) {
            $bulan = Carbon::parse($request->bulan);
            $query->whereMonth('created_at', $bulan->month)
                  ->whereYear('created_at', $bulan->year);
        }

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        return $query->orderBy('created_at')->get();
    }

    private function transformLaporan($payments)
    {
        $transformedData = collect();

        foreach ($payments as $payment) {
            $transformedData->push([
                'tanggal' => $payment->created_at->format('d-m-Y'),
                'nama' => $payment->peserta->full_name ?? '-', // Menggunakan accessor peserta
                'program' => $payment->program_display_name, // Menggunakan accessor baru
                'paket' => $payment->nama_paket, // Menggunakan accessor baru
                'keterangan' => collect($payment->components)->map(function ($c) {
                    return '- ' . $c->komponen . ' : Rp ' . number_format($c->jumlah, 0, ',', '.');
                })->implode("\n"),
                'total' => $payment->components->sum('jumlah'),
            ]);
        }

        return $transformedData;
    }

    public function index(Request $request)
    {
        $payments = $this->getFilteredPayments($request);
        $laporan = $this->transformLaporan($payments);

        return view('laporan_pemasukan.index', compact('laporan'));
    }

    public function printReport(Request $request)
    {
        $payments = $this->getFilteredPayments($request);
        $laporan = $this->transformLaporan($payments);

        return view('laporan_pemasukan.print', compact('laporan'));
    }
}
