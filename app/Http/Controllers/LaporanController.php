<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PotonganGaji;

class LaporanController extends Controller
{
    public function index()
    {
        $employees = Employee::get(['nama','id']);

        return view('laporan.index', compact('employees'));
    }

    public function store(Request $request)
    { 
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggal = $bulan.$tahun;
        $potongan_alpha = PotonganGaji::where('jenis_potongan', 'alpha')->get();
        $potongan_izin = PotonganGaji::where('jenis_potongan', 'izin')->get();
        $items = DB::select("
            SELECT employees.nik,employees.nama,jabatan.nama as nama_jabatan, jabatan.gaji_pokok,jabatan.transportasi,jabatan.uang_makan,absensi.alpha,absensi.izin
            FROM employees
            JOIN absensi ON absensi.employee_id = employees.id 
            JOIN jabatan ON jabatan.id = employees.jabatan_id
            WHERE absensi.bulan = $tanggal 
            AND absensi.employee_id = $request->karyawan_id
        ");

        return view('laporan.cetak-gaji', compact('bulan', 'tahun', 'items','potongan_alpha','potongan_izin'));
    }

    public function show()
    {
        return view('laporan.show');
    }

    public function cekGaji(Request $request)
    { 
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggal = $bulan.$tahun;
        $potongan_alpha = PotonganGaji::where('jenis_potongan', 'alpha')->get();
        $potongan_izin = PotonganGaji::where('jenis_potongan', 'izin')->get();
        $karyawan_id = auth()->id();
        $items = DB::select("
            SELECT employees.nik,employees.nama,jabatan.nama as nama_jabatan, jabatan.gaji_pokok,jabatan.transportasi,jabatan.uang_makan,absensi.alpha,absensi.izin
            FROM employees
            JOIN absensi ON absensi.employee_id = employees.id 
            JOIN jabatan ON jabatan.id = employees.jabatan_id
            WHERE absensi.bulan = $tanggal 
            AND absensi.employee_id = $karyawan_id
        ");

        return view('laporan.cetak-gaji-karyawan', compact('bulan', 'tahun', 'items','potongan_alpha','potongan_izin'));
    }
}
