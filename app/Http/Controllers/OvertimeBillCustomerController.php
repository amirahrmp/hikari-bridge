<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeBill;
use App\Models\Payment;
use App\Models\PaymentComponent;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\PaketHkc;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OvertimeBillCustomerController extends Controller
{
    /**
     * Menampilkan daftar tagihan overtime untuk pengguna yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat tagihan Overtime.');
        }

        $tagihanOvertime = OvertimeBill::where('user_id', $user->id)
                                       ->orderBy('tahun', 'desc')
                                       ->orderBy('bulan', 'desc')
                                       ->get();

        return view('overtime_bills_customer.index', compact('tagihanOvertime'));
    }

    /**
     * Menampilkan halaman formulir pembayaran untuk tagihan Overtime tertentu.
     */
    public function bayar($tagihanId)
    {
        $tagihan = OvertimeBill::findOrFail($tagihanId);

        // Otorisasi
        if ($tagihan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses tagihan ini.');
        }

        // Jika status sudah lunas atau menunggu verifikasi, mungkin tidak perlu bayar lagi
        if ($tagihan->status === 'lunas' || $tagihan->status === 'menunggu_verifikasi') {
            return redirect()->route('overtime.customer.index')->with('info', 'Tagihan ini sudah ' . ucfirst(str_replace('_', ' ', $tagihan->status)) . '.');
        }

        // Siapkan data peserta terkait tagihan
        $peserta = null;
        $programDisplay = null;
        switch ($tagihan->registration_type) {
            case RegistrationHikariKidzDaycare::class:
                $peserta = RegistrationHikariKidzDaycare::find($tagihan->registration_id);
                $programDisplay = optional($peserta->paket)->nama_paket;
                break;
            case RegistrationHikariKidzClub::class:
                $peserta = RegistrationHikariKidzClub::find($tagihan->registration_id);
                $paketHkc = optional($peserta)->getPaketHkc();
                $programDisplay = optional($paketHkc)->member . ' (' . optional($paketHkc)->kelas . ')';
                break;
            default:
                break;
        }

        if (!$peserta) {
            abort(404, 'Data peserta terkait tidak ditemukan untuk tagihan ini. Harap hubungi admin.');
        }

        // Overtime Bill adalah komponen tunggal untuk pembayaran ini
        $komponenList = ['Denda Overtime' => $tagihan->total_denda];

        return view('overtime_bills_customer.bayar', compact('tagihan', 'peserta', 'komponenList', 'programDisplay'));
    }

    /**
     * Memproses pembayaran tagihan Overtime dengan mengunggah bukti bayar.
     */
    public function prosesPembayaran(Request $request, $tagihanId)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'registration_id' => 'required|integer',
            'registration_type' => 'required|string',
            'total_bayar' => 'required|numeric',
        ]);

        $tagihanOvertime = OvertimeBill::findOrFail($tagihanId);

        // Otorisasi
        if ($tagihanOvertime->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk memproses pembayaran tagihan ini.');
        }

        // Periksa lagi status tagihan
        if ($tagihanOvertime->status === 'lunas' || $tagihanOvertime->status === 'menunggu_verifikasi') {
            return redirect()->route('overtime.customer.index')->with('info', 'Tagihan ini sudah ' . ucfirst(str_replace('_', ' ', $tagihanOvertime->status)) . '. Pembayaran tidak diproses.');
        }

        DB::transaction(function () use ($request, $tagihanOvertime) {
            // 1. Proses upload bukti transfer
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/buktipembayaran'), $filename);

            // 2. Simpan ke tabel Payments
            $payment = Payment::create([
                'registration_id' => $request->registration_id,
                'registration_type' => $request->registration_type,
                'jumlah' => $request->total_bayar,
                'bukti_transfer' => $filename,
                'status' => 'menunggu_verifikasi',
                'user_id' => Auth::id(),
                'overtime_bill_id' => $tagihanOvertime->id, // <-- Tautkan payment ke overtime_bill_id
            ]);

            // Dapatkan nama paket dari tagihan Overtime (yang sudah tersimpan saat generate)
            $namaPaket = $tagihanOvertime->package_name;
            $komponenNama = 'Denda Overtime Bulan ' . Carbon::create()->month($tagihanOvertime->bulan)->format('F Y') . ' (' . $namaPaket . ')';

            // 3. Simpan detail komponen pembayaran ke payment_components
            PaymentComponent::create([
                'payment_id' => $payment->id,
                'komponen' => $komponenNama,
                'jumlah' => $tagihanOvertime->total_denda,
            ]);

            // 4. Update status tagihan Overtime ke 'menunggu_verifikasi'
            $tagihanOvertime->status = 'menunggu_verifikasi';
            $tagihanOvertime->bukti_bayar_path = 'uploads/buktipembayaran/' . $filename;
            $tagihanOvertime->tanggal_bayar = Carbon::now();
            $tagihanOvertime->save();
        });

        return redirect()->route('overtime.customer.index')->with('success', 'Pembayaran denda overtime berhasil diupload dan sedang menunggu verifikasi.');
    }
}