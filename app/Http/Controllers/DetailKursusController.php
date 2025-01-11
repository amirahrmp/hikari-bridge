<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\PesertaKursus;
use App\Models\DetailKursus;
use App\Http\Requests\StoreDetailKursusRequest;
use App\Http\Requests\UpdateDetailKursusRequest;
use Illuminate\Http\Request;

class DetailKursusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Ambil daftar kursus dan peserta
        $kursusList = Kursus::all();
        $pesertaList = PesertaKursus::all();

        // Jika ada filter kursus_id, ambil peserta berdasarkan kursus yang dipilih
        if ($request->has('kursus_id') && !empty($request->kursus_id)) {
            $kursus = Kursus::with('pesertaKursus')->find($request->kursus_id);
            $peserta = $kursus ? $kursus->pesertaKursus : collect();
        } else {
            $peserta = collect(); // Kosongkan peserta jika tidak ada filter
        }

        return view('detail_kursus.index', compact('kursusList', 'peserta', 'pesertaList'));
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'id_kursus' => 'required|exists:kursus,id',
            'id_peserta' => 'required|exists:peserta_kursus,id',
            'tgl_masuk_kursus' => 'required',
        ]);

        $existingDetailKursus = DetailKursus::where('id_kursus', $request->id_kursus)
                                          ->where('id_peserta', $request->id_peserta)
                                          ->first();

        if ($existingDetailKursus) {
            $notification = array(
                'message' => 'Peserta sudah terdaftar pada kursus ini!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Simpan data ke tabel detail_kursus
        DetailKursus::create([
            'id_kursus' => $request->id_kursus,
            'id_peserta' => $request->id_peserta,
            'tgl_masuk_kursus' => $request->tgl_masuk_kursus,
        ]);

        $notification = array(
            'message' => 'Peserta berhasil ditambahkan ke kursus',
            'alert-type' => 'success'
        );
        return redirect()->route('kursus.detail')->with($notification);
    }

    public function ubahStatus(Request $request, $id)
    {
        // Cari peserta berdasarkan ID
        $detailkursus = DetailKursus::findOrFail($id);

        // Ambil status yang dikirimkan dari form
        $currentStatus = $request->input('current_status');

        // Ubah status berdasarkan status saat ini
        if ($currentStatus == 'Aktif') {
            $detailkursus->status = 'Nonaktif'; // Jika status saat ini 'Aktif', ubah menjadi 'Nonaktif'
        } else {
            $detailkursus->status = 'Aktif'; // Jika status saat ini 'Nonaktif', ubah menjadi 'Aktif'
        }

        // Simpan perubahan status pada tabel pivot
        $detailkursus->save();

        $notification = array(
            'message' => 'Status peserta berhasil diperbarui',
            'alert-type' => 'info'
        );
        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with($notification);
    }
    
}
