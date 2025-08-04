<?php

use App\Http\Controllers\AbsensiHkcController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard2Controller;
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
use App\Http\Controllers\PaketHqController;
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
use App\Http\Controllers\SppBillCustomerController;
use App\Http\Controllers\SppBillGeneratorController;
use App\Http\Controllers\LaporanKegiatanController;
use App\Http\Controllers\LaporanPemasukanController;
use App\Http\Controllers\AdditionalBillController;
use App\Http\Controllers\MealBillGeneratorController;
use App\Http\Controllers\MealBillCustomerController;
use App\Http\Controllers\OvertimeBillCustomerController;

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
//
Route::get('/dashboard2', [Dashboard2Controller::class, 'index'])->name('dashboard')->middleware('auth');

// Notifikasi
Route::get('/notification/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');

// Route untuk validasi login
Route::post('/validasi_login', [LoginController::class, 'show']);

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');

    // Master Data Users
    Route::resource('users', UsersController::class);
    Route::put('/users/{id}/updateRole', [UsersController::class, 'updateRole'])->name('users.updateRole');
    Route::get('/delete-user/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    // Master Data COA
    Route::resource('coa', CoaController::class);
    Route::post('/upload-coa-excel', [CoaController::class, 'uploadExcel'])->name('coa.upload');

    // Master Data Jabatan
    Route::resource('jabatan', JabatanController::class);
    Route::post('/upload-jabatan-excel', [JabatanController::class, 'uploadExcel'])->name('jabatan.upload');

    // Master Data Staf
    Route::resource('staf', StafController::class);
    Route::post('/upload-staf-excel', [StafController::class, 'uploadExcel'])->name('staf.upload');

    // Master Data Teacher
    Route::resource('teacher', TeacherController::class);
    Route::post('/upload-teacher-excel', [TeacherController::class, 'uploadExcel'])->name('teacher.upload');

    // Master Data Peserta Kursus
    Route::resource('peserta_kursus', PesertaKursusController::class);
    Route::post('/upload-peserta_kursus-excel', [PesertaKursusController::class, 'uploadExcel'])->name('peserta_kursus.upload');

    // Master Data Kursus
    Route::resource('kursus', KursusController::class);
    Route::post('/upload-kursus-excel', [KursusController::class, 'uploadExcel'])->name('kursus.upload');

    // Detail Kursus
    Route::get('/kursus/detail', [DetailKursusController::class, 'show'])->name('kursus.detail');
    Route::post('/kursus/store', [DetailKursusController::class, 'store'])->name('kursus.store');
    Route::patch('ubah-status/{id}', [DetailKursusController::class, 'ubahStatus'])->name('ubah.status');

    // Jadwal Kursus
    Route::resource('jadwal_kursus', JadwalKursusController::class);
    Route::post('/jadwal-kursus/{id}/add-peserta', [JadwalKursusController::class, 'addPeserta'])->name('jadwal_kursus.addPeserta');
    Route::delete('/jadwal-kursus/{jadwalId}/remove-peserta/{pesertaId}', [JadwalKursusController::class, 'removePeserta'])->name('jadwal_kursus.removePeserta');
    Route::get('jadwal_kursus/{id}/show', [JadwalKursusController::class, 'show'])->name('jadwal_kursus.show'); // Detail jadwal
    Route::post('/jadwal-kursus', [JadwalKursusController::class, 'showByEmail'])->name('jadwal_kursus.peserta'); // Jadwal peserta
    Route::post('jadwal-kursus-teacher', [JadwalKursusController::class, 'showTeacherSchedule']); // Jadwal teacher

    // Presensi Staf
    Route::resource('presensi_staf', PresensiStafController::class);
    Route::get('/presensi-staf/export/pdf', [PresensiStafController::class, 'exportToPdf'])->name('presensi_staf.exportPdf');
    Route::post('/presensi/rfid', [PresensiStafController::class, 'autoPresensi']);

    // Presensi Teacher
    Route::resource('presensi_teacher', PresensiTeacherController::class);
    Route::get('/presensi-teacher/export/pdf', [PresensiTeacherController::class, 'exportToPdf'])->name('presensi_teacher.exportPdf');
    Route::post('riwayat_presensi-teacher', [PresensiTeacherController::class, 'showRiwayatPresensi'])->name('riwayat.presensi');
    Route::post('/presensi/rfid-teacher', [PresensiTeacherController::class, 'autoPresensi']);

    // Gaji Staf
    Route::get('gaji_staf', [GajiStafController::class, 'index'])->name('gaji_staf.index');
    Route::get('gaji_staf/form', [GajiStafController::class, 'showForm'])->name('gaji_staf.form');
    Route::post('gaji_staf/store', [GajiStafController::class, 'store'])->name('gaji_staf.store');
    Route::get('/gaji_staf/{id}/edit', [GajiStafController::class, 'edit'])->name('gaji_staf.edit');
    Route::put('/gaji_staf/{id}', [GajiStafController::class, 'update'])->name('gaji_staf.update');

    // Jurnal dan Buku Besar
    Route::get('jurnal/umum', [JurnalController::class,'jurnalumum'])->middleware(['auth']);
    Route::get('jurnal/viewdatajurnalumum/{periode}', [JurnalController::class,'viewdatajurnalumum']);
    Route::get('/jurnal/bukubesar', [JurnalController::class, 'bukubesar']);
    Route::get('/jurnal/viewdatabukubesar/{periode}/{kode_akun}', [JurnalController::class, 'viewdatabukubesar']);

    // Master Data Potongan Gaji
    Route::resource('potongan-gaji', PotonganGajiController::class);

    // Transaksi Purchase
    Route::get('purchase/tabel', [PurchaseController::class,'tabel']);
    Route::get('purchase/fetchpurchase', [PurchaseController::class,'fetchpurchase']);
    Route::get('purchase/fetchAll', [PurchaseController::class,'fetchAll']);
    Route::get('purchase/edit/{id}', [PurchaseController::class,'edit']);
    Route::get('purchase/destroy/{id}', [PurchaseController::class,'destroy']);
    Route::get('/purchase/filter', [PurchaseController::class, 'filterByDate']);
    Route::resource('purchase', PurchaseController::class);

    // Transaksi Pengeluaran
    Route::get('pengeluaran/tabel', [PengeluaranController::class,'tabel']);
    Route::get('pengeluaran/fetchpurchase', [PengeluaranController::class,'fetchpurchase']);
    Route::get('pengeluaran/fetchAll', [PengeluaranController::class,'fetchAll']);
    Route::get('pengeluaran/edit/{id}', [PengeluaranController::class,'edit']);
    Route::get('pengeluaran/destroy/{id}', [PengeluaranController::class,'destroy']);
    Route::get('/pengeluaran/filter', [PengeluaranController::class, 'filterByDate']);
    Route::resource('pengeluaran', PengeluaranController::class);

    // Laporan Gaji
    Route::get('laporan/slip-gaji/karyawan', [LaporanController::class, 'show'])->name('laporan.show');
    Route::post('laporan/slip-gaji/karyawan', [LaporanController::class, 'cekGaji'])->name('laporan.karyawan');

    // Grafik
    Route::get('grafik/viewPenjualanBlnBerjalan', [GrafikController::class,'viewBulanBerjalan']);

    // Calendar
    Route::get('fullcalendar', [FullcalendarController::class, 'index'])->name('fullcalendar');

    // Register Kidz Club (Admin/Manajemen)
    Route::get('/registerkidzclub/index', [RegistrationHikariKidzClubController::class, 'index'])->name('registerkidzclub.index');
    Route::get('/registerkidzclub', [RegistrationHikariKidzClubController::class, 'create'])->name('registerkidzclub.create');
    Route::post('/registerkidzclub', [RegistrationHikariKidzClubController::class, 'store'])->name('registerkidzclub.store');
    Route::get('/get-paket_hkc-by-member-kelas/{member}/{kelas}', [PaketHkcController::class, 'getByMemberAndKelas']);
    Route::get('/paket_hkc/{id}', [PaketHkcController::class, 'getPaketHkcById']);

    // Register Kidz Daycare (Admin/Manajemen)
    Route::get('/registerkidzdaycare/index', [RegistrationHikariKidzDaycareController::class, 'index'])->name('registerkidzdaycare.index');
    Route::get('/registerkidzdaycare', [RegistrationHikariKidzDaycareController::class, 'create'])->name('registerkidzdaycare.create');
    Route::post('/registerkidzdaycare', [RegistrationHikariKidzDaycareController::class, 'store'])->name('registerkidzdaycare.store');
    Route::get('/get-paket-by-tipe/{tipe}', [PaketController::class, 'getByTipe']);
    Route::get('/paket/{id}', [PaketController::class, 'getPaketById']);

    // Daftar HKC (Customer facing, tapi logicnya di controller)
    Route::get('/daftarhkc', [DaftarHkcController::class, 'create'])->name('daftar_hkc.index');
    Route::post('/daftarhkc', [DaftarHkcController::class, 'store'])->name('daftar_hkc.store');

    // Riwayat Pendaftaran (Customer facing)
    Route::get('/riwayatpendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('riwayat.pendaftaran');

    // Tagihan Pembayaran (Customer facing - daftar tagihan Pendaftaran Awal)
    Route::middleware(['auth'])->group(function () {
        Route::get('/tagihanpembayaran', [TagihanPembayaranController::class, 'index'])->name('tagihan.pembayaran');
    });


    // Master Data Paket (Daycare)
    Route::resource('paket', PaketController::class);
    Route::post('/upload-paket-excel', [PaketController::class, 'uploadExcel'])->name('paket.upload');

    // Master Data Paket HKC
    Route::resource('paket_hkc', PaketHkcController::class);
    Route::post('/upload-paket_hkc-excel', [PaketHkcController::class, 'uploadExcel'])->name('paket_hkc.upload');

    // Master Data Paket HQ
    Route::resource('paket_hq', PaketHqController::class);
    Route::post('/upload-paket_hq-excel', [PaketHqController::class, 'uploadExcel'])->name('paket_hq.upload');

    // Master Data Pengasuh
    Route::resource('pengasuh', PengasuhController::class);
    Route::post('/upload-pengasuh-excel', [PengasuhController::class, 'uploadExcel'])->name('pengasuh.upload');

    // Transaksi Peserta Hikari Kidz (Admin/Manajemen)
    Route::resource('peserta_hikari_kidz', PesertaHikariKidzController::class)->except(['destroy']);
    Route::post('/upload-peserta_hikari_kidz-excel', [PesertaHikariKidzController::class, 'uploadExcel'])->name('peserta_hikari_kidz.upload');
    Route::get('/ubah-status/{id}', [PesertaHikariKidzController::class, 'ubahStatus'])->name('peserta_hikari_kidz.ubahstatus');
    Route::get('/peserta_hikari_kidz/wa/{id}', [PesertaHikariKidzController::class, 'kirimPesanWhatsapp'])->name('peserta_hikari_kidz.kirimWa');
    Route::get('/peserta/download-vcard/{id_anak}', [PesertaHikariKidzController::class, 'generateParticipantVCard'])->name('peserta.vcard');
    Route::get('/peserta-hikari-kidz/terverifikasi', [PesertaHikariKidzController::class, 'verifikasi'])->name('peserta_hikari_kidz.verifikasi');
    Route::get('/peserta-hikari-kidz/ubah-status-siklus/{id}', [PesertaHikariKidzController::class, 'ubahStatusSiklus'])->name('peserta_hikari_kidz.ubahstatus_siklus');
    Route::post('/peserta-hikari-kidz/ubah-status-keaktifan/{id}', [PesertaHikariKidzController::class, 'ubahStatusKeaktifan'])->name('peserta_hikari_kidz.ubahstatus_keaktifan');

    // Master Data Hikari Kidz (Program/Kelas)
    Route::resource('hikari_kidz', HikariKidzController::class);
    Route::post('/upload-hikari_kidz-excel', [HikariKidzController::class, 'uploadExcel'])->name('hikari_kidz.upload');

    // Detail Hikari Kidz
    Route::get('/hikari_kidz/detail', [DetailHikariKidzController::class, 'show'])->name('hikari_kidz.detail');
    Route::post('/hikari_kidz/store', [DetailHikariKidzController::class, 'store'])->name('hikari_kidz.store');

    // Jadwal Hikari Kidz Daycare
    Route::resource('jadwal_hikari_kidz', JadwalHikariKidzController::class);
    Route::post('/upload-jadwal_hikari_kidz-excel', [JadwalHikariKidzController::class, 'uploadExcel'])->name('jadwal_hikari_kidz.upload');
    Route::get('jadwal_hikari_kidz/{id}/show', [JadwalHikariKidzController::class, 'show'])->name('jadwal_hikari_kidz.show');
    Route::post('/jadwal-hikari-kidz/{id}/add-peserta', [JadwalHikariKidzController::class, 'addPeserta'])->name('jadwal_hikari_kidz.addPeserta');
    Route::delete('/jadwal-hikari-kidz/{jadwalId}/remove-peserta/{pesertaId}', [JadwalHikariKidzController::class, 'removePeserta'])->name('jadwal_hikari_kidz.removePeserta');
    Route::post('/jadwal-hikari-kidz', [JadwalHikariKidzController::class, 'showByEmail'])->name('jadwal_hikari_kidz.peserta'); // Jadwal peserta
    Route::post('jadwal-hikari-kidz-pengasuh', [JadwalHikariKidzController::class, 'showPengasuhSchedule']); // Jadwal pengasuh
    Route::get('/jadwal_hikari_kidz_user', [JadwalHikariKidzController::class, 'userView'])->name('jadwal_hikari_kidz_user');

    // Jadwal Hikari Kidz Club
    Route::resource('jadwal_hkc', JadwalHkcController::class);
    Route::post('/upload-jadwal_hkc-excel', [JadwalHkcController::class, 'uploadExcel'])->name('jadwal_hkc.upload');
    Route::get('/jadwal_hkc_user', [JadwalHkcController::class, 'userView'])->name('jadwal_hkc_user');

    // Master Data Jadwal Makan Daycare
    Route::resource('jadwal_makan_daycare', JadwalMakanDaycareController::class);
    Route::post('/upload-jadwal_makan_daycare-excel', [JadwalMakanDaycareController::class, 'uploadExcel'])->name('jadwal_makan_daycare.upload');
    Route::get('/delete-jadwal_makan_daycare/{id}', [JadwalMakanDaycareController::class, 'destroy'])->name('jadwal_makan_daycare.destroy');
    Route::get('/jadwal_makan_daycare_user', [JadwalMakanDaycareController::class, 'userView'])->name('jadwal_makan_daycare_user');
    Route::post('/jadwal_makan_daycare/deleteByPeriode',[JadwalMakanDaycareController::class,'deleteByPeriode'])->name('jadwal_makan_daycare.deleteByPeriode');

    // Absensi Daycare
    Route::get('/absensi_daycare/store-jam-datang', [AbsensiDaycareController::class, 'createJamDatang']);
    Route::post('/absensi_daycare/store-jam-datang', [AbsensiDaycareController::class, 'storeJamDatang'])->name('absensi_daycare.store_jam_datang');
    Route::get('/absensi_daycare/store-jam-pulang', [AbsensiDaycareController::class, 'createJamPulang']);
    Route::post('/absensi_daycare/store-jam-pulang', [AbsensiDaycareController::class, 'storeJamPulang'])->name('absensi_daycare.store_jam_pulang');
    Route::get('/cek-jam-datang/{id}', [AbsensiDaycareController::class, 'cekJamDatang']);
    Route::get('/get-program-anak/{id}', [AbsensiDaycareController::class, 'getProgramAnak']);
    Route::get('/absensi_daycare/riwayat_absensi', [AbsensiDaycareController::class, 'riwayat_absensi'])->name('absensi_daycare.riwayat_absensi');
    Route::get('/absensi-daycare/riwayat', [AbsensiDaycareController::class, 'riwayat_absensi'])->name('absensi_daycare.riwayat');
    Route::get('/absensi_daycare/export/pdf', [AbsensiDaycareController::class, 'exportPdf'])->name('absensi_daycare.exportPdf');

    // Tema HKC
    Route::resource('tema_hkc', TemaHkcController::class);
    Route::get('delete-tema_hkc/{id}', [TemaHkcController::class, 'destroy'])->name('tema_hkc.delete');

    // Kegiatan Tambahan
    Route::resource('kegiatan_tambahan', KegiatanTambahanController::class);
    Route::post('/upload-kegiatan_tambahan-excel', [KegiatanTambahanController::class, 'upload'])->name('kegiatan_tambahan.upload');
    Route::patch('/kegiatan_tambahan/{kegiatanTambahan}/ubah-status', [KegiatanTambahanController::class, 'ubahStatus'])->name('kegiatan_tambahan.ubahstatus');
    // Route untuk pembayaran kegiatan tambahan (customer view)
    Route::get('/kegiatan-tambahan-pembayaran', [KegiatanTambahanController::class, 'userIndex'])->name('pembayaran_kegiatan_tambahan_user.index');
    Route::post('kegiatan-tambahan/{kegiatanTambahan}/upload-bukti', [KegiatanTambahanController::class, 'uploadBuktiPembayaran'])->name('kegiatan_tambahan.upload_bukti');

    // Laporan Kegiatan Daycare (HKD)
    Route::prefix('laporan-kegiatan-daycare')->group(function () {
        Route::get('/', [LaporanKegiatanController::class, 'index'])->name('laporan_kegiatan.daycare.index');
        Route::post('/', [LaporanKegiatanController::class, 'store'])->name('laporan_kegiatan.daycare.store');
        Route::put('/{laporanKegiatan}', [LaporanKegiatanController::class, 'update'])->name('laporan_kegiatan.daycare.update');
        Route::delete('/{id}', [LaporanKegiatanController::class, 'destroy'])->name('laporan_kegiatan.daycare.destroy');
        Route::get('/{laporanKegiatan}/get-data', [LaporanKegiatanController::class, 'edit'])->name('laporan_kegiatan.daycare.edit');
    });

    // Harian Kegiatan Cetak (HKC)
    Route::prefix('laporan-kegiatan-daycare')->group(function () {
        Route::get('/hkc', [LaporanKegiatanController::class, 'showHarianKegiatanCetak'])->name('laporan_kegiatan.hkc_list');
        Route::post('/hkc/store', [LaporanKegiatanController::class, 'storeLaporanHkc'])->name('laporan_kegiatan.store.hkc');
        Route::get('/hkc/{laporanKegiatan}/edit', [LaporanKegiatanController::class, 'editLaporanHkc'])->name('laporan_kegiatan.edit.hkc');
        Route::put('/hkc/{laporanKegiatan}', [LaporanKegiatanController::class, 'updateLaporanHkc'])->name('laporan_kegiatan.update.hkc');
        Route::delete('/hkc/{id}', [LaporanKegiatanController::class, 'destroyLaporanHkc'])->name('laporan_kegiatan.destroy.hkc');
    });

    // Laporan Pemasukan
    Route::get('/laporan-pemasukan', [LaporanPemasukanController::class, 'index'])->name('laporan.pemasukan.index');
    Route::get('/laporan-pemasukan/print', [LaporanPemasukanController::class, 'printReport'])->name('laporan.pemasukan.print');

    // Tagihan Tambahan (Overtime/Denda) - Admin
    Route::middleware(['auth'])->group(function () {
    Route::get('/admin/additional-bills', [AdditionalBillController::class, 'index'])->name('admin.additional_bills.index');
    Route::post('/admin/additional-bills/generate', [AdditionalBillController::class, 'generate'])->name('additional_bills.generate');
    });

   Route::prefix('admin/spp-bills-generator')->name('spp.generator.')->group(function () {
        Route::get('/', [SppBillGeneratorController::class, 'index'])->name('index');
        Route::post('/generate', [SppBillGeneratorController::class, 'generate'])->name('generate');
    });

    // Meal Bill Generator (Admin)
    Route::prefix('admin/meal-bills-generator')->name('meal.generator.')->group(function () {
        Route::get('/', [MealBillGeneratorController::class, 'index'])->name('index');
        Route::post('/generate', [MealBillGeneratorController::class, 'generate'])->name('generate');
    });

    // Absensi HKC
    Route::get('/absensi_hkc/riwayat', [AbsensiHkcController::class, 'history'])->name('absensi_hkc.riwayat');
    Route::get('/absensi_hkc/export/pdf', [AbsensiHkcController::class, 'exportPdf'])->name('absensi_hkc.exportPdf');
    Route::resource('absensi_hkc', AbsensiHkcController::class)->except(['show']);
});


// =========================================================================
// ROUTE UNTUK CUSTOMER/PENGGUNA (DI LUAR ADMIN)
// =========================================================================

// Authentication Routes
require __DIR__.'/auth.php'; // Ini akan berisi rute login, register, dll.

// Customer Specific Routes (contoh: untuk pengguna yang sudah login)
Route::middleware(['auth'])->group(function () {

    // route pembayaran (untuk customer)
Route::prefix('paymenthkc')->middleware('auth')->name('paymenthkc.')->group(function () {
    Route::get('/', [PaymentHkcController::class, 'index'])->name('index');
    Route::get('/create', [PaymentHkcController::class, 'create'])->name('create');
    Route::post('/', [PaymentHkcController::class, 'store'])->name('store');
    Route::get('/{payment}/receipt', [PaymentHkcController::class, 'receipt'])->name('receipt');
});

    // SPP Bulanan Routes (Customer Facing)
    Route::prefix('spp-bulanan')->name('spp.customer.')->group(function () {
        Route::get('/', [SppBillCustomerController::class, 'index'])->name('index'); // Daftar Tagihan SPP
        Route::get('/{tagihanId}/bayar', [SppBillCustomerController::class, 'bayar'])->name('bayar'); // Form Pembayaran SPP
        Route::post('/{tagihanId}/proses-pembayaran', [SppBillCustomerController::class, 'prosesPembayaran'])->name('proses_pembayaran'); // Proses SPP Payment
    });

    // Meal Bill Customer Routes
    Route::prefix('customer/meal-bills')->name('meal.customer.')->group(function () {
        Route::get('/', [MealBillCustomerController::class, 'index'])->name('index');
        Route::get('/{tagihanId}/bayar', [MealBillCustomerController::class, 'bayar'])->name('bayar');
        Route::post('/{tagihanId}/proses-pembayaran', [MealBillCustomerController::class, 'prosesPembayaran'])->name('proses_pembayaran');
    });

    // Overtime Bill Customer Routes
    Route::prefix('customer/overtime-bills')->name('overtime.customer.')->group(function () {
        Route::get('/', [OvertimeBillCustomerController::class, 'index'])->name('index');
        Route::get('/{tagihanId}/bayar', [OvertimeBillCustomerController::class, 'bayar'])->name('bayar');
        Route::post('/{tagihanId}/proses-pembayaran', [OvertimeBillCustomerController::class, 'prosesPembayaran'])->name('proses_pembayaran');
    });

    // Admin Payment Verification Routes (Ini seharusnya di dalam middleware admin)
    // Saya sarankan group ini dipindahkan ke dalam Route::middleware(['auth', 'can:access-admin-panel']) atau sejenisnya
    // Tergantung bagaimana Anda mengimplementasikan otorisasi admin.
    Route::prefix('admin/pembayaran')->name('admin.pembayaran.')->group(function () {
        Route::get('/', [PaymentController::class, 'adminIndex'])->name('index');
        Route::patch('/{payment}/approve', [PaymentController::class, 'approve'])->name('approve');
        Route::patch('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
    });
});