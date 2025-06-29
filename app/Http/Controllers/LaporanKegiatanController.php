<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKegiatan;
use App\Models\PesertaHikariKidz;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use File;

class LaporanKegiatanController extends Controller
{
    /**
     * Tampilkan semua laporan kegiatan untuk Harian Kegiatan Daring (HKD).
     * Ini adalah tampilan default untuk daycare.blade.php
     */
    public function index()
    {
        // Mengambil laporan kegiatan hanya yang bertipe 'HKD' untuk tabel utama
        $laporan_kegiatan = LaporanKegiatan::with('peserta')
                                           ->where('tipe', 'HKD')
                                           ->orderBy('tanggal', 'desc')
                                           ->get();

        // Mengambil peserta Hikari Kidz yang sudah terverifikasi, bertipe 'HKD', dan status keaktifan 'Aktif'
        $peserta = PesertaHikariKidz::where('status', 'Terverifikasi')
                                     ->where('tipe', 'HKD')
                                     ->where('status_keaktifan', 'Aktif')
                                     ->orderBy('full_name')
                                     ->get();

        return view('laporan_kegiatan.daycare', compact('laporan_kegiatan', 'peserta'));
    }

    /**
     * Mengambil data laporan kegiatan dalam format JSON untuk diedit (khusus HKD).
     * Digunakan oleh Ajax di frontend untuk mengisi modal edit.
     *
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(LaporanKegiatan $laporanKegiatan)
    {
        if ($laporanKegiatan->tipe !== 'HKD') {
            return response()->json(['error' => 'Laporan ini bukan tipe HKD dan tidak dapat diedit dari sini.'], 403);
        }

        $kegiatan_data = $laporanKegiatan->kegiatan;
        $kegiatan_utama_names = ['Snack Pagi', 'Makan Siang', 'Snack Sore', 'Tidur Siang'];
        $kegiatan_utama_checkbox = [
            'snack_pagi'  => in_array('Snack Pagi', $kegiatan_data ?? []),
            'makan_siang' => in_array('Makan Siang', $kegiatan_data ?? []),
            'snack_sore'  => in_array('Snack Sore', $kegiatan_data ?? []),
            'tidur_siang' => in_array('Tidur Siang', $kegiatan_data ?? []),
        ];
        $kegiatan_tambahan_list = array_diff($kegiatan_data ?? [], $kegiatan_utama_names);
        $kegiatan_tambahan_string = implode(', ', $kegiatan_tambahan_list);

        // Siapkan array URL foto untuk dikirim ke frontend
        $foto_urls = [];
        if (is_array($laporanKegiatan->foto)) {
            foreach ($laporanKegiatan->foto as $file_name) {
                // Pastikan file ada sebelum membuat URL
                if (File::exists(public_path('uploads/laporankegiatanhkd/' . $file_name))) {
                    $foto_urls[] = asset('uploads/laporankegiatanhkd/' . $file_name);
                }
            }
        } elseif (is_string($laporanKegiatan->foto) && !empty($laporanKegiatan->foto)) {
            // Kasus kompatibilitas jika masih ada data string dari sebelum migrasi
            if (File::exists(public_path('uploads/laporankegiatanhkd/' . $laporanKegiatan->foto))) {
                $foto_urls[] = asset('uploads/laporankegiatanhkd/' . $laporanKegiatan->foto);
            }
        }

        return response()->json([
            'id'                       => $laporanKegiatan->id,
            'peserta_id'               => $laporanKegiatan->peserta_id,
            'tanggal'                  => $laporanKegiatan->tanggal->format('Y-m-d'),
            'kegiatan_utama_checkbox'  => $kegiatan_utama_checkbox,
            'kegiatan_tambahan_string' => $kegiatan_tambahan_string,
            'catatan'                  => $laporanKegiatan->catatan,
            'foto'                     => $foto_urls,
            'old_foto_names'           => $laporanKegiatan->foto,
        ]);
    }

    /**
     * Perbarui laporan kegiatan yang ada di database (khusus HKD).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, LaporanKegiatan $laporanKegiatan)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta_hikari_kidz,id_anak',
            'tanggal'    => 'required|date',
            'kegiatan'   => 'nullable|string',
            'catatan'    => 'nullable|string',
            'foto.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'old_foto_names.*' => 'nullable|string',
            'deleted_foto_names.*' => 'nullable|string',
        ]);

        $peserta = PesertaHikariKidz::where('id_anak', $request->peserta_id)
                                    ->where('status', 'Terverifikasi')
                                    ->where('tipe', 'HKD') // Pastikan tipe HKD
                                    ->where('status_keaktifan', 'Aktif')
                                    ->first();

        if (!$peserta) {
            return redirect()->back()->withInput()->with('error', 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKD Aktif Terverifikasi.');
        }

        $currentFotoNames = $laporanKegiatan->foto ?? [];
        $newFotoNames = [];

        if ($request->has('deleted_foto_names') && is_array($request->deleted_foto_names)) {
            foreach ($request->deleted_foto_names as $deleted_file_name) {
                $filePath = public_path('uploads/laporankegiatanhkd/' . $deleted_file_name); // Path HKD
                if (File::exists($filePath)) {
                    File::delete($filePath);
                    $currentFotoNames = array_diff($currentFotoNames, [$deleted_file_name]);
                }
            }
        }

        if ($request->has('old_foto_names') && is_array($request->old_foto_names)) {
           $newFotoNames = array_merge($newFotoNames, $request->old_foto_names);
        } else {
           foreach ($currentFotoNames as $old_file_name) {
               $filePath = public_path('uploads/laporankegiatanhkd/' . $old_file_name); // Path HKD
               if (File::exists($filePath)) {
                   File::delete($filePath);
               }
           }
           $currentFotoNames = [];
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;

                    $file->move(public_path('uploads/laporankegiatanhkd'), $newFileName); // Path HKD
                    $newFotoNames[] = $newFileName;
                }
            }
        }

        $all_activities = [];
        if ($request->has('snack_pagi')) { $all_activities[] = 'Snack Pagi'; }
        if ($request->has('makan_siang')) { $all_activities[] = 'Makan Siang'; }
        if ($request->has('snack_sore')) { $all_activities[] = 'Snack Sore'; }
        if ($request->has('tidur_siang')) { $all_activities[] = 'Tidur Siang'; }

        if (!empty($request->kegiatan)) {
            $additional_activities = array_map('trim', preg_split('/[\r\n,]+/', $request->kegiatan, -1, PREG_SPLIT_NO_EMPTY));
            $all_activities = array_merge($all_activities, $additional_activities);
        }

        $dataToUpdate = [
            'peserta_id'  => $peserta->id_anak,
            'nama_anak'   => $peserta->full_name,
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $all_activities,
            'catatan'     => $request->catatan,
            'foto'        => count($newFotoNames) > 0 ? array_values($newFotoNames) : null,
            'tipe'        => 'HKD',
        ];

        $laporanKegiatan->update($dataToUpdate);

        return redirect()->route('laporan_kegiatan.daycare.index')
                         ->with('success', 'Laporan kegiatan berhasil diperbarui untuk ' . $peserta->full_name . '!');
    }

    /**
     * Simpan laporan kegiatan baru ke database (khusus HKD).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta_hikari_kidz,id_anak',
            'tanggal'    => 'required|date',
            'kegiatan'   => 'nullable|string',
            'catatan'    => 'nullable|string',
            'foto.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $peserta = PesertaHikariKidz::where('id_anak', $request->peserta_id)
                                    ->where('status', 'Terverifikasi')
                                    ->where('tipe', 'HKD') // Pastikan tipe HKD
                                    ->where('status_keaktifan', 'Aktif')
                                    ->first();

        if (!$peserta) {
            return redirect()->back()->withInput()->with('error', 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKD Aktif Terverifikasi.');
        }

        $fotoFileNames = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;

                    $file->move(public_path('uploads/laporankegiatanhkd'), $newFileName); // Path HKD
                    $fotoFileNames[] = $newFileName;
                }
            }
        }

        $all_activities = [];
        if ($request->has('snack_pagi')) { $all_activities[] = 'Snack Pagi'; }
        if ($request->has('makan_siang')) { $all_activities[] = 'Makan Siang'; }
        if ($request->has('snack_sore')) { $all_activities[] = 'Snack Sore'; }
        if ($request->has('tidur_siang')) { $all_activities[] = 'Tidur Siang'; }

        if (!empty($request->kegiatan)) {
            $additional_activities = array_map('trim', preg_split('/[\r\n,]+/', $request->kegiatan, -1, PREG_SPLIT_NO_EMPTY));
            $all_activities = array_merge($all_activities, $additional_activities);
        }

        LaporanKegiatan::create([
            'peserta_id'  => $peserta->id_anak,
            'nama_anak'   => $peserta->full_name,
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $all_activities,
            'catatan'     => $request->catatan,
            'foto'        => count($fotoFileNames) > 0 ? $fotoFileNames : null,
            'tipe'        => 'HKD',
        ]);

        return redirect()->back()->with('success', 'Laporan kegiatan berhasil disimpan.');
    }

    /**
     * Hapus laporan kegiatan dari database (khusus HKD).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        if ($laporan->tipe !== 'HKD') {
             return redirect()->back()->with('error', 'Laporan ini bukan tipe HKD dan tidak dapat dihapus dari sini.');
        }

        // Hapus semua file foto terkait jika ada
        if (is_array($laporan->foto)) {
            foreach ($laporan->foto as $file_name) {
                $filePath = public_path('uploads/laporankegiatanhkd/' . $file_name); // Path HKD
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        } elseif (is_string($laporan->foto) && !empty($laporan->foto)) {
            $filePath = public_path('uploads/laporankegiatanhkd/' . $laporan->foto); // Path HKD
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan kegiatan berhasil dihapus.');
    }

    // --- METODE BARU/MODIFIKASI UNTUK HKC ---

    /**
     * Simpan laporan Harian Kegiatan Cetak (HKC) baru ke database.
     * Metode ini menerima data dari modal form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLaporanHkc(Request $request)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'peserta_id'    => 'required|array', // Menerima array peserta_id
            'peserta_id.*'  => 'exists:peserta_hikari_kidz,id_anak', // Validasi setiap item di array
            'tema_kegiatan' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'catatan'       => 'nullable|string',
            'foto.*'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fotoFileNames = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;

                    // Simpan foto ke direktori 'uploads/laporankegiatanhkc/'
                    $file->move(public_path('uploads/laporankegiatanhkc'), $newFileName);
                    $fotoFileNames[] = $newFileName;
                }
            }
        }

        foreach ($request->peserta_id as $pesertaId) {
            $peserta = PesertaHikariKidz::where('id_anak', $pesertaId)
                                        ->where('status', 'Terverifikasi')
                                        ->where('tipe', 'HKC')
                                        ->where('status_keaktifan', 'Aktif')
                                        ->first();

            if (!$peserta) {
                Log::warning("Percobaan menyimpan laporan HKC untuk peserta ID {$pesertaId} yang tidak valid.");
                continue;
            }

            $kegiatan_data = [
                'tema' => $request->tema_kegiatan,
                'nama' => $request->nama_kegiatan,
            ];

            LaporanKegiatan::create([
                'peserta_id'  => $peserta->id_anak,
                'nama_anak'   => $peserta->full_name,
                'tanggal'     => $request->tanggal,
                'kegiatan'    => $kegiatan_data, // Disimpan sebagai JSON di kolom 'kegiatan'
                'catatan'     => $request->catatan,
                'foto'        => count($fotoFileNames) > 0 ? $fotoFileNames : null,
                'tipe'        => 'HKC', // Pastikan tipenya HKC
            ]);
        }

        return redirect()->route('laporan_kegiatan.hkc_list')->with('success', 'Laporan kegiatan HKC berhasil disimpan!');
    }

    /**
     * Mengambil data laporan kegiatan HKC dalam format JSON untuk diedit.
     * Mengikuti pola edit HKD Anda.
     *
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\JsonResponse
     */
    public function editLaporanHkc(LaporanKegiatan $laporanKegiatan)
    {
        if ($laporanKegiatan->tipe !== 'HKC') {
            return response()->json(['error' => 'Laporan ini bukan tipe HKC dan tidak dapat diedit dari sini.'], 403);
        }

        // Data kegiatan HKC disimpan sebagai ['tema' => '...', 'nama' => '...']
        $tema_kegiatan = $laporanKegiatan->kegiatan['tema'] ?? '';
        $nama_kegiatan = $laporanKegiatan->kegiatan['nama'] ?? '';

        // Siapkan array URL foto untuk dikirim ke frontend
        $foto_urls = [];
        if (is_array($laporanKegiatan->foto)) {
            foreach ($laporanKegiatan->foto as $file_name) {
                if (File::exists(public_path('uploads/laporankegiatanhkc/' . $file_name))) {
                    $foto_urls[] = asset('uploads/laporankegiatanhkc/' . $file_name);
                }
            }
        }

        return response()->json([
            'id'             => $laporanKegiatan->id,
            'peserta_id'     => [$laporanKegiatan->peserta_id], // Kirim sebagai array untuk Select2 multiple
            'tanggal'        => $laporanKegiatan->tanggal->format('Y-m-d'),
            'tema_kegiatan'  => $tema_kegiatan,
            'nama_kegiatan'  => $nama_kegiatan,
            'catatan'        => $laporanKegiatan->catatan,
            'foto'           => $foto_urls,
            'old_foto_names' => $laporanKegiatan->foto,
        ]);
    }

    /**
     * Perbarui laporan kegiatan HKC yang ada di database.
     * Mengikuti pola update HKD Anda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LaporanKegiatan  $laporanKegiatan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLaporanHkc(Request $request, LaporanKegiatan $laporanKegiatan)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'peserta_id'    => 'required|array', // Menerima array peserta_id
            'peserta_id.*'  => 'exists:peserta_hikari_kidz,id_anak', // Validasi setiap item di array
            'tema_kegiatan' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'catatan'       => 'nullable|string',
            'foto.*'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'old_foto_names.*' => 'nullable|string',
            'deleted_foto_names.*' => 'nullable|string',
        ]);

        if ($laporanKegiatan->tipe !== 'HKC') {
            return redirect()->back()->withInput()->with('error', 'Laporan ini bukan tipe HKC dan tidak dapat diperbarui dari sini.');
        }

        // Asumsi: Saat edit, user hanya edit detail kegiatan untuk SATU laporan yang sudah ada.
        // Jika user mengubah peserta, ini akan menimbulkan kompleksitas karena satu baris laporan DB
        // hanya untuk satu peserta. Untuk kasus ini, kita ambil peserta_id[0]
        $peserta = PesertaHikariKidz::where('id_anak', $request->peserta_id[0])
                                    ->where('status', 'Terverifikasi')
                                    ->where('tipe', 'HKC')
                                    ->where('status_keaktifan', 'Aktif')
                                    ->first();

        if (!$peserta) {
            return redirect()->back()->withInput()->with('error', 'Peserta tidak ditemukan atau tidak memenuhi kriteria HKC Aktif Terverifikasi.');
        }

        $currentFotoNames = $laporanKegiatan->foto ?? [];
        $newFotoNames = [];

        // Hapus foto yang ditandai untuk dihapus
        if ($request->has('deleted_foto_names') && is_array($request->deleted_foto_names)) {
            foreach ($request->deleted_foto_names as $deleted_file_name) {
                $filePath = public_path('uploads/laporankegiatanhkc/' . $deleted_file_name);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                    $currentFotoNames = array_diff($currentFotoNames, [$deleted_file_name]);
                }
            }
        }

        // Tambahkan foto lama yang dipertahankan
        if ($request->has('old_foto_names') && is_array($request->old_foto_names)) {
           $newFotoNames = array_merge($newFotoNames, $request->old_foto_names);
        } else {
            // Jika old_foto_names tidak dikirim, artinya semua foto lama dihapus
           foreach ($currentFotoNames as $old_file_name) {
               $filePath = public_path('uploads/laporankegiatanhkc/' . $old_file_name);
               if (File::exists($filePath)) {
                   File::delete($filePath);
               }
           }
           $currentFotoNames = [];
        }

        // Upload dan tambahkan foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = Str::slug($originalName) . '-' . time() . '-' . Str::random(5) . '.' . $extension;
                    $file->move(public_path('uploads/laporankegiatanhkc'), $newFileName);
                    $newFotoNames[] = $newFileName;
                }
            }
        }

        $kegiatan_data = [
            'tema' => $request->tema_kegiatan,
            'nama' => $request->nama_kegiatan,
        ];

        $dataToUpdate = [
            'peserta_id'  => $peserta->id_anak,
            'nama_anak'   => $peserta->full_name,
            'tanggal'     => $request->tanggal,
            'kegiatan'    => $kegiatan_data,
            'catatan'     => $request->catatan,
            'foto'        => count($newFotoNames) > 0 ? array_values($newFotoNames) : null,
            'tipe'        => 'HKC',
        ];

        $laporanKegiatan->update($dataToUpdate);

        return redirect()->route('laporan_kegiatan.hkc_list')->with('success', 'Laporan kegiatan HKC berhasil diperbarui!');
    }

    /**
     * Hapus laporan kegiatan HKC dari database.
     * Mengikuti pola destroy HKD Anda.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyLaporanHkc($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        if ($laporan->tipe !== 'HKC') {
             return redirect()->back()->with('error', 'Laporan ini bukan tipe HKC dan tidak dapat dihapus dari sini.');
        }

        if (is_array($laporan->foto)) {
            foreach ($laporan->foto as $file_name) {
                $filePath = public_path('uploads/laporankegiatanhkc/' . $file_name);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }
        
        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan kegiatan HKC berhasil dihapus.');
    }


    /**
     * Menampilkan laporan kegiatan untuk Harian Kegiatan Cetak (HKC)
     * Ini adalah halaman utama untuk HKC, menampilkan tabel data dan modal create/edit.
     *
     * @param string|null $id_anak Opsional, untuk melihat laporan HKC spesifik anak (tapi kita akan menampilkan semua di tabel utama)
     * @return \Illuminate\View\View
     */
    public function showHarianKegiatanCetak($id_anak = null)
    {
        // Mengambil semua laporan HKC dari peserta yang aktif dan terverifikasi
        // untuk ditampilkan di tabel utama.
        $hkc_participant_ids = PesertaHikariKidz::where('status', 'Terverifikasi')
                                                     ->where('tipe', 'HKC')
                                                     ->where('status_keaktifan', 'Aktif')
                                                     ->pluck('id_anak')
                                                     ->unique();

        $laporan_kegiatan = LaporanKegiatan::with('peserta')
                                           ->whereIn('peserta_id', $hkc_participant_ids)
                                           ->where('tipe', 'HKC')
                                           ->orderBy('tanggal', 'desc')
                                           ->get();
        
        // Data untuk form CREATE/EDIT HKC (untuk modal)
        $pesertaHKC = PesertaHikariKidz::where('status', 'Terverifikasi')
                                        ->where('tipe', 'HKC')
                                        ->where('status_keaktifan', 'Aktif')
                                        ->orderBy('full_name')
                                        ->get();

        // Variabel namaAnak, periode, id_anak tidak relevan untuk tampilan tabel utama ini
        // tapi tetap di-compact jika view Anda menggunakannya untuk breadcrumb/judul dinamis.
        return view('laporan_kegiatan.hkc', compact('laporan_kegiatan', 'pesertaHKC'));
    }

    /**
     * Metode yang Anda berikan untuk menampilkan laporan HKC yang digrup berdasarkan kelas.
     * Saya tetap biarkan ini, tapi rute utama HKC tidak akan mengarah ke sini
     * agar konsisten dengan fungsionalitas modal create/edit yang mengikuti showHarianKegiatanCetak.
     */
    public function showLaporanHKC() // Ini adalah metode yang grup per kelas
    {
        $tanggal = now()->toDateString();

        $laporan_list = LaporanKegiatan::where('tipe', 'like', 'HKC-%')
                                        ->whereDate('tanggal', $tanggal)
                                        ->get()
                                        ->groupBy('tipe');

        $laporan_by_kelas = [];

        foreach ($laporan_list as $tipe => $laporan_group) {
            $first = $laporan_group->first();
            $kelas = str_replace('HKC-', '', $tipe);

            $kegiatan_formatted = [];
            if (is_array($first->kegiatan) && isset($first->kegiatan['tema']) && isset($first->kegiatan['nama'])) {
                $kegiatan_formatted = [
                    '0' => [
                        'nama' => $first->kegiatan['nama'],
                        'catatan' => $first->kegiatan['tema']
                    ]
                ];
            } else {
                 $kegiatan_formatted = collect($first->kegiatan)->mapWithKeys(function ($item, $index) {
                    return [$index => is_array($item) ? $item : ['nama' => $item, 'catatan' => null]];
                });
            }

            $laporan_by_kelas[$kelas] = [
                'tanggal' => $first->tanggal,
                'kegiatan' => $kegiatan_formatted,
                'foto' => $first->foto,
            ];
        }

        // Catatan: Metode ini tidak meng-compact $pesertaHKC.
        return view('laporan_kegiatan.hkc', compact('laporan_by_kelas'));
    }
}