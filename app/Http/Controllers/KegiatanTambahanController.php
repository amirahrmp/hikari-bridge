<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanTambahan;
use App\Models\PesertaHikariKidz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class KegiatanTambahanController extends Controller
{
    // Menampilkan daftar semua kegiatan tambahan (Untuk Admin)
    // ayang bututtttttttttttttttttttttttttt
    public function index()
    {
        $anak = PesertaHikariKidz::where('status', 'terverifikasi')
                                 ->where('tipe', 'HKC')
                                 ->get();
        $kegiatan_tambahan = KegiatanTambahan::with('anak')->get();
        return view('kegiatan_tambahan.index', compact('anak', 'kegiatan_tambahan'));
    }

    // Menyimpan data kegiatan tambahan baru (Untuk Admin)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_anak' => 'required|exists:peserta_hikari_kidz,id_anak',
            'nama_kegiatan' => 'required|string|max:255',
            'biaya' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);
        $validatedData['status_pembayaran'] = 'belum'; // Default status
        KegiatanTambahan::create($validatedData);
        return redirect()->route('kegiatan_tambahan.index')->with('success', 'Data kegiatan tambahan berhasil ditambahkan.');
    }

    // Method edit (Untuk Admin)
    public function edit(KegiatanTambahan $kegiatanTambahan)
    {
        $anak = PesertaHikariKidz::where('status', 'terverifikasi')
                                 ->where('tipe', 'HKC')
                                 ->get();
        return view('kegiatan_tambahan.edit', compact('kegiatanTambahan', 'anak'));
    }

    // Method update (Untuk Admin)
    public function update(Request $request, KegiatanTambahan $kegiatanTambahan)
    {
        $validatedData = $request->validate([
            'id_anak' => 'required|exists:peserta_hikari_kidz,id_anak',
            'nama_kegiatan' => 'required|string|max:255',
            'biaya' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status_pembayaran' => 'required|in:belum,menunggu verifikasi,lunas', // Sesuaikan status
        ]);

        // Jika status diubah menjadi 'belum' atau 'menunggu verifikasi', kosongkan bukti pembayaran dan tanggal
        // Perhatikan di sini: pastikan menggunakan 'bukti_pembayaran_path'
        if (in_array($validatedData['status_pembayaran'], ['belum', 'menunggu verifikasi'])) {
            if ($kegiatanTambahan->bukti_pembayaran) { // *** UBAH INI
                Storage::disk('public')->delete('proof_of_payments/' . $kegiatanTambahan->bukti_pembayaran); // *** UBAH INI
            }
            $validatedData['bukti_pembayaran'] = null; // *** UBAH INI
            $validatedData['payment_method'] = null;
            $validatedData['payment_date'] = null;
        }

        $kegiatanTambahan->update($validatedData);

        return redirect()->route('kegiatan_tambahan.index')->with('success', 'Data kegiatan tambahan berhasil diubah.');
    }

    // Perbaikan method ubahStatus (Untuk Admin)
    public function ubahStatus(Request $request, KegiatanTambahan $kegiatanTambahan)
    {
        $currentStatus = strtolower($kegiatanTambahan->status_pembayaran);

        if ($currentStatus === 'belum' || $currentStatus === 'menunggu verifikasi') {
            $kegiatanTambahan->status_pembayaran = 'lunas';
            $message = 'Status pembayaran berhasil diubah menjadi Lunas.';
        } else {
            $kegiatanTambahan->status_pembayaran = 'belum'; // Kembali ke 'belum'
            // Jika dikembalikan ke 'belum', hapus juga bukti pembayaran
            // Perhatikan di sini: pastikan menggunakan 'bukti_pembayaran'
            if ($kegiatanTambahan->bukti_pembayaran) { // *** UBAH INI
                Storage::disk('public')->delete('proof_of_payments/' . $kegiatanTambahan->bukti_pembayaran); // *** UBAH INI
            }
            $kegiatanTambahan->bukti_pembayaran = null; // *** UBAH INI
            $kegiatanTambahan->payment_method = null;
            $kegiatanTambahan->payment_date = null;
            $message = 'Status pembayaran berhasil diubah menjadi Belum Lunas.';
        }

        $kegiatanTambahan->save();

        $notification = [
            'message' => $message,
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // Method destroy (Untuk Admin)
    public function destroy(KegiatanTambahan $kegiatan_tambahan)
    {
        // Hapus juga bukti pembayaran jika ada
        // Perhatikan di sini: pastikan menggunakan 'bukti_pembayaran_path'
        if ($kegiatan_tambahan->bukti_pembayaran_path) { // *** UBAH INI
            Storage::disk('public')->delete('proof_of_payments/' . $kegiatan_tambahan->bukti_pembayaran_path); // *** UBAH INI
        }
        $kegiatan_tambahan->delete();
        $notification = [
            'message' => 'Data paket berhasil dihapus!',
            'alert-type' => 'info'
        ];
        return redirect()->back()->with($notification);
    }

    public function upload(Request $request)
    {
        // ... (Logika impor Excel Anda) ...
        return redirect()->route('kegiatan_tambahan.index')->with('error', 'Fungsi impor Excel belum diimplementasikan sepenuhnya.');
    }

    public function userIndex()
{
    $user = Auth::user();
    
    // Step 1: Log data user
    \Log::info('USER LOGIN:', [
        'id' => $user->id,
        'name' => $user->name
    ]);

    // Step 2: Ambil relasi anak
    $pesertaAnakDariUser = $user->pesertaHikariKidz;
    \Log::info('Anak dari user:', $pesertaAnakDariUser->pluck('id_anak')->toArray());

    $id_anak_list = $pesertaAnakDariUser->pluck('id_anak')->toArray();

    if (empty($id_anak_list)) {
        \Log::warning('User tidak punya anak terhubung.');
        $kegiatan_tambahan_user = collect();
        $total_tagihan_belum_lunas = 0;
        return view('pembayaran_kegiatan_tambahan_user.index', compact('kegiatan_tambahan_user', 'total_tagihan_belum_lunas'));
    }

    // Step 3: Ambil tagihan
    $kegiatan_tambahan_user = KegiatanTambahan::whereIn('id_anak', $id_anak_list)
        ->with('anak')
        ->get();

    \Log::info('Tagihan ditemukan:', $kegiatan_tambahan_user->pluck('id_anak')->toArray());

    $total_tagihan_belum_lunas = $kegiatan_tambahan_user
        ->whereIn('status_pembayaran', ['belum', 'menunggu verifikasi'])
        ->sum('biaya');

    return view('pembayaran_kegiatan_tambahan_user.index', compact('kegiatan_tambahan_user', 'total_tagihan_belum_lunas'));
}


    
    public function uploadBuktiPembayaran(Request $request, KegiatanTambahan $kegiatanTambahan)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'payment_method' => 'required|string|in:Transfer Bank,QRIS',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, svg, webp.',
            'bukti_pembayaran.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus bukti pembayaran lama jika ada sebelum mengunggah yang baru
            if ($kegiatanTambahan->bukti_pembayaran_path && Storage::disk('public')->exists('proof_of_payments/' . $kegiatanTambahan->bukti_pembayaran_path)) {
                Storage::disk('public')->delete('proof_of_payments/' . $kegiatanTambahan->bukti_pembayaran_path);
            }

            $image = $request->file('bukti_pembayaran');
            $fileName = 'bukti_' . $kegiatanTambahan->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('proof_of_payments', $fileName, 'public'); // Store the file and get its relative path

            $kegiatanTambahan->bukti_pembayaran_path = $fileName; // Simpan nama file ke kolom bukti_pembayaran_path
            $kegiatanTambahan->payment_method = $request->payment_method;
            $kegiatanTambahan->payment_date = now();
            $kegiatanTambahan->status_pembayaran = 'menunggu verifikasi'; // Ubah status setelah upload

            $kegiatanTambahan->save();

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah! Tagihan ini sekarang "Menunggu Verifikasi" oleh admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
}