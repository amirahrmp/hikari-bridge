<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;

class TagihanPembayaranController extends Controller
{
    public function index()
    {
        // Mengambil data pendaftaran dari ketiga model
        $registrations = collect();

$registrations = $registrations->merge(RegistrationHikariKidzClub::all());
$registrations = $registrations->merge(RegistrationHikariKidzDaycare::with('paket')->get());
$registrations = $registrations->merge(RegistrationHikariQuran::with('pakethq')->get());


        // Mengirim data ke view di folder riwayatpendaftaran
        return view('tagihan_pembayaran.tagihan', compact('registrations'));
    }

}
