<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
// Asumsi model-model ini sudah ada dan diimpor dengan benar
use App\Models\PaketHkc;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\PaymentComponent;

class PaymentController extends Controller
{
    /**
     * Tampilkan daftar riwayat pembayaran (untuk customer/frontend).
     */
    public function index()
    {
        // Mengambil semua data pembayaran terbaru dulu (DESC)
        // agar logika nomor urut terbalik di Blade menghasilkan 1 untuk yang paling baru
        $payments = Payment::latest()->with(['components'])->get();

        return view('payment.index', compact('payments'));
    }

    /**
     * Menampilkan form untuk membuat pembayaran baru.
     * Menerima registration_id dan registration_type dari query parameter.
     */
    public function create(Request $request)
    {
        $registration_id = $request->query('registration_id');
        $registration_type = $request->query('registration_type');

        $peserta = null;
        $paket = null;

        if (empty($registration_id) || empty($registration_type)) {
            // Redirect jika parameter tidak lengkap
            return redirect()->route('daftar_program_hikari_kidz.index')->with('error', 'ID pendaftaran atau tipe tidak ditemukan.');
        }

        // Mendapatkan data peserta dan paket berdasarkan tipe pendaftaran
        switch ($registration_type) {
            case 'Hikari Kidz Club':
                $peserta = RegistrationHikariKidzClub::findOrFail($registration_id);
                // Asumsi ada model PaketHkc dan relasi yang sesuai
                $paket = PaketHkc::where('member', $peserta->member)
                                 ->where('kelas', $peserta->kelas)
                                 ->first();
                if (!$paket) {
                    abort(404, 'Paket Hikari Kidz Club tidak ditemukan untuk peserta ini.');
                }
                break;

            case 'Hikari Kidz Daycare':
                $peserta = RegistrationHikariKidzDaycare::findOrFail($registration_id);
                // Asumsi relasi 'paket' ada di model RegistrationHikariKidzDaycare
                $paket = $peserta->paket;
                if (!$paket) {
                    abort(404, 'Paket Hikari Kidz Daycare tidak ditemukan untuk peserta ini.');
                }
                break;

            case 'Hikari Quran':
                $peserta = RegistrationHikariQuran::findOrFail($registration_id);
                // Asumsi relasi 'pakethq' ada di model RegistrationHikariQuran
                $paket = $peserta->pakethq;
                if (!$paket) {
                    abort(404, 'Paket Hikari Quran tidak ditemukan untuk peserta ini.');
                }
                break;

            default:
                abort(404, 'Tipe pendaftaran tidak valid.');
        }

        return view('payment.create', compact('registration_id', 'registration_type', 'peserta', 'paket'));
    }

    /**
     * Menyimpan data pembayaran baru yang dikirim dari form.
     */
    public function store(Request $request)
    {
        // 1. Validasi data input dari form
        $validatedData = $request->validate([
            'registration_id' => 'required|integer',
            'registration_type' => 'required|string',
            'komponen' => 'nullable|array', // Komponen bisa kosong jika semua bernilai 0
            'komponen.*' => 'string',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'cicilan_uang_pangkal' => 'nullable|integer|min:0|max:3',
            'uang_pangkal_nominal_full' => 'nullable|numeric',
        ]);

        // Pastikan 'komponen' selalu array, meskipun null dari input
        $validatedData['komponen'] = $validatedData['komponen'] ?? [];

        // 2. Ambil data paket dan nominal komponen yang sebenarnya dari database/logika backend
        $nominalKomponen = [];
        $peserta = null;
        $paket = null;

        switch ($validatedData['registration_type']) {
            case 'Hikari Kidz Club':
                $peserta = RegistrationHikariKidzClub::findOrFail($validatedData['registration_id']);
                $paket = PaketHkc::where('member', $peserta->member)
                                 ->where('kelas', $peserta->kelas)
                                 ->first();
                if (!$paket) {
                    return back()->withErrors(['paket' => 'Paket tidak ditemukan untuk peserta ini.'])->withInput();
                }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                    'Uang Sarana' => $paket->u_sarana ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                break;

            case 'Hikari Kidz Daycare':
                $peserta = RegistrationHikariKidzDaycare::findOrFail($validatedData['registration_id']);
                $paket = $peserta->paket;
                if (!$paket) {
                    return back()->withErrors(['paket' => 'Paket tidak ditemukan untuk peserta ini.'])->withInput();
                }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Pangkal' => $paket->u_pangkal ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                    'Uang Makan' => $paket->u_makan ?? 0,
                    "Uang Kegiatan" => $paket->u_kegiatan ?? 0,
                ];
                break;

            case 'Hikari Quran':
                $peserta = RegistrationHikariQuran::findOrFail($validatedData['registration_id']);
                $paket = $peserta->pakethq;
                if (!$paket) {
                    return back()->withErrors(['paket' => 'Paket tidak ditemukan untuk peserta ini.'])->withInput();
                }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Modul' => $paket->u_modul ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                break;

            default:
                abort(404, 'Tipe pendaftaran tidak valid.');
        }

        // 3. Tentukan komponen wajib yang nominalnya > 0 (ini yang harus ada di input form)
        $requiredComponentsNominalGreaterThanZero = [];
        foreach ($nominalKomponen as $compName => $compValue) {
            // Hanya tambahkan ke daftar yang 'dibutuhkan' jika nominalnya > 0
            // dan komponen tersebut memang bagian dari mandatory components awal
            if ($compValue > 0 && in_array($compName, $this->getMandatoryComponentsList($validatedData['registration_type']))) {
                $requiredComponentsNominalGreaterThanZero[] = $compName;
            }
        }

        // 4. Verifikasi bahwa komponen wajib yang nominalnya > 0 memang ada di input form
        foreach ($requiredComponentsNominalGreaterThanZero as $comp) {
            if (!in_array($comp, $validatedData['komponen'])) {
                // Ini akan memicu error jika ada ketidaksesuaian antara frontend dan backend (misalnya manipulasi form)
                return back()->withErrors(['komponen' => 'Komponen wajib tidak lengkap: ' . $comp . '. Harap coba lagi.'])->withInput();
            }
        }

        // 5. Proses upload bukti transfer
        $file = $request->file('bukti_transfer');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/buktipembayaran'), $filename);

        // 6. Hitung total pembayaran dan siapkan komponen untuk disimpan
        $total = 0;
        $componentsToSave = [];

        // Tambahkan komponen yang dikirim dari form (ini hanya yang nominalnya > 0 dari frontend)
        foreach ($validatedData['komponen'] as $komponenNama) {
            $jumlahKomponen = $nominalKomponen[$komponenNama] ?? 0; // Ambil nominal dari data paket
            $total += (float) $jumlahKomponen;
            $componentsToSave[] = [
                'komponen' => $komponenNama,
                'jumlah' => (float) $jumlahKomponen
            ];
        }

        // 7. Logika untuk Uang Pangkal (khusus Daycare dan jika opsi cicilan dipilih)
        if ($validatedData['registration_type'] === 'Hikari Kidz Daycare' &&
            isset($validatedData['cicilan_uang_pangkal']) && $validatedData['cicilan_uang_pangkal'] > 0 &&
            isset($validatedData['uang_pangkal_nominal_full']) && $validatedData['uang_pangkal_nominal_full'] > 0
        ) {
            $cicilan = (int) $validatedData['cicilan_uang_pangkal'];
            $uangPangkalFull = (float) $validatedData['uang_pangkal_nominal_full'];
            $jumlahCicilanSaatIni = ceil($uangPangkalFull / $cicilan); // Pembulatan ke atas

            $total += (float) $jumlahCicilanSaatIni;

            $componentsToSave[] = [
                'komponen' => 'Uang Pangkal (Cicilan ' . $cicilan . 'x)',
                'jumlah' => (float) $jumlahCicilanSaatIni
            ];
        }

        // 8. Tambahkan juga komponen wajib yang nominalnya 0 ke daftar componentsToSave
        // Ini untuk memastikan riwayat pembayaran lengkap, meskipun komponen tersebut gratis.
        $currentSavedComponents = array_column($componentsToSave, 'komponen');
        $initialMandatoryComponents = $this->getMandatoryComponentsList($validatedData['registration_type']);

        foreach ($initialMandatoryComponents as $compName) {
            // Jika komponen wajib ini nominalnya 0 DAN belum ada di daftar yang akan disimpan
            if (($nominalKomponen[$compName] ?? 0) === 0 && !in_array($compName, $currentSavedComponents)) {
                $componentsToSave[] = [
                    'komponen' => $compName,
                    'jumlah' => 0.0 // Simpan dengan nominal 0
                ];
            }
        }

        // 9. Simpan ke tabel Payments
        $payment = Payment::create([
            'registration_id' => $validatedData['registration_id'],
            'registration_type' => $validatedData['registration_type'],
            'jumlah' => $total, // Total pembayaran final
            'bukti_transfer' => $filename,
            'status' => 'menunggu_verifikasi', // Status awal pembayaran
        ]);

        // 10. Simpan detail komponen ke tabel PaymentComponents
        foreach ($componentsToSave as $compData) {
            PaymentComponent::create([
                'payment_id' => $payment->id,
                'komponen' => $compData['komponen'],
                'jumlah' => $compData['jumlah'],
            ]);
        }

        return redirect()->route('payment.index')->with('success', 'Pembayaran berhasil disimpan dan menunggu verifikasi admin.');
    }

    /**
     * Helper function untuk mendapatkan daftar komponen wajib berdasarkan tipe pendaftaran.
     */
    private function getMandatoryComponentsList($registrationType)
    {
        switch ($registrationType) {
            case 'Hikari Kidz Daycare':
                return ['Uang Pendaftaran', 'SPP Bulanan', 'Uang Makan', 'Uang Kegiatan'];
            case 'Hikari Kidz Club':
                return ['Uang Pendaftaran', 'Uang Perlengkapan', 'Uang Sarana', 'SPP Bulanan'];
            case 'Hikari Quran':
                return ['Uang Pendaftaran', 'Uang Modul', 'SPP Bulanan'];
            default:
                return [];
        }
    }

    /**
     * Tampilkan daftar pembayaran yang perlu disetujui oleh Admin.
     */
    public function adminIndex()
    {
        // Hanya ambil pembayaran dengan status 'menunggu_verifikasi'
        // dan eager load komponennya. Urutkan yang terbaru di atas.
        $payments = Payment::where('status', 'menunggu_verifikasi')
                           ->with(['components'])
                           ->latest()
                           ->get();

        // Mengubah path view di sini agar sesuai dengan struktur direktori Anda
        // Laravel akan mencari resources/views/pembayaran_admin/index.blade.php
        return view('pembayaran_admin.index', compact('payments'));
    }

    /**
     * Mengubah status pembayaran menjadi 'terverifikasi' oleh Admin.
     */
    public function approve(Request $request, Payment $payment)
    {
        // Pastikan pembayaran memang dalam status menunggu verifikasi sebelum di-approve
        if ($payment->status === 'menunggu_verifikasi') {
            $payment->status = 'terverifikasi';
            $payment->save();
            // Menggunakan nama rute yang benar dan sudah didefinisikan untuk admin.
            return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diverifikasi!');
        }

        // Menggunakan nama rute yang benar dan sudah didefinisikan untuk admin.
        return redirect()->route('admin.pembayaran.index')->with('error', 'Pembayaran tidak dapat diverifikasi karena status tidak sesuai.');
    }
}