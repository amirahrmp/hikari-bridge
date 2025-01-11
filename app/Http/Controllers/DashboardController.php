<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Teacher;
use App\Models\PesertaKursus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung jumlah peserta aktif
        $pesertaAktif = PesertaKursus::whereHas('kursus', function ($query) {
            $query->where('status', 'Aktif');
        })->count();

        // Menghitung jumlah tenaga pengajar
        $totalTeacher = Teacher::count(); // Menghitung total tenaga pengajar

        // Data staf
        $stafTetap = Staf::where('tipe_staf', 'Staf Tetap')->where('status', 'Aktif')->count();
        $stafNonTetap = Staf::where('tipe_staf', 'Staf Non Tetap')->where('status', 'Aktif')->count();
        $stafDaycare = Staf::where('tipe_staf', 'Staf Daycare')->where('status', 'Aktif')->count();

        // Menentukan tampilan berdasarkan role
        $role = Session::get('role');
        $viewName = in_array($role, ['admin', 'keuangan', 'staf', 'daycare']) ? 'dashboard' : 'dashboard2';

        // Mengirim data ke view
        return view($viewName, compact(
            'pesertaAktif',
            'totalTeacher', // Mengirim total tenaga pengajar
            'stafTetap',
            'stafNonTetap',
            'stafDaycare',
        ));
    }
}