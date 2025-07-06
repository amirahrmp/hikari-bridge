<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKegiatan;
use App\Models\PesertaHikariKidz; // Pastikan model ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use File; // Pastikan ini diimpor

class LaporanKegiatanController extends Controller
{
    // Direktori penyimpanan foto untuk HKD
    const HKD_UPLOAD_PATH = 'uploads/laporankegiatanhkd/';
    // Direktori penyimpanan foto untuk HKC
    const HKC_UPLOAD_PATH = 'uploads/laporankegiatanhkc/';

    /**
     * Tampilkan semua laporan kegiatan untuk Harian Kegiatan Daring (HKD).
     * Ini adalah tampilan default untuk daycare.blade.php
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = auth()->id(); // Mengambil ID user yang sedang login

        // Ambil ID anak yang milik user ini dengan kriteria 'Terverifikasi', 'HKD', dan 'Aktif'
        $pesertaIds = PesertaHikariKidz::where('user_id', $userId)
                                        ->where('status', 'Terverifikasi')
                                        ->where('tipe', 'HKD')
                                        ->where('status_keaktifan', 'Aktif')
                                        ->pluck('id_anak'); // Mengambil hanya kolom id_anak

        // Ambil semua laporan HKD berdasarkan anak-anak tersebut, diurutkan berdasarkan tanggal terbaru
        $laporan_kegiatan = LaporanKegiatan::with('peserta') // Memuat relasi peserta
                                            ->whereIn('peserta_id', $pesertaIds)
                                            ->where('tipe', 'HKD')
                                            ->orderBy('tanggal', 'desc')
                                            ->get();

        // Ambil data peserta yang relevan untuk dropdown di form (sesuai kriteria di atas)
        $peserta = PesertaHikariKidz::whereIn('id_anak', $pesertaIds)
                                    ->orderBy('full_name')
                                    ->get();

        // Mengirim data ke view
        return view('laporan_kegiatan.daycare', compact('laporan_kegiatan', 'peserta'));
    }

    /**
     * Mengambil data laporan kegiatan HKD dalam format JSON untuk diedit.
     * Digunakan oleh Ajax di frontend untuk mengisi modal edit.
     * Menggunakan Route Model Binding: LaporanKegiatan $laporanKegiatan.
     *
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(LaporanKegiatan $laporanKegiatan)
    {
        // Pastikan laporan yang diedit adalah tipe HKD
        if ($laporanKegiatan->tipe !== 'HKD') {
            return response()->json(['error' => 'Laporan ini bukan tipe HKD dan tidak dapat diedit dari sini.'], 403);
        }

        $kegiatan_data = $laporanKegiatan->kegiatan; // Array kegiatan dari DB
        $kegiatan_utama_names = ['Snack Pagi', 'Makan Siang', 'Snack Sore', 'Tidur Siang'];

        // Menentukan status checkbox kegiatan utama
        $kegiatan_utama_checkbox = [
            'snack_pagi'  => in_array('Snack Pagi', $kegiatan_data ?? []),
            'makan_siang' => in_array('Makan Siang', $kegiatan_data ?? []),
            'snack_sore'  => in_array('Snack Sore', $kegiatan_data ?? []),
            'tidur_siang' => in_array('Tidur Siang', $kegiatan_data ?? []),
        ];

        // Memisahkan kegiatan tambahan
        $kegiatan_tambahan_list = array_diff($kegiatan_data ?? [], $kegiatan_utama_names);
        $kegiatan_tambahan_string = implode(', ', $kegiatan_tambahan_list); // Menggabungkan menjadi string

        // Siapkan array URL foto untuk dikirim ke frontend
        $foto_urls = [];
        $fotosInDb = is_array($laporanKegiatan->foto) ? $laporanKegiatan->foto : (empty($laporanKegiatan->foto) ? [] : [$laporanKegiatan->foto]);

        foreach ($fotosInDb as $file_name) {
            $filePath = public_path(self::HKD_UPLOAD_PATH . $file_name);
            if (File::exists($filePath)) {
                $foto_urls[] = asset(self::HKD_UPLOAD_PATH . $file_name);
            }
        }

        // Mengembalikan data dalam format JSON
        return response()->json([
            'id'                       => $laporanKegiatan->id,
            'peserta_id'               => $laporanKegiatan->peserta_id,
            'tanggal'                  => $laporanKegiatan->tanggal->format('Y-m-d'), // Format tanggal untuk input HTML
            'kegiatan_utama_checkbox'  => $kegiatan_utama_checkbox,
            'kegiatan_tambahan_string' => $kegiatan_tambahan_string,
            'catatan'                  => $laporanKegiatan->catatan,
            'foto'                     => $foto_urls, // URL foto untuk ditampilkan
            'old_foto_names'           => $fotosInDb, // Nama file asli untuk melacak di frontend
        ]);
    }

    /**
     * Perbarui laporan kegiatan HKD yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, LaporanKegiatan $laporanKegiatan)
    {
        // Validasi input
        $request->validate([
            'peserta_id'           => 'required|exists:peserta_hikari_kidz,id_anak',
            'tanggal'              => 'required|date',
            'kegiatan'             => 'nullable|string', // Kegiatan tambahan
            'catatan'              => 'nullable|string',
            'foto.*'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk multiple files
            'old_foto_names.*'     => 'nullable|string', // Nama foto lama yang dipertahankan
            'deleted_foto_names.*' => 'nullable|string', // Nama foto yang dihapus
        ]);

        // Pastikan laporan yang diperbarui adalah tipe HKD
        if ($laporanKegiatan->tipe !== 'HKD') {
            return response()->json(['error' => 'Laporan ini bukan tipe HKD dan tidak dapat diperbarui dari sini.'], 403);
        }

        // Verifikasi peserta
        $peserta = PesertaHikariKidz::where('id_anak', $request->peserta_id)
                                    ->where('status', 'Terverifikasi')
                                    ->where('tipe', 'HKD')
                                    ->where('status_keaktifan', 'Aktif')
                                    ->first();

        if (!$peserta) {
            // Jika request adalah AJAX, kembalikan JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKD Aktif Terverifikasi.'], 400);
            }
            // Jika bukan AJAX, redirect dengan error
            return redirect()->back()->withInput()->with('error', 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKD Aktif Terverifikasi.');
        }

        $currentFotoNames = $laporanKegiatan->foto ?? []; // Foto yang saat ini ada di DB
        $finalFotoNames = [];

        // 1. Hapus foto yang ditandai untuk dihapus dari folder dan dari array `currentFotoNames`
        if ($request->has('deleted_foto_names') && is_array($request->deleted_foto_names)) {
            foreach ($request->deleted_foto_names as $deleted_file_name) {
                $filePath = public_path(self::HKD_UPLOAD_PATH . $deleted_file_name);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
            // Filter foto yang dihapus dari array foto yang ada di DB
            $currentFotoNames = array_diff($currentFotoNames, $request->deleted_foto_names);
        }

        // 2. Tambahkan foto lama yang dipertahankan (yang tidak dihapus) ke `finalFotoNames`
        if ($request->has('old_foto_names') && is_array($request->old_foto_names)) {
            // Hanya masukkan nama file dari old_foto_names yang memang ada di currentFotoNames
            $finalFotoNames = array_values(array_intersect($currentFotoNames, $request->old_foto_names));
        } else {
            // Jika old_foto_names tidak dikirim, berarti semua foto lama dihapus (kecuali yang baru diupload)
            $finalFotoNames = [];
        }


        // 3. Upload dan tambahkan foto baru ke `finalFotoNames`
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;

                    $file->move(public_path(self::HKD_UPLOAD_PATH), $newFileName);
                    $finalFotoNames[] = $newFileName;
                }
            }
        }

        // Gabungkan kegiatan utama (checkbox) dan kegiatan tambahan (textarea)
        $all_activities = [];
        if ($request->has('snack_pagi')) { $all_activities[] = 'Snack Pagi'; }
        if ($request->has('makan_siang')) { $all_activities[] = 'Makan Siang'; }
        if ($request->has('snack_sore')) { $all_activities[] = 'Snack Sore'; }
        if ($request->has('tidur_siang')) { $all_activities[] = 'Tidur Siang'; }

        if (!empty($request->kegiatan)) {
            // Pisahkan kegiatan tambahan berdasarkan koma atau baris baru
            $additional_activities = array_map('trim', preg_split('/[\r\n,]+/', $request->kegiatan, -1, PREG_SPLIT_NO_EMPTY));
            $all_activities = array_merge($all_activities, $additional_activities);
        }

        // Data yang akan diperbarui
        $dataToUpdate = [
            'peserta_id'  => $peserta->id_anak,
            'nama_anak'   => $peserta->full_name,
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $all_activities, // Simpan sebagai array
            'catatan'     => $request->catatan,
            'foto'        => count($finalFotoNames) > 0 ? array_values($finalFotoNames) : null, // Pastikan array di-reindex
            'tipe'        => 'HKD',
        ];

        $laporanKegiatan->update($dataToUpdate);

        // Respon untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan kegiatan berhasil diperbarui untuk ' . $peserta->full_name . '!']);
        }

        // Respon untuk non-AJAX (fallback)
        return redirect()->route('laporan_kegiatan.daycare.index')
                         ->with('success', 'Laporan kegiatan berhasil diperbarui untuk ' . $peserta->full_name . '!');
    }

    /**
     * Simpan laporan kegiatan HKD baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'peserta_id' => 'required|exists:peserta_hikari_kidz,id_anak',
            'tanggal'    => 'required|date',
            'kegiatan'   => 'nullable|string', // Kegiatan tambahan
            'catatan'    => 'nullable|string',
            'foto.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk multiple files
        ]);

        // Verifikasi peserta
        $peserta = PesertaHikariKidz::where('id_anak', $request->peserta_id)
                                    ->where('status', 'Terverifikasi')
                                    ->where('tipe', 'HKD')
                                    ->where('status_keaktifan', 'Aktif')
                                    ->first();

        if (!$peserta) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKD Aktif Terverifikasi.'], 400);
            }
            return redirect()->back()->withInput()->with('error', 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKD Aktif Terverifikasi.');
        }

        $fotoFileNames = [];
        // Upload foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;

                    $file->move(public_path(self::HKD_UPLOAD_PATH), $newFileName);
                    $fotoFileNames[] = $newFileName;
                }
            }
        }

        // Gabungkan kegiatan utama (checkbox) dan kegiatan tambahan (textarea)
        $all_activities = [];
        if ($request->has('snack_pagi')) { $all_activities[] = 'Snack Pagi'; }
        if ($request->has('makan_siang')) { $all_activities[] = 'Makan Siang'; }
        if ($request->has('snack_sore')) { $all_activities[] = 'Snack Sore'; }
        if ($request->has('tidur_siang')) { $all_activities[] = 'Tidur Siang'; }

        if (!empty($request->kegiatan)) {
            $additional_activities = array_map('trim', preg_split('/[\r\n,]+/', $request->kegiatan, -1, PREG_SPLIT_NO_EMPTY));
            $all_activities = array_merge($all_activities, $additional_activities);
        }

        // Buat laporan kegiatan baru
        LaporanKegiatan::create([
            'peserta_id'  => $peserta->id_anak,
            'nama_anak'   => $peserta->full_name,
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $all_activities,
            'catatan'     => $request->catatan,
            'foto'        => count($fotoFileNames) > 0 ? $fotoFileNames : null,
            'tipe'        => 'HKD',
        ]);

        // Respon untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan kegiatan berhasil disimpan.']);
        }

        // Respon untuk non-AJAX (fallback)
        return redirect()->back()->with('success', 'Laporan kegiatan berhasil disimpan.');
    }

    /**
     * Hapus laporan kegiatan HKD dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        // Pastikan laporan yang dihapus adalah tipe HKD
        if ($laporan->tipe !== 'HKD') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Laporan ini bukan tipe HKD dan tidak dapat dihapus dari sini.'], 403);
            }
            return redirect()->back()->with('error', 'Laporan ini bukan tipe HKD dan tidak dapat dihapus dari sini.');
        }

        // Hapus semua file foto terkait jika ada
        $fotosInDb = is_array($laporan->foto) ? $laporan->foto : (empty($laporan->foto) ? [] : [$laporan->foto]);
        foreach ($fotosInDb as $file_name) {
            $filePath = public_path(self::HKD_UPLOAD_PATH . $file_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $laporan->delete();

        // Respon untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan kegiatan berhasil dihapus.']);
        }

        // Respon untuk non-AJAX (fallback)
        return redirect()->back()->with('success', 'Laporan kegiatan berhasil dihapus.');
    }

    // --- METODE UNTUK HKC ---

    /**
     * Menampilkan laporan kegiatan untuk Harian Kegiatan Cetak (HKC).
     * Ini adalah halaman utama untuk HKC, menampilkan tabel data dan modal create/edit.
     *
     * @return \Illuminate\View\View
     */
    public function showHarianKegiatanCetak()
    {
        // Ambil semua laporan HKC, diurutkan berdasarkan tanggal terbaru
        $laporan_kegiatan = LaporanKegiatan::where('tipe', 'HKC')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('laporan_kegiatan.hkc', compact('laporan_kegiatan'));
    }

    /**
     * Simpan laporan Harian Kegiatan Cetak (HKC) baru ke database.
     * Metode ini menerima data dari modal form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeLaporanHkc(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal'       => 'required|date',
            'tema_kegiatan' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'catatan'       => 'nullable|string',
            'foto.*'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk multiple files
        ]);

        $fotoFileNames = [];
        // Upload foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;

                    $file->move(public_path(self::HKC_UPLOAD_PATH), $newFileName);
                    $fotoFileNames[] = $newFileName;
                }
            }
        }

        // Data kegiatan untuk HKC disimpan sebagai array asosiatif (tema dan nama)
        $kegiatan_data = [
            'tema' => $request->tema_kegiatan,
            'nama' => $request->nama_kegiatan,
        ];

        // Buat laporan HKC baru
        LaporanKegiatan::create([
            'peserta_id'  => null, // HKC tidak terkait dengan peserta tertentu
            'nama_anak'   => null, // HKC tidak terkait dengan nama anak tertentu
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $kegiatan_data,
            'catatan'     => $request->catatan,
            'foto'        => count($fotoFileNames) > 0 ? $fotoFileNames : null,
            'tipe'        => 'HKC',
        ]);

        // Respon untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan kegiatan HKC berhasil disimpan!']);
        }

        // Respon untuk non-AJAX (fallback)
        return redirect()->route('laporan_kegiatan.hkc_list')->with('success', 'Laporan kegiatan HKC berhasil disimpan!');
    }

    /**
     * Mengambil data laporan kegiatan HKC dalam format JSON untuk diedit.
     * Menggunakan Route Model Binding: LaporanKegiatan $laporanKegiatan.
     *
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\JsonResponse
     */
    public function editLaporanHkc(LaporanKegiatan $laporanKegiatan)
    {
        // Pastikan laporan yang diedit adalah tipe HKC
        if ($laporanKegiatan->tipe !== 'HKC') {
            return response()->json(['error' => 'Laporan ini bukan tipe HKC dan tidak dapat diedit dari sini.'], 403);
        }

        // Ambil tema dan nama kegiatan dari array kegiatan
        $tema_kegiatan = $laporanKegiatan->kegiatan['tema'] ?? '';
        $nama_kegiatan = $laporanKegiatan->kegiatan['nama'] ?? '';

        // Siapkan array URL foto untuk dikirim ke frontend
        $foto_urls = [];
        $fotosInDb = is_array($laporanKegiatan->foto) ? $laporanKegiatan->foto : (empty($laporanKegiatan->foto) ? [] : [$laporanKegiatan->foto]);

        foreach ($fotosInDb as $file_name) {
            $filePath = public_path(self::HKC_UPLOAD_PATH . $file_name);
            if (File::exists($filePath)) {
                $foto_urls[] = asset(self::HKC_UPLOAD_PATH . $file_name);
            }
        }

        // Mengembalikan data dalam format JSON
        return response()->json([
            'id'             => $laporanKegiatan->id,
            'tanggal'        => $laporanKegiatan->tanggal->format('Y-m-d'),
            'tema_kegiatan'  => $tema_kegiatan,
            'nama_kegiatan'  => $nama_kegiatan,
            'catatan'        => $laporanKegiatan->catatan,
            'foto'           => $foto_urls, // URL foto untuk ditampilkan
            'old_foto_names' => $fotosInDb, // Nama file asli untuk melacak di frontend
        ]);
    }

    /**
     * Perbarui laporan kegiatan HKC yang ada di database.
     * Menggunakan Route Model Binding: LaporanKegiatan $laporanKegiatan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateLaporanHkc(Request $request, LaporanKegiatan $laporanKegiatan)
    {
        // Validasi input
        $request->validate([
            'tanggal'              => 'required|date',
            'tema_kegiatan'        => 'required|string|max:255',
            'nama_kegiatan'        => 'required|string|max:255',
            'catatan'              => 'nullable|string',
            'foto.*'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'old_foto_names.*'     => 'nullable|string',
            'deleted_foto_names.*' => 'nullable|string',
        ]);

        // Pastikan laporan yang diperbarui adalah tipe HKC
        if ($laporanKegiatan->tipe !== 'HKC') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Laporan ini bukan tipe HKC dan tidak dapat diperbarui dari sini.'], 403);
            }
            return redirect()->back()->withInput()->with('error', 'Laporan ini bukan tipe HKC dan tidak dapat diperbarui dari sini.');
        }

        $currentFotoNames = $laporanKegiatan->foto ?? [];
        $finalFotoNames = [];

        // 1. Hapus foto yang ditandai untuk dihapus dari folder dan dari array `currentFotoNames`
        if ($request->has('deleted_foto_names') && is_array($request->deleted_foto_names)) {
            foreach ($request->deleted_foto_names as $deleted_file_name) {
                $filePath = public_path(self::HKC_UPLOAD_PATH . $deleted_file_name);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
            $currentFotoNames = array_diff($currentFotoNames, $request->deleted_foto_names);
        }

        // 2. Tambahkan foto lama yang dipertahankan ke `finalFotoNames`
        if ($request->has('old_foto_names') && is_array($request->old_foto_names)) {
            $finalFotoNames = array_values(array_intersect($currentFotoNames, $request->old_foto_names));
        } else {
            $finalFotoNames = [];
        }

        // 3. Upload dan tambahkan foto baru ke `finalFotoNames`
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;
                    $file->move(public_path(self::HKC_UPLOAD_PATH), $newFileName);
                    $finalFotoNames[] = $newFileName;
                }
            }
        }

        // Data kegiatan untuk HKC disimpan sebagai array asosiatif (tema dan nama)
        $kegiatan_data = [
            'tema' => $request->tema_kegiatan,
            'nama' => $request->nama_kegiatan,
        ];

        // Data yang akan diperbarui
        $dataToUpdate = [
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $kegiatan_data,
            'catatan'     => $request->catatan,
            'foto'        => count($finalFotoNames) > 0 ? array_values($finalFotoNames) : null,
            'tipe'        => 'HKC',
        ];

        $laporanKegiatan->update($dataToUpdate);

        // Respon untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan kegiatan HKC berhasil diperbarui!']);
        }

        // Respon untuk non-AJAX (fallback)
        return redirect()->route('laporan_kegiatan.hkc_list')->with('success', 'Laporan kegiatan HKC berhasil diperbarui!');
    }

    /**
     * Hapus laporan kegiatan HKC dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroyLaporanHkc($id, Request $request)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        // Pastikan laporan yang dihapus adalah tipe HKC
        if ($laporan->tipe !== 'HKC') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Laporan ini bukan tipe HKC dan tidak dapat dihapus dari sini.'], 403);
            }
            return redirect()->back()->with('error', 'Laporan ini bukan tipe HKC dan tidak dapat dihapus dari sini.');
        }

        // Hapus semua file foto terkait jika ada
        $fotosInDb = is_array($laporan->foto) ? $laporan->foto : (empty($laporan->foto) ? [] : [$laporan->foto]);
        foreach ($fotosInDb as $file_name) {
            $filePath = public_path(self::HKC_UPLOAD_PATH . $file_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $laporan->delete();

        // Respon untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan kegiatan HKC berhasil dihapus.']);
        }

        // Respon untuk non-AJAX (fallback)
        return redirect()->back()->with('success', 'Laporan kegiatan HKC berhasil dihapus.');
    }

    /**
     * Metode ini tampaknya untuk menampilkan laporan HKC yang digrup berdasarkan kelas.
     * Jika metode ini tidak digunakan untuk tabel utama HKC, Anda bisa menghapusnya atau memindahkannya.
     * Saya akan tetap menyertakannya tetapi tidak menggunakannya di view utama HKC.
     *
     * @return \Illuminate\View\View
     */
    public function showLaporanHKC() // Ini adalah metode yang grup per kelas
    {
        $tanggal = now()->toDateString();

        // Mengambil laporan dengan tipe 'HKC-%' (misal HKC-A, HKC-B)
        $laporan_list = LaporanKegiatan::where('tipe', 'like', 'HKC-%')
                                        ->whereDate('tanggal', $tanggal)
                                        ->get()
                                        ->groupBy('tipe'); // Mengelompokkan berdasarkan tipe (misal HKC-A, HKC-B)

        $laporan_by_kelas = [];

        foreach ($laporan_list as $tipe => $laporan_group) {
            $first = $laporan_group->first(); // Mengambil laporan pertama dari setiap grup (asumsi data per kelas sama)
            $kelas = str_replace('HKC-', '', $tipe); // Mengambil nama kelas (misal 'A' dari 'HKC-A')

            $kegiatan_formatted = [];
            // Memeriksa format kegiatan (bisa array asosiatif tema/nama atau array string)
            if (is_array($first->kegiatan) && isset($first->kegiatan['tema']) && isset($first->kegiatan['nama'])) {
                $kegiatan_formatted = [
                    [
                        'nama' => $first->kegiatan['nama'],
                        'catatan' => $first->kegiatan['tema'] // Menggunakan tema sebagai catatan untuk format ini
                    ]
                ];
            } else {
                // Jika formatnya array string, ubah ke format yang konsisten
                $kegiatan_formatted = collect($first->kegiatan)->map(function ($item) {
                    return is_array($item) ? $item : ['nama' => $item, 'catatan' => null];
                })->values()->all(); // Pastikan di-reindex
            }

            // Menyimpan data laporan per kelas
            $laporan_by_kelas[$kelas] = [
                'tanggal' => $first->tanggal,
                'kegiatan' => $kegiatan_formatted,
                'foto' => $first->foto,
            ];
        }

        // Mengirim data ke view (perhatikan bahwa view 'laporan_kegiatan.hkc' di atas tidak menggunakan $laporan_by_kelas ini)
        return view('laporan_kegiatan.hkc', compact('laporan_by_kelas'));
    }
}
