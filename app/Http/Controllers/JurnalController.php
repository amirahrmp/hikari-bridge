<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentComponent;
use App\Models\Jurnal;
use App\Http\Requests\StoreJurnalRequest;
use App\Http\Requests\UpdateJurnalRequest;

class JurnalController extends Controller
{
    // Halaman Jurnal Umum
    public function jurnalumum()
    {
        return view('laporan.jurnalumum');
    }

    /**
     * Helper untuk mengambil kode akun dari COA berdasarkan nama akun.
     * Digunakan dalam proses jurnal umum.
     * @param string $namaAkun
     * @return string
     */
    private function getKodeAkunFromCOA(string $namaAkun): string
    {
        $akun = DB::table('coa')->where('nama_akun', $namaAkun)->first();
        // Fallback to '499' (Pendapatan Lain-lain) if account not found.
        // Pastikan kode '499' atau kode default lainnya ada di tabel 'coa' Anda.
        return $akun?->kode_akun ?? '499';
    }

    /**
     * Mengambil data jurnal umum berdasarkan periode.
     * Data diambil dari PaymentComponent yang terverifikasi.
     * @param string $periode Format YYYY-MM
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewdatajurnalumum($periode)
{
    try {
        $month = substr($periode, 5, 2);
        $year = substr($periode, 0, 4);

        // Ambil semua pembayaran yang sudah terverifikasi untuk periode ini
        $payments = \App\Models\Payment::with('components')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('status', 'terverifikasi')
            ->get();

        if ($payments->isEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => 'Tidak ada transaksi terverifikasi di periode ini.',
                'jurnal' => []
            ]);
        }

        $jurnal = [];

        foreach ($payments as $payment) {
            $total = $payment->components->sum('jumlah'); // Hitung total dari komponen
if ($total <= 0) continue;; // skip jika tidak ada komponen atau 0 semua

            $tanggal = date('Y-m-d', strtotime($payment->tanggal));
            $jrId = 'JR-' . sprintf('%04d', $payment->id);
            $registrationType = $payment->registration_type;

            // Kode akun
            $kodeKas = $this->getKodeAkunFromCOA('Kas');

            $namaPendapatan = match ($registrationType) {
                \App\Models\RegistrationHikariKidzDaycare::class => 'Pendapatan atas Daycare',
                \App\Models\RegistrationHikariKidzClub::class     => 'Pendapatan atas HKC',
                \App\Models\RegistrationHikariQuran::class        => 'Pendapatan atas HQ',
                default                                            => 'Pendapatan Lain-lain',
            };
            $kodePendapatan = $this->getKodeAkunFromCOA($namaPendapatan);

            // Entri Debet (Kas)
            $jurnal[] = [
                'id_transaksi' => $jrId,
                'tgl_jurnal'   => $tanggal,
                'nama_akun'    => 'Kas',
                'kode_akun'    => $kodeKas,
                'nominal'      => $total,
                'posisi_d_c'   => 'd',
            ];

            // Entri Kredit (Pendapatan)
            $jurnal[] = [
                'id_transaksi' => $jrId,
                'tgl_jurnal'   => $tanggal,
                'nama_akun'    => $namaPendapatan,
                'kode_akun'    => $kodePendapatan,
                'nominal'      => $total,
                'posisi_d_c'   => 'c',
            ];
        }

        // Urutkan berdasarkan tanggal dan ID transaksi
        usort($jurnal, function($a, $b) {
            return strtotime($a['tgl_jurnal']) <=> strtotime($b['tgl_jurnal']);
        });

        return response()->json([
            'status' => 200,
            'jurnal' => $jurnal
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}


    /**
     * Menampilkan halaman Buku Besar.
     * Mengirimkan daftar semua akun yang pernah tercatat di jurnal untuk dropdown filter.
     * @return \Illuminate\View\View
     */
    public function bukubesar()
    {
        // Memuat SEMUA akun yang pernah ada di jurnal (distinct) untuk dropdown filter
        $akun = Jurnal::viewakunbukubesar();
        return view('laporan.bukubesar', ['akun' => $akun]);
    }

    /**
     * Mengambil data Buku Besar berdasarkan periode dan kode akun yang dipilih.
     * @param string $periode Format YYYY-MM
     * @param string $kode_akun Kode akun yang dipilih
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewdatabukubesar($periode, $kode_akun)
    {
        try {
            $saldoawal = Jurnal::viewsaldobukubesar($periode, $kode_akun);
            $posisi = Jurnal::viewposisisaldonormalakun($kode_akun);
            $bukubesar = Jurnal::viewdatabukubesar($periode, $kode_akun);

            return response()->json([
                'status'     => 200,
                'bukubesar'  => $bukubesar,
                'saldoawal'  => $saldoawal,
                'posisi'     => $posisi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}