<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
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
    $userId = auth()->id();

    $payments = Payment::latest()->with('components')->get();
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
            return redirect()->route('daftar_program_hikari_kidz.index')->with('error', 'ID pendaftaran atau tipe tidak ditemukan.');
        }

        // Switch-case untuk mengisi variabel $peserta dan $paket
        switch ($registration_type) {
            case 'Hikari Kidz Club':
                $peserta = \App\Models\RegistrationHikariKidzClub::findOrFail($registration_id);
                $paket = \App\Models\PaketHkc::where('member', $peserta->member)
                                 ->where('kelas', $peserta->kelas)
                                 ->first();
                if (!$paket) {
                    abort(404, 'Paket Hikari Kidz Club tidak ditemukan untuk peserta ini.');
                }
                break;

            case 'Hikari Kidz Daycare':
                $peserta = \App\Models\RegistrationHikariKidzDaycare::findOrFail($registration_id);
                $paket = $peserta->paket;
                if (!$paket) {
                    abort(404, 'Paket Hikari Kidz Daycare tidak ditemukan untuk peserta ini.');
                }
                break;

            case 'Hikari Quran':
                $peserta = \App\Models\RegistrationHikariQuran::findOrFail($registration_id);
                $paket = $peserta->pakethq;
                if (!$paket) {
                    abort(404, 'Paket Hikari Quran tidak ditemukan untuk peserta ini.');
                }
                break;

            default:
                abort(404, 'Tipe pendaftaran tidak valid.');
        }

        // --- LOGIKA UNTUK MENGHITUNG RIWAYAT PEMBAYARAN ---

        // 1. Ambil semua nama komponen yang SUDAH DIBAYAR dan TERVERIFIKASI
        $paidComponents = \App\Models\PaymentComponent::whereHas('payment', function ($query) use ($registration_id, $registration_type) {
            $query->where('registration_id', $registration_id)
                  ->where('registration_type', $registration_type)
                  ->where('status', 'terverifikasi');
        })->pluck('komponen')->toArray();

        // 2. Logika Cerdas untuk Cicilan Uang Pangkal (Khusus Daycare)
        $totalUangPangkalPaid = 0;
        $originalCicilanPlan = 0; // 0 = belum ada rencana; 1 = lunas; 2 = 2x; 3 = 3x
        $installmentsPaidCount = 0; // Berapa kali cicilan sudah dibayar

        if ($registration_type === 'Hikari Kidz Daycare') {
            $uangPangkalPayments = \App\Models\PaymentComponent::whereHas('payment', function ($query) use ($registration_id, $registration_type) {
                $query->where('registration_id', $registration_id)
                      ->where('registration_type', $registration_type)
                      ->where('status', 'terverifikasi');
            })
            ->where('komponen', 'like', 'Uang Pangkal%')
            ->orderBy('created_at', 'asc')
            ->get();

            if ($uangPangkalPayments->isNotEmpty()) {
                $totalUangPangkalPaid = $uangPangkalPayments->sum('jumlah');
                $installmentsPaidCount = $uangPangkalPayments->count();
                $firstUangPangkalPayment = $uangPangkalPayments->first();
                $namaKomponenPertama = $firstUangPangkalPayment->komponen;

                if (strpos($namaKomponenPertama, 'Cicilan 3x') !== false) {
                    $originalCicilanPlan = 3;
                } elseif (strpos($namaKomponenPertama, 'Cicilan 2x') !== false) {
                    $originalCicilanPlan = 2;
                } else {
                    $originalCicilanPlan = 1;
                }
            }
        }

        // Kirim SEMUA variabel yang dibutuhkan ke view
        return view('payment.create', compact(
            'registration_id', 'registration_type', 'peserta', 'paket',
            'paidComponents', 'totalUangPangkalPaid',
            'originalCicilanPlan', 'installmentsPaidCount'
        ));
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
            'komponen' => 'nullable|array',
            'komponen.*' => 'string',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'cicilan_uang_pangkal' => 'nullable|integer|min:0|max:3',
            'uang_pangkal_nominal_full' => 'nullable|numeric',
            'pay_next_installment' => 'nullable|boolean',
            'next_installment_amount' => 'nullable|numeric',
            'cicilan_info_string' => 'nullable|string',
        ]);

        $validatedData['komponen'] = $validatedData['komponen'] ?? [];

        // 2. Ambil data paket dan nominal komponen dari backend
        $nominalKomponen = [];
        switch ($validatedData['registration_type']) {
            case 'Hikari Kidz Club':
                $peserta = RegistrationHikariKidzClub::findOrFail($validatedData['registration_id']);
                $paket = PaketHkc::where('member', $peserta->member)->where('kelas', $peserta->kelas)->first();
                if (!$paket) { return back()->withErrors(['paket' => 'Paket tidak ditemukan.'])->withInput(); }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0, 'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                    'Uang Sarana' => $paket->u_sarana ?? 0, 'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                break;
            case 'Hikari Kidz Daycare':
                $peserta = RegistrationHikariKidzDaycare::findOrFail($validatedData['registration_id']);
                $paket = $peserta->paket;
                if (!$paket) { return back()->withErrors(['paket' => 'Paket tidak ditemukan.'])->withInput(); }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0, 'Uang Pangkal' => $paket->u_pangkal ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0, 'Uang Makan' => $paket->u_makan ?? 0,
                    "Uang Kegiatan" => $paket->u_kegiatan ?? 0,
                ];
                break;
            case 'Hikari Quran':
                $peserta = RegistrationHikariQuran::findOrFail($validatedData['registration_id']);
                $paket = $peserta->pakethq;
                if (!$paket) { return back()->withErrors(['paket' => 'Paket tidak ditemukan.'])->withInput(); }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0, 'Uang Modul' => $paket->u_modul ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                break;
            default:
                abort(404, 'Tipe pendaftaran tidak valid.');
        }

        // 3. Validasi komponen wajib HANYA untuk pembayaran pertama
        if (!$request->has('pay_next_installment')) {
            $initialMandatoryComponents = $this->getMandatoryComponentsList($validatedData['registration_type']);
            $requiredComponentsNominalGreaterThanZero = [];
            foreach ($nominalKomponen as $compName => $compValue) {
                if ($compValue > 0 && in_array($compName, $initialMandatoryComponents)) {
                    $requiredComponentsNominalGreaterThanZero[] = $compName;
                }
            }

            foreach ($requiredComponentsNominalGreaterThanZero as $comp) {
                if (!in_array($comp, $validatedData['komponen'])) {
                    return back()->withErrors(['komponen' => 'Komponen wajib tidak lengkap: ' . $comp . '. Harap coba lagi.'])->withInput();
                }
            }
        }

        // 4. Proses upload bukti transfer
        $file = $request->file('bukti_transfer');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/buktipembayaran'), $filename);

        // 5. Hitung total pembayaran dan siapkan komponen untuk disimpan
        $total = 0;
        $componentsToSave = [];

        foreach ($validatedData['komponen'] as $komponenNama) {
            $jumlahKomponen = $nominalKomponen[$komponenNama] ?? 0;
            $total += (float) $jumlahKomponen;
            $componentsToSave[] = ['komponen' => $komponenNama, 'jumlah' => (float) $jumlahKomponen];
        }

        // 6. Logika untuk menyimpan cicilan
        if (isset($validatedData['cicilan_uang_pangkal']) && $validatedData['cicilan_uang_pangkal'] > 0) {
            $cicilan = (int) $validatedData['cicilan_uang_pangkal'];
            $uangPangkalFull = (float) $validatedData['uang_pangkal_nominal_full'];
            $jumlahCicilanSaatIni = ceil($uangPangkalFull / $cicilan);
            $total += (float) $jumlahCicilanSaatIni;
            $componentsToSave[] = ['komponen' => 'Uang Pangkal (Cicilan ' . $cicilan . 'x)', 'jumlah' => (float) $jumlahCicilanSaatIni];
        }
        elseif (isset($validatedData['pay_next_installment']) && $validatedData['pay_next_installment']) {
            $jumlahCicilanSaatIni = (float) $validatedData['next_installment_amount'];
            $komponenNama = $validatedData['cicilan_info_string'];
            $total += $jumlahCicilanSaatIni;
            $componentsToSave[] = ['komponen' => $komponenNama, 'jumlah' => $jumlahCicilanSaatIni];
        }

        // 7. Simpan ke tabel Payments
        $payment = Payment::create([
            'registration_id' => $validatedData['registration_id'],
            'registration_type' => $validatedData['registration_type'],
            'jumlah' => $total,
            'bukti_transfer' => $filename,
            'status' => 'menunggu_verifikasi',
            'user_id' => auth()->id(), // ← tambahkan ini
        ]);

        // 8. Simpan detail komponen
        if (!empty($componentsToSave)) {
            $payment->components()->createMany($componentsToSave);
        }

        return redirect()->route('payment.index')->with('success', 'Pembayaran berhasil disimpan dan menunggu verifikasi admin.');
    }

    /**
     * Helper function untuk mendapatkan daftar komponen wajib.
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
        $payments = Payment::where('status', 'menunggu_verifikasi')
                           ->with(['components'])
                           ->latest()
                           ->get();
        return view('verifikasi_pembayaran.index', compact('payments'));
    }

    /**
     * Mengubah status pembayaran menjadi 'terverifikasi' oleh Admin.
     */
    public function approve(Request $request, Payment $payment)
    {
        if ($payment->status === 'menunggu_verifikasi') {
            $payment->status = 'terverifikasi';
            $payment->save();
            return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diverifikasi!');
        }
        return redirect()->route('admin.pembayaran.index')->with('error', 'Pembayaran tidak dapat diverifikasi karena status tidak sesuai.');
    }
}