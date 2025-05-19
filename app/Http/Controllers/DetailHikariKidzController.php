<?php

namespace App\Http\Controllers;

use App\Models\HikariKidz;
use App\Models\DetailHikariKidz;
use App\Models\PesertaHikariKidz;
use App\Http\Requests\StoreDetailHikariKidzRequest;
use App\Http\Requests\UpdateDetailHikariKidzRequest;
use Illuminate\Http\Request;

class DetailHikariKidzController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Ambil daftar kursus dan peserta
        $hikari_kidzList = HikariKidz::all();
        $pesertaList = PesertaHikariKidz::all();

        // Jika ada filter hikari_kidz_id, ambil peserta berdasarkan kursus yang dipilih
        if ($request->has('hikari_kidz_id') && !empty($request->hikari_kidz_id)) {
            $hikari_kidz = HikariKidz::with('pesertaHikariKidz')->find($request->hikari_kidz_id);
            $peserta = $hikari_kidz ? $hikari_kidz->pesertaHikariKidz : collect();
        } else {
            $peserta = collect(); // Kosongkan peserta jika tidak ada filter
        }

        return view('detail_hikari_kidz.index', compact('hikari_kidzList', 'peserta', 'pesertaList'));
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'id_hikari_kidz' => 'required|exists:hikari_kidz,id',
            'id_anak' => 'required|exists:peserta_hikari_kidz,id',
            'tgl_masuk_hikari_kidz' => 'required',
        ]);

        $existingDetailHikariKidz = DetailHikariKidz::where('id_hikari_kidz', $request->id_hikari_kidz)
                                          ->where('id_anak', $request->id_anak)
                                          ->first();

        if ($existingDetailHikariKidz) {
            $notification = array(
                'message' => 'Peserta sudah terdaftar pada hikari kidz ini!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Simpan data ke tabel detail_hikari_kidz
        DetailHikariKidz::create([
            'id_hikari_kidz' => $request->id_hikari_kidz,
            'id_anak' => $request->id_anak,
            'tgl_masuk_hikari_kidz' => $request->tgl_masuk_hikari_kidz,
        ]);

        $notification = array(
            'message' => 'Peserta berhasil ditambahkan ke hikari kidz',
            'alert-type' => 'success'
        );
        return redirect()->route('hikari_kidz.detail')->with($notification);
    }

    public function ubahStatus(Request $request, $id)
    {
        // Cari peserta berdasarkan ID
       $detailhikarikidz = DetailHikariKidz::findOrFail($id);

        // Ambil status yang dikirimkan dari form
        $currentStatus = $request->input('current_status');

        // Ubah status berdasarkan status saat ini
        if ($currentStatus == 'Aktif') {
           $detailhikarikidz->status = 'Nonaktif'; // Jika status saat ini 'Aktif', ubah menjadi 'Nonaktif'
        } else {
           $detailhikarikidz->status = 'Aktif'; // Jika status saat ini 'Nonaktif', ubah menjadi 'Aktif'
        }

        // Simpan perubahan status pada tabel pivot
       $detailhikarikidz->save();

        $notification = array(
            'message' => 'Status peserta berhasil diperbarui',
            'alert-type' => 'info'
        );
        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with($notification);
    }
    
}
