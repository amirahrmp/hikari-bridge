<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Teacher;
use App\Models\PresensiTeacher;
use App\Http\Requests\StorePresensiTeacherRequest;
use App\Http\Requests\UpdatePresensiTeacherRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiTeacherController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));

        $teachers = Teacher::all();

        $presensi = PresensiTeacher::where(function ($query) use ($date) {
                $query->whereDate('tgl_presensi', $date)
                    ->whereIn('keterangan', ['Hadir', 'Izin', 'Alfa'])  
                    ->orWhere(function ($query) use ($date) {
                        $query->whereIn('keterangan', ['Izin', 'Alfa'])
                            ->whereNull('waktu_masuk')  
                            ->whereDate('tgl_presensi', $date);  
                    });
            })
            ->get()
            ->keyBy('id_card');

        $totalHadir = $presensi->where('keterangan', 'Hadir')->count();
        $totalIzin = $presensi->where('keterangan', 'Izin')->count();
        $totalAlfa = $presensi->where('keterangan', 'Alfa')->count();

        return view('presensi_teacher.index', compact('teachers', 'date', 'presensi', 'totalHadir', 'totalIzin', 'totalAlfa'));
    }

    public function store(Request $request)
    {
        foreach ($request->input('teachers') as $id_card => $data) {
            if (!empty($data['keterangan'])) {
                $presensiData = [
                    'nama_teacher' => $data['nama_teacher'],
                    'keterangan' => $data['keterangan'],
                    'tgl_presensi' => $request->input('date'),
                ];

                if ($data['keterangan'] == 'Hadir') {
                    $presensiData['waktu_masuk'] = !empty($data['waktu_masuk']) ? $data['waktu_masuk'] : null;
                    $presensiData['waktu_keluar'] = !empty($data['waktu_keluar']) ? $data['waktu_keluar'] : null;
                }
                // Jika keterangan bukan 'Alfa' atau 'Izin', pastikan waktu_masuk dan waktu_keluar diisi
                elseif ($data['keterangan'] != 'Alfa' && $data['keterangan'] != 'Izin') {
                    if (!empty($data['waktu_masuk'])) {
                        $presensiData['waktu_masuk'] = $data['waktu_masuk'];
                    }
                    if (!empty($data['waktu_keluar'])) {
                        $presensiData['waktu_keluar'] = $data['waktu_keluar'];
                    }
                } else {
                    // Jika keterangan adalah 'Alfa' atau 'Izin', set waktu_masuk dan waktu_keluar menjadi null
                    $presensiData['waktu_masuk'] = null;
                    $presensiData['waktu_keluar'] = null;
                }

                // Cek jika data presensi sudah ada sebelumnya, jika ada update, jika tidak create baru
                $presensi = PresensiTeacher::where('id_card', $id_card)
                    ->whereDate('tgl_presensi', $request->input('date')) // Menggunakan tanggal hari ini
                    ->first();

                if ($presensi) {
                    // Update jika ditemukan
                    $presensi->update($presensiData);
                } else {
                    // Tambahkan data baru jika tidak ada
                    PresensiTeacher::create(array_merge(
                        ['id_card' => $id_card], // Pastikan id_card ditambahkan
                        $presensiData
                    ));
                }
            }
        }

        $notification = array(
            'message' => 'Update data presensi berhasil',
            'alert-type' => 'success'
        );

        return redirect()->route('presensi_teacher.index')->with($notification);
    }

    public function autoPresensi(Request $request)
    {
        // Validasi input yang diterima
        $request->validate([
            'id_card' => 'required|exists:teachers,id_card', // Pastikan ID card ada pada tabel Teacher
            'keterangan' => 'required|in:Hadir,Izin,Alfa',   // Pastikan keterangan valid
            'waktu_masuk' => 'nullable|date_format:H:i:s', // Format waktu masuk
            'waktu_keluar' => 'nullable|date_format:H:i:s', // Format waktu keluar
        ]);

        // Ambil data teacher berdasarkan id_card yang diterima
        $teacher = Teacher::where('id_card', $request->input('id_card'))->first();

        if (!$teacher) {
            return response()->json(['error' => 'Teacher tidak ditemukan'], 404);
        }

        // Siapkan data untuk disimpan atau diperbarui
        $presensiData = [
            'id_card' => $teacher->id_card,
            'nama_teacher' => $teacher->nama_teacher,
            'keterangan' => $request->input('keterangan'),
            'waktu_masuk' => ($request->input('keterangan') == 'Hadir') ? $request->input('waktu_masuk') : null,
            'waktu_keluar' => ($request->input('keterangan') == 'Hadir') ? $request->input('waktu_keluar') : null,
        ];

        // Cek apakah data presensi sudah ada untuk hari ini
        $presensi = PresensiTeacher::where('id_card', $teacher->id_card)
            ->whereDate('tgl_presensi', Carbon::now()->toDateString()) // Menggunakan tanggal hari ini
            ->first();

        if ($presensi) {
            // Update jika sudah ada
            $presensi->update($presensiData);
        } else {
            // Buat data baru jika belum ada
            PresensiTeacher::create($presensiData);
        }

        return response()->json(['message' => 'Presensi berhasil disimpan'], 200);
    }


    public function exportToPdf(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $teachers = Teacher::all(); 
        $presensi = PresensiTeacher::whereDate('tgl_presensi', $date)->get(); 

        $totalHadir = $presensi->where('keterangan', 'Hadir')->count();
        $totalIzin = $presensi->where('keterangan', 'Izin')->count();
        $totalAlfa = $presensi->where('keterangan', 'Alfa')->count();

        $pdf = PDF::loadView('presensi_teacher.pdf', compact('teachers', 'presensi', 'date', 'totalHadir', 'totalIzin', 'totalAlfa'));
        return $pdf->download('presensi_teacher_'.$date.'.pdf');
    }

    // riwayat presensi untuk teacher
    public function showRiwayatPresensi(Request $request)
    {
        // Ambil email dari request
        $email = $request->input('email');

        // Cari teacher berdasarkan email
        $teacher = Teacher::where('email', $email)->first();

        if (!$teacher) {
            return view('presensi_teacher.show', ['message' => 'Data presensi tidak ditemukan.']);
        }

        // Ambil semua riwayat presensi untuk teacher tersebut
        $query = PresensiTeacher::where('id_card', $teacher->id_card);

        // Filter berdasarkan tanggal jika ada
        if ($startDate = $request->input('startDate')) {
            $query->whereDate('tgl_presensi', '>=', $startDate);
        }

        if ($endDate = $request->input('endDate')) {
            $query->whereDate('tgl_presensi', '<=', $endDate);
        }

        $presensi = $query->get();

        // Hitung total kehadiran, izin, alfa
        $kehadiran = $presensi->where('keterangan', 'Hadir')->count();
        $izin = $presensi->where('keterangan', 'Izin')->count();
        $alfa = $presensi->where('keterangan', 'Alfa')->count();

        // Return view dengan data presensi, teacher, dan total kehadiran/izin/alfa
        return view('presensi_teacher.show', compact('presensi', 'teacher', 'kehadiran', 'izin', 'alfa'));
    }

}
