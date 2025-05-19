<?php

namespace App\Http\Controllers;

use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\RegistrationProgramHkcw;
use Illuminate\Http\Request;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        // Mengambil data pendaftaran dari ketiga model
        $registrations = collect(); // Koleksi kosong

        $registrations = $registrations->merge(RegistrationHikariKidzClub::all());
        $registrations = $registrations->merge(RegistrationHikariKidzDaycare::all());
        $registrations = $registrations->merge(RegistrationHikariQuran::all());
        $registrations = $registrations->merge(RegistrationProgramHkcw::all());

        // Mengirim data ke view di folder riwayatpendaftaran
        return view('riwayatpendaftaran.riwayat', compact('registrations'));
    }

}
