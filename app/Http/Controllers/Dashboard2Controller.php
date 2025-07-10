<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\PesertaHikariKidz;

class Dashboard2Controller extends Controller
{
    public function index()
{
    $user = Auth::user();
    $role = session('role');

    // Jika admin/staf diarahkan ke dashboard utama (hindari looping)
    if (in_array($role, ['admin', 'keuangan', 'staf', 'teacher', 'daycare'])) {
        abort(403, 'Akses khusus pengguna biasa');
    }

    $idAnakHKC = \App\Models\RegistrationHikariKidzClub::where('user_id', $user->id)->pluck('id_anak');
    $idAnakHKD = \App\Models\RegistrationHikariKidzDaycare::where('user_id', $user->id)->pluck('id_anak');

    $allIdAnak = $idAnakHKC->merge($idAnakHKD)->unique();
    $totalPeserta = \App\Models\PesertaHikariKidz::whereIn('id_anak', $allIdAnak)->count();

    return view('dashboard2', compact('totalPeserta'));
}

}