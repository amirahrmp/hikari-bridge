<?php

namespace App\Http\Controllers;

use App\Models\GajiStaf;
use App\Models\DetailGajiStaf;
use App\Models\Staf;
use App\Models\PresensiStaf;
use App\Http\Requests\StoreGajiStafRequest;
use App\Http\Requests\UpdateGajiStafRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GajiStafController extends Controller
{
    public function index()
    {
        $gaji = GajiStaf::with('detailGajiStaf')->get();

        return view('gaji_staf.index', compact('gaji'));
    }

    public function showForm(Request $request)
    {
        $stafs = Staf::where('status', 'Aktif')->get();
        $tgl_gaji = $request->get('tgl_gaji', Carbon::now()->format('Y-m-d'));
           
        return view('gaji_staf.form', compact('stafs', 'tgl_gaji'));
    }

    public function store(Request $request)
    {
        $bulan_tahun = $request->get('bulan_tahun');
        $tgl_gaji = $request->get('tgl_gaji');
        $keterangan = $request->get('keterangan');
        $total_gaji_dibayarkan = $request->get('total_gaji_dibayarkan');
        $id_gaji = 'GJ-' . Carbon::parse($tgl_gaji)->format('Ymd') . '-' . strtoupper(Str::random(5));

        // Simpan data gaji
        $gaji = GajiStaf::create([
            'id_gaji' => $id_gaji,
            'tgl_gaji' => $tgl_gaji,
            'keterangan' => $keterangan,
            'bulan_tahun' => $bulan_tahun,
            'total_gaji_dibayarkan' => $total_gaji_dibayarkan,
        ]);
        
        // Simpan detail gaji per staf hanya jika total_gaji ada dan tidak kosong
        foreach ($request->get('staf') as $staf) {
            // Pastikan total_gaji tidak kosong atau null
            if (!empty($staf['total_gaji']) && $staf['total_gaji'] > 0) {
                DetailGajiStaf::create([
                    'id_gaji' => $id_gaji,
                    'id_staf' => $staf['id'],
                    'gaji_pokok' => $staf['gaji_pokok'],
                    'uang_makan' => $staf['uang_makan'],
                    'tunjangan' => $staf['tunjangan'],
                    'bonus' => $staf['bonus'],
                    'potongan_pph21' => $staf['potongan_pph21'],
                    'potongan' => $staf['potongan'],
                    'total_gaji' => $staf['total_gaji'],
                ]);
            }
        }
        $notification = array(
            'message' => 'Data gaji staf berhasil ditambah!',
            'alert-type' => 'success'
        );

        return redirect()->route('gaji_staf.index')->with($notification);
    }


    public function edit($id)
    {
        // Ambil data GajiStaf berdasarkan ID
        $gaji = GajiStaf::findOrFail($id);

        // Ambil data DetailGajiStaf berdasarkan id_gaji
        $detailGajiStaf = DetailGajiStaf::where('id_gaji', $gaji->id_gaji)->get();

        // Jika ada data DetailGajiStaf, ambil semua id_staf yang terkait
        if ($detailGajiStaf->isNotEmpty()) {
            // Mengambil semua id_staf dari DetailGajiStaf
            $stafIds = $detailGajiStaf->pluck('id_staf'); // Ambil semua id_staf yang ada di detail_gaji_staf

            // Ambil semua data staf yang memiliki id_staf yang sesuai
            $stafs = Staf::whereIn('id', $stafIds)->get();  // Ambil semua staf yang ada di daftar id_staf
        } else {
            $stafs = collect();  // Jika tidak ada DetailGajiStaf, kirim koleksi kosong
        }

        // Kirim data ke view
        return view('gaji_staf.edit', compact('gaji', 'detailGajiStaf', 'stafs'));
    }



    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'bulan_tahun' => 'required|date',
            'tgl_gaji' => 'required|date',
            'staf.*.gaji_pokok' => 'required|numeric',
            'staf.*.uang_makan' => 'required|numeric',
            'staf.*.tunjangan' => 'required|numeric',
            'staf.*.bonus' => 'required|numeric',
            'staf.*.potongan_pph21' => 'required|numeric',
            'staf.*.potongan' => 'required|numeric',
        ]);

        // Update data gaji
        $gaji = GajiStaf::findOrFail($id);
        $gaji->update([
            'bulan_tahun' => $request->get('bulan_tahun'),
            'tgl_gaji' => $request->get('tgl_gaji'),
            'keterangan' => $request->get('keterangan'),
            'total_gaji_dibayarkan' => $request->get('total_gaji_dibayarkan'),
        ]);

        // Update detail gaji per staf
        foreach ($request->get('staf') as $stafData) {
            $stafDetail = DetailGajiStaf::where('id_gaji', $gaji->id_gaji)
                ->where('id_staf', $stafData['id'])
                ->first();

            if ($stafDetail) {
                $stafDetail->update([
                    'gaji_pokok' => $stafData['gaji_pokok'],
                    'uang_makan' => $stafData['uang_makan'],
                    'tunjangan' => $stafData['tunjangan'],
                    'bonus' => $stafData['bonus'],
                    'potongan_pph21' => $stafData['potongan_pph21'],
                    'potongan' => $stafData['potongan'],
                    'total_gaji' => $stafData['total_gaji'],
                ]);
            }
        }
        $gaji->save();
        $notification = array(
            'message' => 'Data gaji staf berhasil diubah!',
            'alert-type' => 'info'
        );
        return redirect()->route('gaji_staf.index')->with($notification);
    }

}
