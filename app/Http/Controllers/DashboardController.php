<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $role = Session::get('role');

    if (!in_array($role, ['admin', 'keuangan', 'staf', 'daycare', 'teacher'])) {
        abort(403, 'Akses hanya untuk admin/staf');
    }

    return view('dashboard', [
        'pesertaAktif' => \App\Models\PesertaKursus::whereHas('kursus', function ($query) {
            $query->where('status', 'Aktif');
        })->count(),
        'totalTeacher' => \App\Models\Teacher::count(),
        'stafTetap' => \App\Models\Staf::where('tipe_staf', 'Staf Tetap')->where('status', 'Aktif')->count(),
        'stafNonTetap' => \App\Models\Staf::where('tipe_staf', 'Staf Non Tetap')->where('status', 'Aktif')->count(),
        'stafDaycare' => \App\Models\Staf::where('tipe_staf', 'Staf Daycare')->where('status', 'Aktif')->count(),
    ]);
}
}