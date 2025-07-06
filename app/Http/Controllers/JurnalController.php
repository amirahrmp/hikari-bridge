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

            $data = PaymentComponent::with('payment')
                ->whereHas('payment', function ($query) use ($month, $year) {
                    $query->whereMonth('tanggal', $month)
                        ->whereYear('tanggal', $year)
                        ->where('status', 'terverifikasi'); // Hanya ambil pembayaran yang terverifikasi
                })
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200, // Mengembalikan 200 dengan data kosong jika tidak ada jurnal
                    'message' => 'Data jurnal tidak ditemukan untuk periode ini.',
                    'jurnal' => []
                ]);
            }

            $jurnal = [];
            // Mengelompokkan komponen pembayaran per pembayaran (payment_id)
            $grouped = $data->groupBy('payment_id');

            foreach ($grouped as $paymentId => $components) {
                $payment = $components->first()->payment;

                // Pastikan ada objek payment dan tanggalnya valid
                if (!$payment || !$payment->tanggal) {
                    continue;
                }

                $tanggal = date('Y-m-d', strtotime($payment->tanggal));
                // Menggunakan payment_id sebagai bagian dari ID transaksi jurnal agar unik
                $jrId = 'JR-' . sprintf('%04d', $paymentId);
                $total = $components->sum('jumlah');

                $registrationType = optional($payment)->registration_type;

                // Mendapatkan kode akun untuk Kas dan Pendapatan
                $kodeKas = $this->getKodeAkunFromCOA('Kas');
                $namaPendapatan = match ($registrationType) {
                    \App\Models\RegistrationHikariKidzDaycare::class => 'Pendapatan atas Daycare',
                    \App\Models\RegistrationHikariKidzClub::class     => 'Pendapatan atas HKC',
                    \App\Models\RegistrationHikariQuran::class        => 'Pendapatan atas HQ',
                    default                                           => 'Pendapatan Lain-lain',
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

            // Urutkan jurnal berdasarkan tanggal untuk tampilan yang rapi
            usort($jurnal, function($a, $b) {
                return strtotime($a['tgl_jurnal']) - strtotime($b['tgl_jurnal']);
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