<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth']);

// route untuk validasi login
Route::post('/validasi_login', [App\Http\Controllers\LoginController::class, 'show']);

// Profile
Route::get('/profil', [App\Http\Controllers\UserController::class, 'profil'])->name('user.profil');
Route::post('/profil', [App\Http\Controllers\UserController::class, 'updateProfil'])->name('user.update_profil');

// route ke master data users
Route::resource('users', App\Http\Controllers\UsersController::class);
Route::put('/users/{id}/updateRole', [App\Http\Controllers\UsersController::class, 'updateRole'])->name('users.updateRole')->middleware(['auth']);
Route::get('/delete-user/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy')->middleware(['auth']);

// route ke master data coa
Route::resource('coa', App\Http\Controllers\CoaController::class);
Route::post('coa', [App\Http\Controllers\CoaController::class, 'store'])->name('coa.store')->middleware(['auth']);
Route::post('/upload-coa-excel', [App\Http\Controllers\CoaController::class, 'uploadExcel'])->name('coa.upload');
Route::put('/coa/{id}/update', [App\Http\Controllers\CoaController::class, 'update'])->name('coa.update')->middleware(['auth']);
Route::get('/delete-coa/{id}', [App\Http\Controllers\CoaController::class, 'destroy'])->name('coa.destroy')->middleware(['auth']);

// route ke master data jabatan
Route::resource('jabatan', App\Http\Controllers\JabatanController::class);
Route::post('jabatan', [App\Http\Controllers\JabatanController::class, 'store'])->name('jabatan.store')->middleware(['auth']);
Route::post('/upload-jabatan-excel', [App\Http\Controllers\JabatanController::class, 'uploadExcel'])->name('jabatan.upload');
Route::put('/jabatan/{id}/update', [App\Http\Controllers\JabatanController::class, 'update'])->name('jabatan.update')->middleware(['auth']);
Route::get('/delete-jabatan/{id}', [App\Http\Controllers\JabatanController::class, 'destroy'])->name('jabatan.destroy')->middleware(['auth']);

// route ke master data staf
Route::resource('staf', App\Http\Controllers\StafController::class);
Route::post('staf', [App\Http\Controllers\StafController::class, 'store'])->name('staf.store')->middleware(['auth']);
Route::post('/upload-staf-excel', [App\Http\Controllers\StafController::class, 'uploadExcel'])->name('staf.upload');
Route::put('/staf/{id}/update', [App\Http\Controllers\StafController::class, 'update'])->name('staf.update')->middleware(['auth']);
Route::get('/delete-staf/{id}', [App\Http\Controllers\StafController::class, 'destroy'])->name('staf.destroy')->middleware(['auth']);

// route ke master data teacher
Route::resource('teacher', App\Http\Controllers\TeacherController::class);
Route::post('teacher', [App\Http\Controllers\TeacherController::class, 'store'])->name('teacher.store')->middleware(['auth']);
Route::post('/upload-teacher-excel', [App\Http\Controllers\TeacherController::class, 'uploadExcel'])->name('teacher.upload');
Route::put('/teacher/{id}/update', [App\Http\Controllers\TeacherController::class, 'update'])->name('teacher.update')->middleware(['auth']);
Route::get('/delete-teacher/{id}', [App\Http\Controllers\TeacherController::class, 'destroy'])->name('teacher.destroy')->middleware(['auth']);

// route ke master data peserta kursus
Route::resource('peserta_kursus', App\Http\Controllers\PesertaKursusController::class);
Route::post('peserta_kursus', [App\Http\Controllers\PesertaKursusController::class, 'store'])->name('peserta_kursus.store')->middleware(['auth']);
Route::post('/upload-peserta_kursus-excel', [App\Http\Controllers\PesertaKursusController::class, 'uploadExcel'])->name('peserta_kursus.upload');
Route::put('/peserta_kursus/{id}/update', [App\Http\Controllers\PesertaKursusController::class, 'update'])->name('peserta_kursus.update')->middleware(['auth']);
Route::get('/delete-peserta_kursus/{id}', [App\Http\Controllers\PesertaKursusController::class, 'destroy'])->name('peserta_kursus.destroy')->middleware(['auth']);

// route ke master data kursus
Route::resource('kursus', App\Http\Controllers\KursusController::class);
Route::post('kursus', [App\Http\Controllers\KursusController::class, 'store'])->name('kursus.store')->middleware(['auth']);
Route::post('/upload-kursus-excel', [App\Http\Controllers\KursusController::class, 'uploadExcel'])->name('kursus.upload');
Route::put('/kursus/{id}/update', [App\Http\Controllers\KursusController::class, 'update'])->name('kursus.update')->middleware(['auth']);
Route::get('/delete-kursus/{id}', [App\Http\Controllers\KursusController::class, 'destroy'])->name('kursus.destroy')->middleware(['auth']);

// detail kursus
Route::get('/kursus/detail', [App\Http\Controllers\DetailKursusController::class, 'show'])->name('kursus.detail')->middleware(['auth']);
Route::post('/kursus/store', [App\Http\Controllers\DetailKursusController::class, 'store'])->name('kursus.store')->middleware(['auth']);
Route::patch('ubah-status/{id}', [App\Http\Controllers\DetailKursusController::class, 'ubahStatus'])->name('ubah.status')->middleware(['auth']);

// jadwal kursus
Route::resource('jadwal_kursus', App\Http\Controllers\JadwalKursusController::class)->middleware(['auth']);
Route::post('jadwal_kursus', [App\Http\Controllers\JadwalKursusController::class, 'store'])->name('jadwal_kursus.store')->middleware(['auth']);
Route::put('/jadwal_kursus/{id}/update', [App\Http\Controllers\JadwalKursusController::class, 'update'])->name('jadwal_kursus.update')->middleware(['auth']);
Route::get('/delete-jadwal_kursus/{id}', [App\Http\Controllers\JadwalKursusController::class, 'destroy'])->name('jadwal_kursus.destroy')->middleware(['auth']);

// detail jadwal kursus
Route::get('jadwal_kursus/{id}/show', [App\Http\Controllers\JadwalKursusController::class, 'show'])->name('jadwal_kursus.show')->middleware(['auth']);
Route::post('/jadwal-kursus/{id}/add-peserta', [App\Http\Controllers\JadwalKursusController::class, 'addPeserta'])->name('jadwal_kursus.addPeserta')->middleware(['auth']);
Route::delete('/jadwal-kursus/{jadwalId}/remove-peserta/{pesertaId}', [App\Http\Controllers\JadwalKursusController::class, 'removePeserta'])->name('jadwal_kursus.removePeserta')->middleware(['auth']);

// jadwal kursus peserta dan teacher
Route::middleware('auth')->post('/jadwal-kursus', [App\Http\Controllers\JadwalKursusController::class, 'showByEmail'])->name('jadwal_kursus.peserta');
Route::post('jadwal-kursus-teacher', [App\Http\Controllers\JadwalKursusController::class, 'showTeacherSchedule']);

// presensi staf
Route::resource('presensi_staf', App\Http\Controllers\PresensiStafController::class)->middleware(['auth']);
Route::post('presensi_staf/store', [App\Http\Controllers\PresensiStafController::class, 'store'])->name('presensi_staf.store')->middleware(['auth']);
Route::get('/presensi-staf/export/pdf', [App\Http\Controllers\PresensiStafController::class, 'exportToPdf'])->name('presensi_staf.exportPdf')->middleware(['auth']);
Route::post('/presensi/rfid', [App\Http\Controllers\PresensiStafController::class, 'autoPresensi'])->middleware(['auth']);

// presensi teacher
Route::resource('presensi_teacher', App\Http\Controllers\PresensiTeacherController::class)->middleware(['auth']);
Route::post('presensi_teacher/store', [App\Http\Controllers\PresensiTeacherController::class, 'store'])->name('presensi_teacher.store')->middleware(['auth']);
Route::get('/presensi-teacher/export/pdf', [App\Http\Controllers\PresensiTeacherController::class, 'exportToPdf'])->name('presensi_teacher.exportPdf')->middleware(['auth']);
Route::post('riwayat_presensi-teacher', [App\Http\Controllers\PresensiTeacherController::class, 'showRiwayatPresensi'])->name('riwayat.presensi')->middleware(['auth']);
Route::post('/presensi/rfid-teacher', [App\Http\Controllers\PresensiTeacherController::class, 'autoPresensi'])->middleware(['auth']);

// gaji staf
Route::get('gaji_staf', [App\Http\Controllers\GajiStafController::class, 'index'])->name('gaji_staf.index');
Route::get('gaji_staf/form', [App\Http\Controllers\GajiStafController::class, 'showForm'])->name('gaji_staf.form');
Route::post('gaji_staf/store', [App\Http\Controllers\GajiStafController::class, 'store'])->name('gaji_staf.store');
Route::get('/gaji_staf/{id}/edit', [App\Http\Controllers\GajiStafController::class, 'edit'])->name('gaji_staf.edit');
Route::put('/gaji_staf/{id}', [App\Http\Controllers\GajiStafController::class, 'update'])->name('gaji_staf.update');

// jurnal dan buku besar
Route::get('jurnal/umum', [App\Http\Controllers\JurnalController::class,'jurnalumum'])->middleware(['auth']);
Route::get('jurnal/viewdatajurnalumum/{periode}', [App\Http\Controllers\JurnalController::class,'viewdatajurnalumum'])->middleware(['auth']);
Route::get('jurnal/bukubesar', [App\Http\Controllers\JurnalController::class,'bukubesar'])->middleware(['auth']);
Route::get('jurnal/viewdatabukubesar/{periode}/{akun}', [App\Http\Controllers\JurnalController::class,'viewdatabukubesar'])->middleware(['auth']);





////////////////////////////////////////////////////////////////////////////////////////////////////
// master data jabatan
Route::resource('jabatan', App\Http\Controllers\JabatanController::class);
Route::resource('potongan-gaji', App\Http\Controllers\PotonganGajiController::class);

//route ke transaksi Purchase
Route::get('purchase/tabel', [App\Http\Controllers\PurchaseController::class,'tabel'])->middleware(['auth']);
Route::get('purchase/fetchpurchase', [App\Http\Controllers\PurchaseController::class,'fetchpurchase'])->middleware(['auth']);
Route::get('purchase/fetchAll', [App\Http\Controllers\PurchaseController::class,'fetchAll'])->middleware(['auth']);
Route::get('purchase/edit/{id}', [App\Http\Controllers\PurchaseController::class,'edit'])->middleware(['auth']);
Route::get('purchase/destroy/{id}', [App\Http\Controllers\PurchaseController::class,'destroy'])->middleware(['auth']);
Route::get('/purchase/filter', 'App\Http\Controllers\PurchaseController@filterByDate');
Route::resource('purchase', App\Http\Controllers\PurchaseController::class)->middleware(['auth']); 

//route ke transaksi Pengeluaran
Route::get('pengeluaran/tabel', [App\Http\Controllers\PengeluaranController::class,'tabel'])->middleware(['auth']);
Route::get('pengeluaran/fetchpurchase', [App\Http\Controllers\PengeluaranController::class,'fetchpurchase'])->middleware(['auth']);
Route::get('pengeluaran/fetchAll', [App\Http\Controllers\PengeluaranController::class,'fetchAll'])->middleware(['auth']);
Route::get('pengeluaran/edit/{id}', [App\Http\Controllers\PengeluaranController::class,'edit'])->middleware(['auth']);
Route::get('pengeluaran/destroy/{id}', [App\Http\Controllers\PengeluaranController::class,'destroy'])->middleware(['auth']);
Route::get('/pengeluaran/filter', 'App\Http\Controllers\PengeluaranController@filterByDate');
Route::resource('pengeluaran', App\Http\Controllers\PengeluaranController::class)->middleware(['auth']); 

// penggajian
Route::get('absensis', [App\Http\Controllers\AbsensiController::class, 'index'])->name('absensis.index');
Route::get('absensis/kehadiran', [App\Http\Controllers\AbsensiController::class, 'show'])->name('absensis.show');
Route::post('absensis/kehadiran', [App\Http\Controllers\AbsensiController::class, 'store'])->name('absensis.store');
Route::get('gaji', [App\Http\Controllers\GajiController::class, 'index'])->name('gaji.index');
Route::get('gaji/cetak/{bulan}/{tahun}', [App\Http\Controllers\GajiController::class, 'cetak'])->name('gaji.cetak');
Route::get('laporan/slip-gaji', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::post('laporan/slip-gaji', [App\Http\Controllers\LaporanController::class, 'store'])->name('laporan.store');

// laporan gaji
Route::get('laporan/slip-gaji/karyawan', [App\Http\Controllers\LaporanController::class, 'show'])->name('laporan.show');
Route::post('laporan/slip-gaji/karyawan', [App\Http\Controllers\LaporanController::class, 'cekGaji'])->name('laporan.karyawan');

// grafik
Route::get('grafik/viewPenjualanBlnBerjalan', [App\Http\Controllers\GrafikController::class,'viewBulanBerjalan'])->middleware(['auth']);

// calendar
Route::get('fullcalendar', [\App\Http\Controllers\FullcalendarController::class, 'index'])->name('fullcalendar');
require __DIR__.'/auth.php';

//registerkidzclub
use App\Http\Controllers\RegistrationHikariKidzClubController;
use App\Http\Controllers\PaketHkcController;
Route::get('/registerkidzclub', [RegistrationHikariKidzClubController::class, 'create'])->name('registerkidzclub.create');
Route::post('/registerkidzclub', [RegistrationHikariKidzClubController::class, 'store'])->name('registerkidzclub.store');
// Route baru untuk ambil data paket berdasarkan member dan kelas
Route::get('/get-paket_hkc-by-member-kelas/{member}/{kelas}', [PaketHkcController::class, 'getByMemberAndKelas']);
Route::get('/paket_hkc/{id}', [PaketHkcController::class, 'getPaketHkcById']);

//registerkidzdaycare
use App\Http\Controllers\RegistrationHikariKidzDaycareController;
use App\Http\Controllers\PaketController;
Route::get('/registerkidzdaycare', [RegistrationHikariKidzDaycareController::class, 'create'])->name('registerkidzdaycare.create');
Route::post('/registerkidzdaycare', [RegistrationHikariKidzDaycareController::class, 'store'])->name('registerkidzdaycare.store');
// Route baru untuk ambil data paket berdasarkan tipe
Route::get('/get-paket-by-tipe/{tipe}', [PaketController::class, 'getByTipe']);
Route::get('/paket/{id}', [PaketController::class, 'getPaketById']);

//register hikari quran
use App\Http\Controllers\RegistrationHikariQuranController;
use App\Http\Controllers\PaketHqController;
Route::get('/registerquran', [RegistrationHikariQuranController::class, 'create'])->name('registerquran.create');
Route::post('/registerquran', [RegistrationHikariQuranController::class, 'store'])->name('registerquran.store');
// Route baru untuk ambil data paket berdasarkan kelas
Route::get('/get-paket_hq-by-kelas/{kelas}', [PaketHqController::class, 'getByKelas']);
Route::get('/paket_hq/{id}', [PaketHqController::class, 'getPaketHqById']);

//register program hkcw
use App\Http\Controllers\RegistrationProgramHkcwController;
Route::get('/registerprogramhkcw', [RegistrationProgramHkcwController::class, 'create'])->name('registerprogramhkcw.create');
Route::post('/registerprogramhkcw', [RegistrationProgramHkcwController::class, 'store'])->name('registerprogramhkcw.store');

//daftar kursus
use App\Http\Controllers\DaftarKursusController;

Route::get('/daftarkursus', [DaftarKursusController::class, 'create'])->name('daftarkursus.index');
Route::post('/daftarkursus', [DaftarKursusController::class, 'store'])->name('daftarkursus.store');

//riwayatpendaftaran
use App\Http\Controllers\RiwayatPendaftaranController;
Route::get('/riwayatpendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('riwayat.pendaftaran');

//tagihanpembayaran
use App\Http\Controllers\TagihanPembayaranController;
Route::get('/tagihanpembayaran', [TagihanPembayaranController::class, 'index'])->name('tagihan.pembayaran');

// route ke master data paket
Route::resource('paket', App\Http\Controllers\PaketController::class);
Route::post('paket', [App\Http\Controllers\PaketController::class, 'store'])->name('paket.store')->middleware(['auth']);
Route::post('/upload-paket-excel', [App\Http\Controllers\PaketController::class, 'uploadExcel'])->name('paket.upload');
Route::put('/paket/{id}/update', [App\Http\Controllers\PaketController::class, 'update'])->name('paket.update')->middleware(['auth']);
Route::get('/delete-paket/{id}', [App\Http\Controllers\PaketController::class, 'destroy'])->name('paket.destroy')->middleware(['auth']);

// route ke master data paket hkc
Route::resource('paket_hkc', App\Http\Controllers\PaketHkcController::class);
Route::post('paket_hkc', [App\Http\Controllers\PaketHkcController::class, 'store'])->name('paket_hkc.store')->middleware(['auth']);
Route::post('/upload-paket_hkc-excel', [App\Http\Controllers\PaketHkcController::class, 'uploadExcel'])->name('paket_hkc.upload');
Route::put('/paket_hkc/{id}/update', [App\Http\Controllers\PaketHkcController::class, 'update'])->name('paket_hkc.update')->middleware(['auth']);
Route::get('/delete-paket_hkc/{id}', [App\Http\Controllers\PaketHkcController::class, 'destroy'])->name('paket_hkc.destroy')->middleware(['auth']);

// route ke master data paket hq
Route::resource('paket_hq', App\Http\Controllers\PaketHqController::class);
Route::post('paket_hq', [App\Http\Controllers\PaketHqController::class, 'store'])->name('paket_hq.store')->middleware(['auth']);
Route::post('/upload-paket_hq-excel', [App\Http\Controllers\PaketHqController::class, 'uploadExcel'])->name('paket_hq.upload');
Route::put('/paket_hq/{id}/update', [App\Http\Controllers\PaketHqController::class, 'update'])->name('paket_hq.update')->middleware(['auth']);
Route::get('/delete-paket_hq/{id}', [App\Http\Controllers\PaketHqController::class, 'destroy'])->name('paket_hq.destroy')->middleware(['auth']);

// route ke master data pengasuh
Route::resource('pengasuh', App\Http\Controllers\PengasuhController::class);
Route::post('pengasuh', [App\Http\Controllers\PengasuhController::class, 'store'])->name('pengasuh.store')->middleware(['auth']);
Route::post('/upload-pengasuh-excel', [App\Http\Controllers\PengasuhController::class, 'uploadExcel'])->name('pengasuh.upload');
Route::put('/pengasuh/{id}/update', [App\Http\Controllers\PengasuhController::class, 'update'])->name('pengasuh.update')->middleware(['auth']);
Route::get('/delete-pengasuh/{id}', [App\Http\Controllers\PengasuhController::class, 'destroy'])->name('pengasuh.destroy')->middleware(['auth']);

// route ke master data peserta hikari kidz
Route::resource('peserta_hikari_kidz', App\Http\Controllers\PesertaHikariKidzController::class);
Route::post('peserta_hikari_kidz', [App\Http\Controllers\PesertaHikariKidzController::class, 'store'])->name('peserta_hikari_kidz.store')->middleware(['auth']);
Route::post('/upload-peserta_hikari_kidz-excel', [App\Http\Controllers\PesertaHikariKidzController::class, 'uploadExcel'])->name('peserta_hikari_kidz.upload');
Route::put('/peserta_hikari_kidz/{id}/update', [App\Http\Controllers\PesertaHikariKidzController::class, 'update'])->name('peserta_hikari_kidz.update')->middleware(['auth']);
Route::get('/delete-peserta_hikari_kidz/{id}', [App\Http\Controllers\PesertaHikariKidzController::class, 'destroy'])->name('peserta_hikari_kidz.destroy')->middleware(['auth']);

// route ke master data hikari kidz
Route::resource('hikari_kidz', App\Http\Controllers\HikariKidzController::class);
Route::post('hikari_kidz', [App\Http\Controllers\HikariKidzController::class, 'store'])->name('hikari_kidz.store')->middleware(['auth']);
Route::post('/upload-hikari_kidz-excel', [App\Http\Controllers\HikariKidzController::class, 'uploadExcel'])->name('hikari_kidz.upload');
Route::put('/hikari_kidz/{id}/update', [App\Http\Controllers\HikariKidzController::class, 'update'])->name('hikari_kidz.update')->middleware(['auth']);
Route::get('/delete-hikari_kidz/{id}', [App\Http\Controllers\HikariKidzController::class, 'destroy'])->name('hikari_kidz.destroy')->middleware(['auth']);

// detail hikari kidz
Route::get('/hikari_kidz/detail', [App\Http\Controllers\DetailHikariKidzController::class, 'show'])->name('hikari_kidz.detail')->middleware(['auth']);
Route::post('/hikari_kidz/store', [App\Http\Controllers\DetailHikariKidzController::class, 'store'])->name('hikari_kidz.store')->middleware(['auth']);
Route::patch('ubah-status/{id}', [App\Http\Controllers\DetailHikariKidzController::class, 'ubahStatus'])->name('ubah.status')->middleware(['auth']);

// jadwal hikari kidz
Route::resource('jadwal_hikari_kidz', App\Http\Controllers\JadwalHikariKidzController::class)->middleware(['auth']);
Route::post('jadwal_hikari_kidz', [App\Http\Controllers\JadwalHikariKidzController::class, 'store'])->name('jadwal_hikari_kidz.store')->middleware(['auth']);
Route::put('/jadwal_hikari_kidz/{id}/update', [App\Http\Controllers\JadwalHikariKidzController::class, 'update'])->name('jadwal_hikari_kidz.update')->middleware(['auth']);
Route::get('/delete-jadwal_hikari_kidz/{id}', [App\Http\Controllers\JadwalHikariKidzController::class, 'destroy'])->name('jadwal_hikari_kidz.destroy')->middleware(['auth']);

// detail jadwal hikari kidz
Route::get('jadwal_hikari_kidz/{id}/show', [App\Http\Controllers\JadwalHikariKidzController::class, 'show'])->name('jadwal_hikari_kidz.show')->middleware(['auth']);
Route::post('/jadwal-hikari-kidz/{id}/add-peserta', [App\Http\Controllers\JadwalHikariKidzController::class, 'addPeserta'])->name('jadwal_hikari_kidz.addPeserta')->middleware(['auth']);
Route::delete('/jadwal-hikari-kidz/{jadwalId}/remove-peserta/{pesertaId}', [App\Http\Controllers\JadwalHikariKidzController::class, 'removePeserta'])->name('jadwal_hikari_kidz.removePeserta')->middleware(['auth']);

// route ke master data jadwal makan daycare
Route::middleware(['auth'])->group(function () {
    Route::resource('jadwal_makan_daycare', App\Http\Controllers\JadwalMakanDaycareController::class);
    Route::post('/upload-jadwal_makan_daycare-excel', [App\Http\Controllers\JadwalMakanDaycareController::class, 'uploadExcel'])->name('jadwal_makan_daycare.upload');
    Route::get('/delete-jadwal_makan_daycare/{id}', [App\Http\Controllers\JadwalMakanDaycareController::class, 'destroy'])->name('jadwal_makan_daycare.destroy')->middleware(['auth']);
    Route::get('/jadwal_makan_daycare_user', [App\Http\Controllers\JadwalMakanDaycareController::class, 'userView'])->name('jadwal_makan_daycare_user');
});

// jadwal kursus peserta dan pengasuh
Route::middleware('auth')->post('/jadwal-hikari-kidz', [App\Http\Controllers\JadwalHikariKidzController::class, 'showByEmail'])->name('jadwal_hikari_kidz.peserta');
Route::post('jadwal-hikari-kidz-pengasuh', [App\Http\Controllers\JadwalHikariKidzController::class, 'showPengasuhSchedule']);


// route pembayaran 
use App\Http\Controllers\PembayaranController;

Route::get('/pembayaran', [PembayaranController::class, 'create'])->name('pembayaran.create');