<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PesertaKursusController;
use App\Http\Controllers\KursusController;
use App\Http\Controllers\DetailKursusController;
use App\Http\Controllers\JadwalKursusController;
use App\Http\Controllers\PresensiStafController;
use App\Http\Controllers\PresensiTeacherController;
use App\Http\Controllers\GajiStafController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\PotonganGajiController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\FullcalendarController;
use App\Http\Controllers\RegistrationHikariKidzClubController;
use App\Http\Controllers\PaketHkcController;
use App\Http\Controllers\RegistrationHikariKidzDaycareController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\RegistrationHikariQuranController;
use App\Http\Controllers\PaketHqController;
use App\Http\Controllers\RegistrationProgramHkcwController;
use App\Http\Controllers\DaftarKursusController;
use App\Http\Controllers\RiwayatPendaftaranController;
use App\Http\Controllers\TagihanPembayaranController;
use App\Http\Controllers\PengasuhController;
use App\Http\Controllers\PesertaHikariKidzController;
use App\Http\Controllers\HikariKidzController;
use App\Http\Controllers\DetailHikariKidzController;
use App\Http\Controllers\JadwalHikariKidzController;
use App\Http\Controllers\JadwalHkcController;
use App\Http\Controllers\JadwalMakanDaycareController;
use App\Http\Controllers\AbsensiDaycareController;
use App\Http\Controllers\TemaHkcController;
use App\Http\Controllers\KegiatanTambahanController;
use App\Http\Controllers\PaymentController; 
use App\Http\Controllers\SppGeneratorController;
use App\Http\Controllers\SppBulananController;
use App\Http\Controllers\LaporanKegiatanController;
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
    return redirect('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth']);

// route untuk validasi login
Route::post('/validasi_login', [LoginController::class, 'show']);

// Profile
Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');

// route ke master data users
Route::resource('users', UsersController::class);
Route::put('/users/{id}/updateRole', [UsersController::class, 'updateRole'])->name('users.updateRole')->middleware(['auth']);
Route::get('/delete-user/{id}', [UsersController::class, 'destroy'])->name('users.destroy')->middleware(['auth']);

// route ke master data coa
Route::resource('coa', CoaController::class);
Route::post('coa', [CoaController::class, 'store'])->name('coa.store')->middleware(['auth']);
Route::post('/upload-coa-excel', [CoaController::class, 'uploadExcel'])->name('coa.upload');
Route::put('/coa/{id}/update', [CoaController::class, 'update'])->name('coa.update')->middleware(['auth']);
Route::get('/delete-coa/{id}', [CoaController::class, 'destroy'])->name('coa.destroy')->middleware(['auth']);

// route ke master data jabatan
Route::resource('jabatan', JabatanController::class); // Jika ini Resource, tidak perlu post/put/get manual
Route::post('jabatan', [JabatanController::class, 'store'])->name('jabatan.store')->middleware(['auth']); // Ini mungkin redundan jika di atas sudah resource
Route::post('/upload-jabatan-excel', [JabatanController::class, 'uploadExcel'])->name('jabatan.upload');
Route::put('/jabatan/{id}/update', [JabatanController::class, 'update'])->name('jabatan.update')->middleware(['auth']);
Route::get('/delete-jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy')->middleware(['auth']);

// route ke master data staf
Route::resource('staf', StafController::class);
Route::post('staf', [StafController::class, 'store'])->name('staf.store')->middleware(['auth']);
Route::post('/upload-staf-excel', [StafController::class, 'uploadExcel'])->name('staf.upload');
Route::put('/staf/{id}/update', [StafController::class, 'update'])->name('staf.update')->middleware(['auth']);
Route::get('/delete-staf/{id}', [StafController::class, 'destroy'])->name('staf.destroy')->middleware(['auth']);

// route ke master data teacher
Route::resource('teacher', TeacherController::class);
Route::post('teacher', [TeacherController::class, 'store'])->name('teacher.store')->middleware(['auth']);
Route::post('/upload-teacher-excel', [TeacherController::class, 'uploadExcel'])->name('teacher.upload');
Route::put('/teacher/{id}/update', [TeacherController::class, 'update'])->name('teacher.update')->middleware(['auth']);
Route::get('/delete-teacher/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy')->middleware(['auth']);

// route ke master data peserta kursus
Route::resource('peserta_kursus', PesertaKursusController::class);
Route::post('peserta_kursus', [PesertaKursusController::class, 'store'])->name('peserta_kursus.store')->middleware(['auth']);
Route::post('/upload-peserta_kursus-excel', [PesertaKursusController::class, 'uploadExcel'])->name('peserta_kursus.upload');
Route::put('/peserta_kursus/{id}/update', [PesertaKursusController::class, 'update'])->name('peserta_kursus.update')->middleware(['auth']);
Route::get('/delete-peserta_kursus/{id}', [PesertaKursusController::class, 'destroy'])->name('peserta_kursus.destroy')->middleware(['auth']);

// route ke master data kursus
Route::resource('kursus', KursusController::class);
Route::post('kursus', [KursusController::class, 'store'])->name('kursus.store')->middleware(['auth']);
Route::post('/upload-kursus-excel', [KursusController::class, 'uploadExcel'])->name('kursus.upload');
Route::put('/kursus/{id}/update', [KursusController::class, 'update'])->name('kursus.update')->middleware(['auth']);
Route::get('/delete-kursus/{id}', [KursusController::class, 'destroy'])->name('kursus.destroy')->middleware(['auth']);

// detail kursus
Route::get('/kursus/detail', [DetailKursusController::class, 'show'])->name('kursus.detail')->middleware(['auth']);
Route::post('/kursus/store', [DetailKursusController::class, 'store'])->name('kursus.store')->middleware(['auth']);
// Perhatikan: ubah.status ini mungkin duplikat jika ada di controller lain
Route::patch('ubah-status/{id}', [DetailKursusController::class, 'ubahStatus'])->name('ubah.status')->middleware(['auth']);

// jadwal kursus
Route::resource('jadwal_kursus', JadwalKursusController::class)->middleware(['auth']);
Route::post('jadwal_kursus', [JadwalKursusController::class, 'store'])->name('jadwal_kursus.store')->middleware(['auth']);
Route::put('/jadwal_kursus/{id}/update', [JadwalKursusController::class, 'update'])->name('jadwal_kursus.update')->middleware(['auth']);
Route::get('/delete-jadwal_kursus/{id}', [JadwalKursusController::class, 'destroy'])->name('jadwal_kursus.destroy')->middleware(['auth']);

// detail jadwal kursus
Route::get('jadwal_kursus/{id}/show', [JadwalKursusController::class, 'show'])->name('jadwal_kursus.show')->middleware(['auth']);
Route::post('/jadwal-kursus/{id}/add-peserta', [JadwalKursusController::class, 'addPeserta'])->name('jadwal_kursus.addPeserta')->middleware(['auth']);
Route::delete('/jadwal-kursus/{jadwalId}/remove-peserta/{pesertaId}', [JadwalKursusController::class, 'removePeserta'])->name('jadwal_kursus.removePeserta')->middleware(['auth']);

// jadwal kursus peserta dan teacher
Route::middleware('auth')->post('/jadwal-kursus', [JadwalKursusController::class, 'showByEmail'])->name('jadwal_kursus.peserta');
Route::post('jadwal-kursus-teacher', [JadwalKursusController::class, 'showTeacherSchedule']);

// presensi staf
Route::resource('presensi_staf', PresensiStafController::class)->middleware(['auth']);
Route::post('presensi_staf/store', [PresensiStafController::class, 'store'])->name('presensi_staf.store')->middleware(['auth']);
Route::get('/presensi-staf/export/pdf', [PresensiStafController::class, 'exportToPdf'])->name('presensi_staf.exportPdf')->middleware(['auth']);
Route::post('/presensi/rfid', [PresensiStafController::class, 'autoPresensi'])->middleware(['auth']);

// presensi teacher
Route::resource('presensi_teacher', PresensiTeacherController::class)->middleware(['auth']);
Route::post('presensi_teacher/store', [PresensiTeacherController::class, 'store'])->name('presensi_teacher.store')->middleware(['auth']);
Route::get('/presensi-teacher/export/pdf', [PresensiTeacherController::class, 'exportToPdf'])->name('presensi_teacher.exportPdf')->middleware(['auth']);
Route::post('riwayat_presensi-teacher', [PresensiTeacherController::class, 'showRiwayatPresensi'])->name('riwayat.presensi')->middleware(['auth']);
Route::post('/presensi/rfid-teacher', [PresensiTeacherController::class, 'autoPresensi'])->middleware(['auth']);

// gaji staf
Route::get('gaji_staf', [GajiStafController::class, 'index'])->name('gaji_staf.index');
Route::get('gaji_staf/form', [GajiStafController::class, 'showForm'])->name('gaji_staf.form');
Route::post('gaji_staf/store', [GajiStafController::class, 'store'])->name('gaji_staf.store');
Route::get('/gaji_staf/{id}/edit', [GajiStafController::class, 'edit'])->name('gaji_staf.edit');
Route::put('/gaji_staf/{id}', [GajiStafController::class, 'update'])->name('gaji_staf.update');

// jurnal dan buku besar
Route::get('jurnal/umum', [JurnalController::class,'jurnalumum'])->middleware(['auth']);
Route::get('jurnal/viewdatajurnalumum/{periode}', [JurnalController::class,'viewdatajurnalumum'])->middleware(['auth']);
Route::get('jurnal/bukubesar', [JurnalController::class,'bukubesar'])->middleware(['auth']);
Route::get('jurnal/viewdatabukubesar/{periode}/{akun}', [JurnalController::class,'viewdatabukubesar'])->middleware(['auth']);

////////////////////////////////////////////////////////////////////////////////////////////////////
// master data jabatan
// Route::resource('jabatan', JabatanController::class); // Ini duplikat dengan yang di atas, saya biarkan yang di atas.
Route::resource('potongan-gaji', PotonganGajiController::class);

//route ke transaksi Purchase
Route::get('purchase/tabel', [PurchaseController::class,'tabel'])->middleware(['auth']);
Route::get('purchase/fetchpurchase', [PurchaseController::class,'fetchpurchase'])->middleware(['auth']);
Route::get('purchase/fetchAll', [PurchaseController::class,'fetchAll'])->middleware(['auth']);
Route::get('purchase/edit/{id}', [PurchaseController::class,'edit'])->middleware(['auth']);
Route::get('purchase/destroy/{id}', [PurchaseController::class,'destroy'])->middleware(['auth']);
Route::get('/purchase/filter', [PurchaseController::class, 'filterByDate']); // Perbaiki cara panggil controller
Route::resource('purchase', PurchaseController::class)->middleware(['auth']);

//route ke transaksi Pengeluaran
Route::get('pengeluaran/tabel', [PengeluaranController::class,'tabel'])->middleware(['auth']);
Route::get('pengeluaran/fetchpurchase', [PengeluaranController::class,'fetchpurchase'])->middleware(['auth']);
Route::get('pengeluaran/fetchAll', [PengeluaranController::class,'fetchAll'])->middleware(['auth']);
Route::get('pengeluaran/edit/{id}', [PengeluaranController::class,'edit'])->middleware(['auth']);
Route::get('pengeluaran/destroy/{id}', [PengeluaranController::class,'destroy'])->middleware(['auth']);
Route::get('/pengeluaran/filter', [PengeluaranController::class, 'filterByDate']); // Perbaiki cara panggil controller
Route::resource('pengeluaran', PengeluaranController::class)->middleware(['auth']);

// laporan gaji
Route::get('laporan/slip-gaji/karyawan', [LaporanController::class, 'show'])->name('laporan.show');
Route::post('laporan/slip-gaji/karyawan', [LaporanController::class, 'cekGaji'])->name('laporan.karyawan');

// grafik
Route::get('grafik/viewPenjualanBlnBerjalan', [GrafikController::class,'viewBulanBerjalan'])->middleware(['auth']);

// calendar
Route::get('fullcalendar', [FullcalendarController::class, 'index'])->name('fullcalendar');
require __DIR__.'/auth.php';

//registerkidzclub
Route::get('/registerkidzclub/index', [RegistrationHikariKidzClubController::class, 'index'])->name('registerkidzclub.index'); // Untuk melihat daftar
Route::get('/registerkidzclub', [RegistrationHikariKidzClubController::class, 'create'])->name('registerkidzclub.create'); // Untuk membuka form
Route::post('/registerkidzclub', [RegistrationHikariKidzClubController::class, 'store'])->name('registerkidzclub.store');
Route::get('/get-paket_hkc-by-member-kelas/{member}/{kelas}', [PaketHkcController::class, 'getByMemberAndKelas']);
Route::get('/paket_hkc/{id}', [PaketHkcController::class, 'getPaketHkcById']);

//registerkidzdaycare
Route::get('/registerkidzdaycare/index', [RegistrationHikariKidzDaycareController::class, 'index'])->name('registerkidzdaycare.index');
Route::get('/registerkidzdaycare', [RegistrationHikariKidzDaycareController::class, 'create'])->name('registerkidzdaycare.create');
Route::post('/registerkidzdaycare', [RegistrationHikariKidzDaycareController::class, 'store'])->name('registerkidzdaycare.store');
Route::get('/get-paket-by-tipe/{tipe}', [PaketController::class, 'getByTipe']);
Route::get('/paket/{id}', [PaketController::class, 'getPaketById']);

//register hikari quran
Route::get('/registerquran', [RegistrationHikariQuranController::class, 'create'])->name('registerquran.create');
Route::post('/registerquran', [RegistrationHikariQuranController::class, 'store'])->name('registerquran.store');
Route::get('/get-paket_hq-by-kelas/{kelas}', [PaketHqController::class, 'getByKelas']);
Route::get('/paket_hq/{id}', [PaketHqController::class, 'getPaketHqById']);

//register program hkcw
Route::get('/registerprogramhkcw', [RegistrationProgramHkcwController::class, 'create'])->name('registerprogramhkcw.create');
Route::post('/registerprogramhkcw', [RegistrationProgramHkcwController::class, 'store'])->name('registerprogramhkcw.store');

//daftar kursus
Route::get('/daftarkursus', [DaftarKursusController::class, 'create'])->name('daftarkursus.index');
Route::post('/daftarkursus', [DaftarKursusController::class, 'store'])->name('daftarkursus.store');

//riwayatpendaftaran
Route::get('/riwayatpendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('riwayat.pendaftaran');

//tagihanpembayaran
Route::get('/tagihanpembayaran', [TagihanPembayaranController::class, 'index'])->name('tagihan.pembayaran');

// route ke master data paket
Route::resource('paket', PaketController::class);
Route::post('paket', [PaketController::class, 'store'])->name('paket.store')->middleware(['auth']);
Route::post('/upload-paket-excel', [PaketController::class, 'uploadExcel'])->name('paket.upload');
Route::put('/paket/{id}/update', [PaketController::class, 'update'])->name('paket.update')->middleware(['auth']);
Route::get('/delete-paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy')->middleware(['auth']);

// route ke master data paket hkc
Route::resource('paket_hkc', PaketHkcController::class);
Route::post('paket_hkc', [PaketHkcController::class, 'store'])->name('paket_hkc.store')->middleware(['auth']);
Route::post('/upload-paket_hkc-excel', [PaketHkcController::class, 'uploadExcel'])->name('paket_hkc.upload');
Route::put('/paket_hkc/{id}/update', [PaketHkcController::class, 'update'])->name('paket_hkc.update')->middleware(['auth']);
Route::get('/delete-paket_hkc/{id}', [PaketHkcController::class, 'destroy'])->name('paket_hkc.destroy')->middleware(['auth']);

// route ke master data paket hq
Route::resource('paket_hq', PaketHqController::class);
Route::post('paket_hq', [PaketHqController::class, 'store'])->name('paket_hq.store')->middleware(['auth']);
Route::post('/upload-paket_hq-excel', [PaketHqController::class, 'uploadExcel'])->name('paket_hq.upload');
Route::put('/paket_hq/{id}/update', [PaketHqController::class, 'update'])->name('paket_hq.update')->middleware(['auth']);
Route::get('/delete-paket_hq/{id}', [PaketHqController::class, 'destroy'])->name('paket_hq.destroy')->middleware(['auth']);

// route ke master data pengasuh
Route::resource('pengasuh', PengasuhController::class);
Route::post('pengasuh', [PengasuhController::class, 'store'])->name('pengasuh.store')->middleware(['auth']);
Route::post('/upload-pengasuh-excel', [PengasuhController::class, 'uploadExcel'])->name('pengasuh.upload');
Route::put('/pengasuh/{id}/update', [PengasuhController::class, 'update'])->name('pengasuh.update')->middleware(['auth']);
Route::get('/delete-pengasuh/{id}', [PengasuhController::class, 'destroy'])->name('pengasuh.destroy')->middleware(['auth']);

// route ke transaksi peserta hikari kidz
Route::resource('peserta_hikari_kidz', PesertaHikariKidzController::class)->except(['destroy'])->middleware(['auth']);
Route::post('/upload-peserta_hikari_kidz-excel', [PesertaHikariKidzController::class, 'uploadExcel'])->name('peserta_hikari_kidz.upload');
Route::get('/ubah-status/{id}', [PesertaHikariKidzController::class, 'ubahStatus'])->name('peserta_hikari_kidz.ubahstatus'); // Ini untuk status verifikasi peserta?
Route::get('/peserta_hikari_kidz/wa/{id}', [PesertaHikariKidzController::class, 'kirimPesanWhatsapp'])->name('peserta_hikari_kidz.kirimWa');
Route::get('/peserta/download-vcard/{id_anak}', [PesertaHikariKidzController::class, 'generateParticipantVCard'])->name('peserta.vcard');
Route::get('/peserta-hikari-kidz/terverifikasi', [PesertaHikariKidzController::class, 'verifikasi'])->name('peserta_hikari_kidz.verifikasi');
Route::get('/peserta-hikari-kidz/ubah-status-siklus/{id}', [PesertaHikariKidzController::class, 'ubahStatusSiklus'])->name('peserta_hikari_kidz.ubahstatus_siklus');
Route::post('/peserta-hikari-kidz/ubah-status-keaktifan/{id}', [PesertaHikariKidzController::class, 'ubahStatusKeaktifan'])->name('peserta_hikari_kidz.ubahstatus_keaktifan');

// route ke master data hikari kidz
Route::resource('hikari_kidz', HikariKidzController::class);
Route::post('hikari_kidz', [HikariKidzController::class, 'store'])->name('hikari_kidz.store')->middleware(['auth']);
Route::post('/upload-hikari_kidz-excel', [HikariKidzController::class, 'uploadExcel'])->name('hikari_kidz.upload');
Route::put('/hikari_kidz/{id}/update', [HikariKidzController::class, 'update'])->name('hikari_kidz.update')->middleware(['auth']);
Route::get('/delete-hikari_kidz/{id}', [HikariKidzController::class, 'destroy'])->name('hikari_kidz.destroy')->middleware(['auth']);

// detail hikari kidz
Route::get('/hikari_kidz/detail', [DetailHikariKidzController::class, 'show'])->name('hikari_kidz.detail')->middleware(['auth']);
Route::post('/hikari_kidz/store', [DetailHikariKidzController::class, 'store'])->name('hikari_kidz.store')->middleware(['auth']);
// Perhatikan: ubah.status ini mungkin duplikat jika ada di controller lain
// Jika ini adalah status detail hikari kidz, pastikan nama rutenya spesifik
// Route::patch('ubah-status/{id}', [DetailHikariKidzController::class, 'ubahStatus'])->name('ubah.status')->middleware(['auth']); // Duplikat?

// jadwal hikari kidz daycare
Route::resource('jadwal_hikari_kidz', JadwalHikariKidzController::class);
Route::post('jadwal_hikari_kidz', [JadwalHikariKidzController::class, 'store'])->name('jadwal_hikari_kidz.store')->middleware(['auth']);
Route::post('/upload-jadwal_hikari_kidz-excel', [JadwalHikariKidzController::class, 'uploadExcel'])->name('jadwal_hikari_kidz.upload');
Route::put('/jadwal_hikari_kidz/{id}/update', [JadwalHikariKidzController::class, 'update'])->name('jadwal_hikari_kidz.update')->middleware(['auth']);
Route::get('/delete-jadwal_hikari_kidz/{id}', [JadwalHikariKidzController::class, 'destroy'])->name('jadwal_hikari_kidz.destroy')->middleware(['auth']);

// jadwal hikari kidz
Route::resource('jadwal_hkc', JadwalHkcController::class);
Route::post('jadwal_hkc', [JadwalHkcController::class, 'store'])->name('jadwal_hkc.store')->middleware(['auth']);
Route::post('/upload-jadwal_hkc-excel', [JadwalHkcController::class, 'uploadExcel'])->name('jadwal_hkc.upload');
Route::put('/jadwal_hkc/{id}/update', [JadwalHkcController::class, 'update'])->name('jadwal_hkc.update')->middleware(['auth']);
Route::get('/delete-jadwal_hkc/{id}', [JadwalHkcController::class, 'destroy'])->name('jadwal_hkc.destroy')->middleware(['auth']);
Route::get('/jadwal_hkc_user', [JadwalHkcController::class, 'userView'])->name('jadwal_hkc_user');

// detail jadwal hikari kidz (daycare)
Route::get('jadwal_hikari_kidz/{id}/show', [JadwalHikariKidzController::class, 'show'])->name('jadwal_hikari_kidz.show')->middleware(['auth']);
Route::post('/jadwal-hikari-kidz/{id}/add-peserta', [JadwalHikariKidzController::class, 'addPeserta'])->name('jadwal_hikari_kidz.addPeserta')->middleware(['auth']);
Route::delete('/jadwal-hikari-kidz/{jadwalId}/remove-peserta/{pesertaId}', [JadwalHikariKidzController::class, 'removePeserta'])->name('jadwal_hikari_kidz.removePeserta')->middleware(['auth']);

// route ke master data jadwal makan daycare
Route::middleware(['auth'])->group(function () {
    Route::resource('jadwal_makan_daycare', JadwalMakanDaycareController::class);
    Route::post('/upload-jadwal_makan_daycare-excel', [JadwalMakanDaycareController::class, 'uploadExcel'])->name('jadwal_makan_daycare.upload');
    Route::get('/delete-jadwal_makan_daycare/{id}', [JadwalMakanDaycareController::class, 'destroy'])->name('jadwal_makan_daycare.destroy')->middleware(['auth']);
    Route::get('/jadwal_makan_daycare_user', [JadwalMakanDaycareController::class, 'userView'])->name('jadwal_makan_daycare_user');
    Route::post('/jadwal_makan_daycare/deleteByPeriode',[JadwalMakanDaycareController::class,'deleteByPeriode'])->name('jadwal_makan_daycare.deleteByPeriode');
});

// jadwal kursus peserta dan pengasuh (ini sepertinya duplikat dengan jadwal_kursus.peserta)
Route::middleware('auth')->post('/jadwal-hikari-kidz', [JadwalHikariKidzController::class, 'showByEmail'])->name('jadwal_hikari_kidz.peserta');
Route::post('jadwal-hikari-kidz-pengasuh', [JadwalHikariKidzController::class, 'showPengasuhSchedule']);

// route pembayaran (untuk customer)
Route::prefix('payment')->middleware('auth')->name('payment.')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::get('/create', [PaymentController::class, 'create'])->name('create');
    Route::post('/', [PaymentController::class, 'store'])->name('store');
});


// route absensi daycare
Route::get('/absensi_daycare/store-jam-datang', [AbsensiDaycareController::class, 'createJamDatang']);
Route::post('/absensi_daycare/store-jam-datang', [AbsensiDaycareController::class, 'storeJamDatang'])->name('absensi_daycare.store_jam_datang');
Route::get('/absensi_daycare/store-jam-pulang', [AbsensiDaycareController::class, 'createJamPulang']);
Route::post('/absensi_daycare/store-jam-pulang', [AbsensiDaycareController::class, 'storeJamPulang'])->name('absensi_daycare.store_jam_pulang');
Route::get('/cek-jam-datang/{id}', [AbsensiDaycareController::class, 'cekJamDatang']);
Route::get('/get-program-anak/{id}', [AbsensiDaycareController::class, 'getProgramAnak']);
Route::get('/absensi_daycare/riwayat_absensi', [AbsensiDaycareController::class, 'riwayat_absensi'])->name('absensi_daycare.riwayat_absensi');
Route::get('/absensi-daycare/riwayat', [AbsensiDaycareController::class, 'riwayat_absensi'])->name('absensi_daycare.riwayat');

// route tema hkc
Route::resource('tema_hkc', TemaHkcController::class); // untuk tema bulanan
Route::get('delete-tema_hkc/{id}', [TemaHkcController::class, 'destroy'])->name('tema_hkc.delete');

// route ke master data kegiatan tambahan
Route::middleware(['auth'])->group(function () {
    Route::resource('kegiatan_tambahan', KegiatanTambahanController::class);
    Route::post('/upload-kegiatan_tambahan-excel', [KegiatanTambahanController::class, 'upload'])->name('kegiatan_tambahan.upload');
    Route::patch('/kegiatan_tambahan/{kegiatanTambahan}/ubah-status', [KegiatanTambahanController::class, 'ubahStatus'])->name('kegiatan_tambahan.ubahstatus');
});

//Verifikasi pembayaran (untuk admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () { // Grup rute admin dengan prefix 'admin' dan nama 'admin.'
    Route::get('/pembayaran', [PaymentController::class, 'adminIndex'])->name('pembayaran.index'); // Daftar verifikasi
    Route::patch('/pembayaran/{payment}/approve', [PaymentController::class, 'approve'])->name('pembayaran.approve');
});

// Route untuk tagihan kegiatan tambahan
Route::middleware(['auth'])->group(function () {
    Route::get('/kegiatan-tambahan-pembayaran', [KegiatanTambahanController::class, 'userIndex'])->name('pembayaran_kegiatan_tambahan_user.index');
    Route::post('kegiatan-tambahan/{kegiatanTambahan}/upload-bukti', [KegiatanTambahanController::class, 'uploadBuktiPembayaran'])->name('kegiatan_tambahan.upload_bukti');
});
Route::resource('kegiatan_tambahan', KegiatanTambahanController::class);
Route::post('kegiatan_tambahan/{kegiatanTambahan}/ubah-status', [KegiatanTambahanController::class, 'ubahStatus'])->name('kegiatan_tambahan.ubah_status');
Route::post('kegiatan_tambahan/upload', [KegiatanTambahanController::class, 'upload'])->name('kegiatan_tambahan.upload');


    Route::get('/pembayaran', [PaymentController::class, 'adminIndex'])->name('pembayaran.index');
    Route::patch('/pembayaran/{payment}/approve', [PaymentController::class, 'approve'])->name('pembayaran.approve');

Route::middleware(['auth'])->group(function () { // Anda bisa tambahkan middleware 'role:admin' jika perlu
    
    // URL: /spp-generator
    Route::get('/spp-generator', [SppGeneratorController::class, 'index'])->name('spp.generator.index');
    
    // URL: /spp-generator/generate
    Route::post('/spp-generator/generate', [SppGeneratorController::class, 'generate'])->name('spp.generator.generate');
    
    // URL: /spp-generator/generate-all
    Route::post('/spp-generator/generate-all', [SppGeneratorController::class, 'generateAll'])->name('spp.generator.generate_all');

});
Route::middleware(['auth'])->group(function () {

    Route::get('/spp-bulanan', [SppBulananController::class, 'index'])->name('spp.bulanan.index');
    Route::get('/spp-bulanan/{tagihanId}/bayar', [SppBulananController::class, 'bayar'])->name('spp.bulanan.bayar');
    Route::post('/spp-bulanan/{tagihanId}/proses', [SppBulananController::class, 'prosesPembayaran'])->name('spp.bulanan.proses');
});
// Route untuk Laporan Kegiatan Daycare (HKD)
Route::prefix('laporan-kegiatan-daycare')->group(function () {
    Route::get('/', [LaporanKegiatanController::class, 'index'])->name('laporan_kegiatan.daycare.index');
    Route::post('/', [LaporanKegiatanController::class, 'store'])->name('laporan_kegiatan.daycare.store');
    Route::put('/{laporanKegiatan}', [LaporanKegiatanController::class, 'update'])->name('laporan_kegiatan.daycare.update');
    Route::delete('/{id}', [LaporanKegiatanController::class, 'destroy'])->name('laporan_kegiatan.daycare.destroy');

    // AJAX get data untuk modal edit
    Route::get('/{laporanKegiatan}/get-data', [LaporanKegiatanController::class, 'edit'])
        ->name('laporan_kegiatan.daycare.edit');
});

// --- Rute untuk Laporan HKC (Mengikuti pola HKD) ---
Route::prefix('laporan-kegiatan')->group(function () {

    // Rute utama untuk menampilkan halaman HKC (termasuk tombol "Tambah" dan daftar laporan)
    // Akan memuat data laporan HKC dan data peserta HKC untuk form modal
    Route::get('/hkc', [LaporanKegiatanController::class, 'showHarianKegiatanCetak'])->name('laporan_kegiatan.hkc_list');

    // Rute untuk memproses data dari form CREATE HKC (dari modal)
    Route::post('/store-hkc', [LaporanKegiatanController::class, 'storeLaporanHkc'])->name('laporan_kegiatan.store.hkc');

    // Rute untuk mendapatkan data laporan HKC (untuk mengisi modal edit via AJAX)
    Route::get('/{laporanKegiatan}/edit-hkc-data', [LaporanKegiatanController::class, 'editLaporanHkc'])->name('laporan_kegiatan.edit.hkc_data');

    // Rute untuk memperbarui laporan HKC (dari modal edit)
    Route::put('/{laporanKegiatan}/update-hkc', [LaporanKegiatanController::class, 'updateLaporanHkc'])->name('laporan_kegiatan.update.hkc');

    // Rute untuk menghapus laporan HKC
    Route::delete('/{id}/destroy-hkc', [LaporanKegiatanController::class, 'destroyLaporanHkc'])->name('laporan_kegiatan.destroy.hkc');

    // Rute untuk melihat laporan HKC spesifik per anak (jika Anda masih ingin fitur ini)
    // Catatan: Jika diakses, ini juga akan menggunakan view hkc.blade.php
    Route::get('/hkc/{id_anak}', [LaporanKegiatanController::class, 'showHarianKegiatanCetak'])->name('laporan_kegiatan.hkc_per_anak');

    // Rute untuk dashboard kelas (metode showLaporanHKC Anda) - tetap ada jika dibutuhkan untuk tampilan lain.
    // Jika ini diakses, modal create/edit tidak akan berfungsi karena data pesertaHKC tidak di-compact.
    Route::get('/hkc-dashboard-kelas', [LaporanKegiatanController::class, 'showLaporanHKC'])->name('laporan_kegiatan.hkc_dashboard_kelas');
});