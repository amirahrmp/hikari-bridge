<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\RegistrationProgramHkcw;
use App\Models\Paket;
use Illuminate\Http\Request;

class RiwayatPendaftaranHkcController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    // Ambil ID anak yang sudah diverifikasi oleh admin
    $idAnakTerverifikasi = \App\Models\PesertaHikariKidz::where('status', 'Terverifikasi')
        ->pluck('id_anak')
        ->toArray();

    // Ambil hanya pendaftar dari user yang login dan anaknya sudah diverifikasi
    $registrations = collect()
        ->merge(
            RegistrationHikariKidzClub::where('user_id', $userId)
                ->whereIn('id_anak', $idAnakTerverifikasi)
                ->get()
        )
        ->merge(
            RegistrationHikariKidzDaycare::where('user_id', $userId)
                ->whereIn('id_anak', $idAnakTerverifikasi)
                ->get()
        );

    return view('riwayatpendaftaranhkc.riwayat', compact('registrations'));
}


}
