<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\Payment;

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
        // $registrations = $registrations->merge(
        //     RegistrationHikariQuran::where('user_id', $userId)->with('pakethq')->get()
        // );

        // Ambil semua pembayaran yang sudah terverifikasi untuk user ini
        $verifiedPayments = Payment::where('status', 'terverifikasi')
            ->where('user_id', $userId)
            ->get();

        // Hitung total pembayaran yang sudah dibayar berdasarkan kombinasi registration_type dan registration_id
        $paidAmounts = $verifiedPayments->groupBy(function ($payment) {
            return $payment->registration_type . '-' . $payment->registration_id;
        })->map(function ($groupedPayments) {
            return $groupedPayments->sum('jumlah');
        });

        return view('tagihan_pembayaran.tagihan', compact('registrations', 'paidAmounts'));
    }
}
