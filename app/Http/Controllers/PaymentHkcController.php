<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaketHkc;
use App\Models\Paket;
use App\Models\PaketHq; // Pastikan ini diimpor
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\PaymentComponent;
use App\Models\SppBill;
use App\Models\OvertimeBill;
use App\Models\MealBill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentHkcController extends Controller
{
    /**
     * Tampilkan daftar riwayat pembayaran (untuk customer/frontend).
     */
    public function index()
    {
        $userId = auth()->id();
        $payments = Payment::where('user_id', $userId)
            ->latest()
            ->with([
                'components',
                'sppBill',
                'overtimeBill',
                'mealBill',
                // Perbaiki eager loading untuk relasi polymorphic 'registration'
                // Kita akan eager load 'paket' jika itu Daycare, atau 'pakethq' jika itu Quran, dll.
                'registration' => function ($morphTo) {
                    $morphTo->morphWith([
                        RegistrationHikariKidzDaycare::class => ['paket'], // Daycare punya relasi 'paket'
                        RegistrationHikariQuran::class => ['pakethq'],    // Quran punya relasi 'pakethq'
                        // RegistrationHikariKidzClub tidak perlu eager load paket di sini
                        // karena paketnya diambil via method getPaketHkc() di accessor
                    ]);
                },
            ])
            ->get();

        return view('paymenthkc.index', compact('payments'));
    }

    /**
     * Menampilkan form untuk membuat pembayaran baru.
     * Ini HANYA untuk pembayaran komponen awal (pendaftaran, uang pangkal, perlengkapan, dll)
     * SPP Bulanan dan Overtime Bill akan memiliki alur pembayaran terpisah.
     */
    public function create(Request $request)
    {
        $registration_id = $request->query('registration_id');
        $registration_type_string = $request->query('registration_type');

        $peserta = null;
        $paket = null;
        $registration_type_class = null;

        if (empty($registration_id) || empty($registration_type_string)) {
            return redirect()->route('daftar_program_hikari_kidz.index')->with('error', 'ID pendaftaran atau tipe tidak ditemukan.');
        }

        switch ($registration_type_string) {
            case 'Hikari Kidz Club':
                $registration_type_class = RegistrationHikariKidzClub::class;
                $peserta = RegistrationHikariKidzClub::findOrFail($registration_id);
                $paket = $peserta->getPaketHkc();
                break;

            case 'Hikari Kidz Daycare':
                $registration_type_class = RegistrationHikariKidzDaycare::class;
                $peserta = RegistrationHikariKidzDaycare::with('paket')->findOrFail($registration_id);
                $paket = $peserta->paket;
                break;

            case 'Hikari Quran':
                $registration_type_class = RegistrationHikariQuran::class;
                $peserta = RegistrationHikariQuran::with('pakethq')->findOrFail($registration_id);
                $paket = $peserta->pakethq;
                break;

            default:
                abort(404, 'Tipe pendaftaran tidak valid.');
        }

        if (!$paket) {
            abort(404, 'Paket tidak ditemukan untuk peserta ini.');
        }

        $komponenList = [];
        $mandatoryComponents = [];

        switch ($registration_type_string) {
            case 'Hikari Kidz Club':
                $komponenList = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                    'Uang Sarana' => $paket->u_sarana ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                $mandatoryComponents = ['Uang Pendaftaran', 'Uang Perlengkapan', 'Uang Sarana', 'SPP Bulanan'];
                break;

            case 'Hikari Kidz Daycare':
                $komponenList = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Pangkal' => $paket->u_pangkal ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                    'Uang Makan' => $paket->u_makan ?? 0,
                    'Uang Kegiatan' => $paket->u_kegiatan ?? 0,
                ];
                $mandatoryComponents = ['Uang Pendaftaran', 'SPP Bulanan', 'Uang Makan', 'Uang Kegiatan'];
                break;

            case 'Hikari Quran':
                $komponenList = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Modul' => $paket->u_modul ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                $mandatoryComponents = ['Uang Pendaftaran', 'Uang Modul', 'SPP Bulanan'];
                break;
        }

        $paidComponents = PaymentComponent::whereHas('payment', function ($query) use ($registration_id, $registration_type_class) {
            $query->where('registration_id', $registration_id)
                  ->where('registration_type', $registration_type_class)
                  ->where('status', 'terverifikasi');
        })->pluck('komponen')->toArray();

        $totalUangPangkalPaid = 0;
        $originalCicilanPlan = 0;
        $installmentsPaidCount = 0;
        $uangPangkalRemaining = 0;

        if ($registration_type_string === 'Hikari Kidz Daycare') {
            $uangPangkalPayments = PaymentComponent::whereHas('payment', function ($query) use ($registration_id, $registration_type_class) {
                $query->where('registration_id', $registration_id)
                      ->where('registration_type', $registration_type_class)
                      ->where('status', 'terverifikasi');
            })
            ->where('komponen', 'like', 'Uang Pangkal%')
            ->orderBy('created_at', 'asc')
            ->get();

            if ($uangPangkalPayments->isNotEmpty()) {
                $totalUangPangkalPaid = $uangPangkalPayments->sum('jumlah');
                $installmentsPaidCount = $uangPangkalPayments->count();
                $firstUangPangkalComponent = $uangPangkalPayments->first();

                if ($firstUangPangkalComponent && Str::contains($firstUangPangkalComponent->komponen, 'Cicilan')) {
                    preg_match('/Cicilan (\\d+) dari (\\d+)/', $firstUangPangkalComponent->komponen, $matches);
                    if (isset($matches[2])) {
                        $originalCicilanPlan = (int) $matches[2];
                    }
                } else {
                    $originalCicilanPlan = 1;
                }
            }

            $uangPangkalRemaining = ($paket->u_pangkal ?? 0) - $totalUangPangkalPaid;
        }

        return view('paymenthkc.create', compact(
            'registration_id',
            'registration_type_string',
            'registration_type_class',
            'peserta',
            'paket',
            'komponenList',
            'mandatoryComponents',
            'paidComponents',
            'totalUangPangkalPaid',
            'originalCicilanPlan',
            'installmentsPaidCount',
            'uangPangkalRemaining'
        ))->with('registration_type', $registration_type_string); // Tambahkan ini
    }
    /**
     * Menyimpan data pembayaran baru dari form 'payment.create'.
     * Ini hanya untuk pembayaran komponen awal.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required|integer',
            'registration_type' => 'required|string', // Ini adalah string literal dari form (Hikari Kidz Daycare, dll)
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

        $nominalKomponen = [];
        $registration_type_class = null; // FQCN yang akan disimpan ke DB

        switch ($validatedData['registration_type']) {
            case 'Hikari Kidz Club':
                $registration_type_class = RegistrationHikariKidzClub::class;
                $peserta = RegistrationHikariKidzClub::findOrFail($validatedData['registration_id']);
                $paket = PaketHkc::where('member', $peserta->member)->where('kelas', $peserta->kelas)->first();
                if (!$paket) { return back()->withErrors(['paket' => 'Paket tidak ditemukan.'])->withInput(); }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                    'Uang Sarana' => $paket->u_sarana ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                break;
            case 'Hikari Kidz Daycare':
                $registration_type_class = RegistrationHikariKidzDaycare::class;
                $peserta = RegistrationHikariKidzDaycare::findOrFail($validatedData['registration_id']);
                $paket = $peserta->paket;
                if (!$paket) { return back()->withErrors(['paket' => 'Paket tidak ditemukan.'])->withInput(); }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Pangkal' => $paket->u_pangkal ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                    'Uang Makan' => $paket->u_makan ?? 0,
                    "Uang Kegiatan" => $paket->u_kegiatan ?? 0,
                ];
                break;
            case 'Hikari Quran':
                $registration_type_class = RegistrationHikariQuran::class;
                $peserta = RegistrationHikariQuran::findOrFail($validatedData['registration_id']);
                $paket = $peserta->pakethq;
                if (!$paket) { return back()->withErrors(['paket' => 'Paket tidak ditemukan.'])->withInput(); }
                $nominalKomponen = [
                    'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                    'Uang Modul' => $paket->u_modul ?? 0,
                    'SPP Bulanan' => $paket->u_spp ?? 0,
                ];
                break;
            default:
                abort(404, 'Tipe pendaftaran tidak valid.');
        }

        $paidComponents = PaymentComponent::whereHas('payment', function ($query) use ($validatedData, $registration_type_class) {
            $query->where('registration_id', $validatedData['registration_id'])
                  ->where('registration_type', $registration_type_class)
                  ->where('status', 'terverifikasi');
        })->pluck('komponen')->toArray();


        if (!($request->has('pay_next_installment') && $request->input('pay_next_installment'))) {
            $initialMandatoryComponents = $this->getMandatoryComponentsList($validatedData['registration_type']);
            $requiredComponentsNominalGreaterThanZero = [];
            foreach ($nominalKomponen as $compName => $compValue) {
                if ($compValue > 0 && in_array($compName, $initialMandatoryComponents)) {
                    if (!in_array($compName, $paidComponents)) {
                        $requiredComponentsNominalGreaterThanZero[] = $compName;
                    }
                }
            }

            foreach ($requiredComponentsNominalGreaterThanZero as $comp) {
                if (!in_array($comp, $validatedData['komponen'])) {
                    return back()->withErrors(['komponen' => 'Anda harus memilih semua komponen wajib yang belum lunas: ' . $comp . '.'])->withInput();
                }
            }
        }


        DB::transaction(function () use ($request, $validatedData, $nominalKomponen, $registration_type_class, $peserta, $paidComponents) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/buktipembayaran'), $filename);

            $total = 0;
            $componentsToSave = [];

            foreach ($validatedData['komponen'] as $komponenNama) {
                if (!in_array($komponenNama, $paidComponents)) {
                    $jumlahKomponen = $nominalKomponen[$komponenNama] ?? 0;
                    $total += (float) $jumlahKomponen;
                    $componentsToSave[] = ['komponen' => $komponenNama, 'jumlah' => (float) $jumlahKomponen];
                }
            }

            if ($validatedData['registration_type'] === 'Hikari Kidz Daycare') {
                $originalCicilanPlan = $peserta->cicilan_uang_pangkal_plan ?? 0;
                $totalUangPangkalPaket = $peserta->paket->u_pangkal ?? 0;

                $currentPaidUangPangkal = PaymentComponent::whereHas('payment', function ($query) use ($validatedData, $registration_type_class) {
                    $query->where('registration_id', $validatedData['registration_id'])
                          ->where('registration_type', $registration_type_class)
                          ->where('status', 'terverifikasi');
                })
                ->where('komponen', 'like', 'Uang Pangkal%')
                ->sum('jumlah');

                $uangPangkalRemaining = $totalUangPangkalPaket - $currentPaidUangPangkal;

                if ($originalCicilanPlan == 0 && $uangPangkalRemaining > 0.01) {
                    $cicilanYangDipilih = (int) $request->input('cicilan_uang_pangkal', 0);

                    if ($cicilanYangDipilih > 0) {
                        $jumlahCicilanSaatIni = ceil($uangPangkalRemaining / $cicilanYangDipilih);
                        $total += (float) $jumlahCicilanSaatIni;
                        $componentsToSave[] = ['komponen' => "Uang Pangkal (Cicilan 1 dari {$cicilanYangDipilih}x)", 'jumlah' => (float) $jumlahCicilanSaatIni];
                        $peserta->cicilan_uang_pangkal_plan = $cicilanYangDipilih;
                        $peserta->save();
                    } else {
                        $jumlahCicilanSaatIni = $uangPangkalRemaining;
                        $total += (float) $jumlahCicilanSaatIni;
                        $componentsToSave[] = ['komponen' => "Uang Pangkal (Lunas 1x Pembayaran)", 'jumlah' => (float) $jumlahCicilanSaatIni];
                        $peserta->cicilan_uang_pangkal_plan = 1;
                        $peserta->save();
                    }
                }
                elseif ($originalCicilanPlan > 0 && ($validatedData['pay_next_installment'] ?? false)) {
                    $jumlahCicilanSaatIni = (float) $validatedData['next_installment_amount'];
                    $cicilanInfoString = $validatedData['cicilan_info_string'];
                    $total += $jumlahCicilanSaatIni;
                    $componentsToSave[] = ['komponen' => $cicilanInfoString, 'jumlah' => $jumlahCicilanSaatIni];
                }
            }


            $payment = Payment::create([
                'registration_id' => $validatedData['registration_id'],
                'registration_type' => $registration_type_class,
                'jumlah' => $total,
                'bukti_transfer' => $filename,
                'status' => 'menunggu_verifikasi',
                'user_id' => auth()->id(),
                'spp_bill_id' => null,
                'overtime_bill_id' => null,
                'meal_bill_id' => null,
                'tanggal' => now(), // TAMBAHKAN INI
            ]);

            if (!empty($componentsToSave)) {
                $payment->components()->createMany($componentsToSave);
            }
        });

        return redirect()->route('paymenthkc.index')->with('success', 'Pembayaran berhasil disimpan dan menunggu verifikasi admin.');
    }

    private function getMandatoryComponentsList($registrationTypeString)
    {
        switch ($registrationTypeString) {
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

    public function adminIndex()
    {
        $payments = Payment::where('status', 'menunggu_verifikasi')
            ->latest()
            ->with([
                'sppBill',
                'overtimeBill',
                'mealBill',
                'components',
                'registration' => function ($morphTo) {
                    $morphTo->morphWith([
                        RegistrationHikariKidzDaycare::class => ['paket'],
                        RegistrationHikariQuran::class => ['pakethq'],
                    ]);
                },
            ])
            ->get();

        return view('verifikasi_pembayaran.index', compact('payments'));
    }

    public function approve(Request $request, Payment $payment)
    {
        if ($payment->status === 'menunggu_verifikasi') {
            DB::transaction(function () use ($payment) {
                $payment->status = 'terverifikasi';
                $payment->notes = null;
                $payment->save();

                if ($payment->sppBill) {
                    $payment->sppBill->status = 'lunas';
                    $payment->sppBill->save();
                }

                if ($payment->overtimeBill) {
                    $payment->overtimeBill->status = 'lunas';
                    $payment->overtimeBill->save();
                }

                if ($payment->mealBill) {
                    $payment->mealBill->status = 'lunas';
                    $payment->mealBill->save();
                }
            });

            return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diverifikasi!');
        }

        return redirect()->route('admin.pembayaran.index')->with('error', 'Pembayaran tidak dapat diverifikasi karena status tidak sesuai.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        if ($payment->status === 'menunggu_verifikasi') {
            DB::transaction(function () use ($payment, $request) {
                $payment->status = 'ditolak';
                $payment->notes = $request->reason;
                $payment->save();

                if ($payment->sppBill) {
                    $payment->sppBill->status = 'ditolak';
                    $payment->sppBill->save();
                }
                if ($payment->overtimeBill) {
                    $payment->overtimeBill->status = 'ditolak';
                    $payment->overtimeBill->save();
                }
                if ($payment->mealBill) {
                    $payment->mealBill->status = 'ditolak';
                    $payment->mealBill->save();
                }
            });

            return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditolak.');
        }
        return redirect()->route('admin.pembayaran.index')->with('error', 'Pembayaran tidak dapat ditolak karena status tidak sesuai.');
    }

    public function receipt(Payment $payment)
    {
        if ($payment->status !== 'terverifikasi') {
            return abort(403, 'Kuitansi hanya tersedia untuk pembayaran yang sudah diverifikasi.');
        }

        $childName = $payment->peserta->full_name ?? '-'; // Asumsikan accessor sudah ada
        $print = request()->query('print') === '1';

        return view('paymenthkc.receipt', compact('payment', 'childName', 'print'));
    }
}