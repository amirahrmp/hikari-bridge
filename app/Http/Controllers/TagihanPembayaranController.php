<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran; // Biarkan ini jika masih ada modelnya, tapi pastikan tidak digunakan di logic
use App\Models\Payment;
use App\Models\PaketHkc; // Diperlukan untuk mengakses paket
use App\Models\Paket;    // Diperlukan untuk mengakses paket

class TagihanPembayaranController extends Controller
{
    /**
     * Menampilkan daftar tagihan berdasarkan user login.
     */
    public function index()
    {
        $userId = Auth::id(); // Dapatkan ID user yang sedang login

        // Ambil data pendaftaran hanya milik user ini
        $registrations = collect();
        $registrations = $registrations->merge(
            RegistrationHikariKidzClub::where('user_id', $userId)->get()
        );
        $registrations = $registrations->merge(
            RegistrationHikariKidzDaycare::where('user_id', $userId)->with('paket')->get()
        );
        // Hapus atau komen jika RegistrationHikariQuran tidak lagi digunakan
        // $registrations = $registrations->merge(
        //     RegistrationHikariQuran::where('user_id', $userId)->with('pakethq')->get()
        // );

        // Ambil semua pembayaran yang sudah terverifikasi untuk user ini
        // Eager load relationships needed for the 'program_display' and other accessors in Payment model
        $payments = Payment::where('user_id', $userId)
                        ->where('status', 'terverifikasi')
                        ->with(['sppBill', 'overtimeBill', 'mealBill', 'components'])
                        ->get();

        // Hitung total pembayaran yang sudah dibayar berdasarkan kombinasi registration_type dan registration_id
        // Gunakan FQCN saat mengelompokkan
        $paidAmounts = $payments->groupBy(function ($payment) {
            return $payment->registration_type . '-' . $payment->registration_id;
        })->map(function ($groupedPayments) {
            return $groupedPayments->sum('jumlah');
        });

        // Sekarang, kita perlu juga menyiapkan data untuk tampilan tabel di view
        $formattedRegistrations = [];

        foreach ($registrations as $registration) {
            $totalTagihan = 0;
            $programNameForDisplay = ''; // Nama program untuk tampilan
            $programNameForDb = ''; // Nama program (FQCN) untuk matching dengan payment.registration_type
            $paketName = '-'; // Untuk nama paket/kelas

            if ($registration instanceof \App\Models\RegistrationHikariKidzClub) {
                $programNameForDisplay = 'Hikari Kidz Club';
                $programNameForDb = \App\Models\RegistrationHikariKidzClub::class;
                
                $paket = \App\Models\PaketHkc::whereRaw('LOWER(TRIM(member)) = ?', [strtolower(trim($registration->member))])
                                             ->whereRaw('LOWER(TRIM(kelas)) = ?', [strtolower(trim($registration->kelas))])
                                             ->first();
                if ($paket) {
                    $paketName = optional($paket)->member . ' (' . optional($paket)->kelas . ')';
                    $totalTagihan = ($paket->u_pendaftaran ?? 0) + ($paket->u_perlengkapan ?? 0) + ($paket->u_sarana ?? 0) + ($paket->u_spp ?? 0);
                }

            } elseif ($registration instanceof \App\Models\RegistrationHikariKidzDaycare) {
                $programNameForDisplay = 'Hikari Kidz Daycare';
                $programNameForDb = \App\Models\RegistrationHikariKidzDaycare::class;

                $paket = $registration->paket;
                if ($paket) {
                    $paketName = optional($paket)->nama_paket ?? '-';
                    $totalTagihan = ($paket->u_pendaftaran ?? 0) + ($paket->u_pangkal ?? 0) + ($paket->u_kegiatan ?? 0) + ($paket->u_spp ?? 0) + ($paket->u_makan ?? 0);
                }
            }
            // Hapus atau komen jika RegistrationHikariQuran tidak lagi digunakan
            // elseif ($registration instanceof \App\Models\RegistrationHikariQuran) {
            //     $programNameForDisplay = 'Hikari Quran';
            //     $programNameForDb = \App\Models\RegistrationHikariQuran::class;
            //     $paket = $registration->pakethq;
            //     if ($paket) {
            //         $paketName = optional($paket)->nama_paket ?? $registration->kelas ?? '-';
            //         $totalTagihan = ($paket->u_pendaftaran ?? 0) + ($paket->u_modul ?? 0) + ($paket->u_spp ?? 0);
            //     }
            // }

            $paymentKey = $programNameForDb . '-' . $registration->id;
            $paidAmount = $paidAmounts[$paymentKey] ?? 0;
            $isFullyPaid = ($totalTagihan > 0 && $paidAmount >= $totalTagihan);

            $formattedRegistrations[] = (object) [
                'id' => $registration->id,
                'created_at' => $registration->created_at,
                'full_name' => $registration->full_name,
                'program_name_display' => $programNameForDisplay,
                'program_name_db' => $programNameForDb, // Penting untuk route
                'paket_name' => $paketName,
                'total_tagihan' => $totalTagihan,
                'paid_amount' => $paidAmount,
                'is_fully_paid' => $isFullyPaid,
            ];
        }

        // Sort the registrations by creation date (descending) so the latest appears first
        $formattedRegistrations = collect($formattedRegistrations)->sortByDesc('created_at')->values()->all();

        return view('tagihan_pembayaran.tagihan', compact('formattedRegistrations'));
    }
}