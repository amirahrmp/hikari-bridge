<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\PresensiStaf;
use App\Models\Staf;
use App\Http\Requests\StorePresensiStafRequest;
use App\Http\Requests\UpdatePresensiStafRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiStafController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));

        $stafs = Staf::all();

        $presensi = PresensiStaf::where(function ($query) use ($date) {
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

        // Menghitung total untuk setiap keterangan
        $totalHadir = $presensi->where('keterangan', 'Hadir')->count();
        $totalIzin = $presensi->where('keterangan', 'Izin')->count();
        $totalAlfa = $presensi->where('keterangan', 'Alfa')->count();

        return view('presensi_staf.index', compact('stafs', 'date', 'presensi', 'totalHadir', 'totalIzin', 'totalAlfa'));
    }

    public function store(Request $request)
    {
        // Proses untuk mencatat atau memperbarui presensi berdasarkan data yang dipilih
        foreach ($request->input('stafs') as $id_card => $data) {
            // Pastikan keterangan ada
            if (!empty($data['keterangan'])) {
                // Siapkan data yang akan disimpan
                $presensiData = [
                    'nama_staf' => $data['nama_staf'],
                    'keterangan' => $data['keterangan'],
                    'tgl_presensi' => $request->input('date'),
                ];

                // Jika keterangan adalah 'Hadir', waktu masuk dan keluar bisa kosong
                if ($data['keterangan'] == 'Hadir') {
                    // Biarkan waktu masuk dan keluar bisa kosong (null)
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
                $presensi = PresensiStaf::where('id_card', $id_card)
                    ->whereDate('tgl_presensi', $request->input('date')) // Menggunakan tanggal hari ini
                    ->first();

                if ($presensi) {
                    // Update jika ditemukan
                    $presensi->update($presensiData);
                } else {
                    // Tambahkan data baru jika tidak ada
                    PresensiStaf::create(array_merge(
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

        return redirect()->route('presensi_staf.index')->with($notification);
    }

    public function autoPresensi(Request $request)
    {
        // Mengambil data RFID
        $id_card = $request->input('id_card');
        $keterangan = $request->input('keterangan'); // Bisa 'Hadir', 'Izin', 'Alfa'
        
        // Cek apakah id_card ada
        $staf = Staf::where('id_card', $id_card)->first();
        if (!$staf) {
            return response()->json(['error' => 'Staf tidak ditemukan'], 404);
        }

        $date = Carbon::now()->toDateString(); 

        // Siapkan data presensi
        $presensiData = [
            'id_card' => $id_card,
            'nama_staf' => $staf->nama_staf,
            'keterangan' => $keterangan,
            'tgl_presensi' => $date, 
            'waktu_masuk' => ($keterangan == 'Hadir' && !empty($request->input('waktu_masuk'))) ? $request->input('waktu_masuk') : null,
            'waktu_keluar' => ($keterangan == 'Hadir' && !empty($request->input('waktu_keluar'))) ? $request->input('waktu_keluar') : null,
        ];

        // Cek jika data presensi sudah ada sebelumnya, jika ada update, jika tidak create baru
        $presensi = PresensiStaf::where('id_card', $id_card)
            ->whereDate('tgl_presensi', Carbon::now()->toDateString()) // Menggunakan tanggal hari ini
            ->first();

        if ($presensi) {
            // Update jika ditemukan
            $presensi->update($presensiData);
        } else {
            // Tambahkan data baru jika tidak ada
            PresensiStaf::create($presensiData);
        }

        return response()->json(['message' => 'Presensi berhasil disimpan'], 200);
    }


    public function exportToPdf(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $stafs = Staf::all(); 
        $presensi = PresensiStaf::whereDate('tgl_presensi', $date)->get(); 

        $totalHadir = $presensi->where('keterangan', 'Hadir')->count();
        $totalIzin = $presensi->where('keterangan', 'Izin')->count();
        $totalAlfa = $presensi->where('keterangan', 'Alfa')->count();

        $pdf = PDF::loadView('presensi_staf.pdf', compact('stafs', 'presensi', 'date', 'totalHadir', 'totalIzin', 'totalAlfa'));
        return $pdf->download('presensi_staf_'.$date.'.pdf');
    }

}
