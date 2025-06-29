<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\RegistrationProgramHkcw;
use App\Models\Paket;
use Illuminate\Http\Request;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {

         $userId = Auth::id(); // Ambil ID user yang sedang login

        // Ambil hanya data milik user yang sedang login
        $registrations = collect()
            ->merge(RegistrationHikariKidzClub::where('user_id', $userId)->get())
            ->merge(RegistrationHikariKidzDaycare::where('user_id', $userId)->get());
            //->merge(RegistrationHikariQuran::where('user_id', $userId)->get())
            //->merge(RegistrationProgramHkcw::where('user_id', $userId)->get());

        // Mengirim data ke view di folder riwayatpendaftaran
        return view('riwayatpendaftaran.riwayat', compact('registrations'));
    }

}
