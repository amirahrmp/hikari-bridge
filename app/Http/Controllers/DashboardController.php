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
    $user_id = Session::get('loginId');

    // Jika customer (bukan admin/staf/guru)
    if (!in_array($role, ['admin', 'keuangan', 'staf', 'daycare', 'teacher'])) {
        // Jumlah program terdaftar: dari HKC + Daycare
        $programHkc = \App\Models\RegistrationHikariKidzClub::where('user_id', $user_id)->count();
        $programDaycare = \App\Models\RegistrationHikariKidzDaycare::where('user_id', $user_id)->count();
        $revenuetoday = $programHkc + $programDaycare;

        // Jumlah total tagihan dari komponen pembayaran (belum lunas)
        $transaction = \App\Models\Payment::where('user_id', $user_id)
            ->where('status', 'Belum Lunas')
            ->with('components')
            ->get()
            ->flatMap(function ($payment) {
                return $payment->components;
            })
            ->sum('jumlah');

        return view('dashboard2', compact('revenuetoday', 'transaction'));
    }

    // Jika bukan customer, redirect ke dashboard khusus
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