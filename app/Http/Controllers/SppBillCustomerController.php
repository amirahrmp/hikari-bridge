<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SppBill;
use App\Models\Payment;
use App\Models\PaymentComponent;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariKidzClub;
use App\Models\PaketHkc;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SppBillCustomerController extends Controller
{
    /**
     * Menampilkan daftar tagihan SPP untuk pengguna yang sedang login (Customer View).
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat tagihan SPP.');
        }

        $tagihanSpp = SppBill::where('user_id', $user->id)
                               ->orderBy('tahun', 'desc')
                               ->orderBy('bulan', 'desc')
                               ->get();

        return view('spp_bills_customer.index', compact('tagihanSpp'));
    }

    /**
     * Menampilkan halaman formulir pembayaran untuk tagihan SPP tertentu.
     */
    public function bayar($tagihanId)
    {
        $tagihan = SppBill::findOrFail($tagihanId);

        // Otorisasi: Pastikan tagihan ini milik user yang sedang login.
        if ($tagihan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses tagihan ini.');
        }

        // Jika status sudah lunas atau menunggu verifikasi, mungkin tidak perlu bayar lagi
        if ($tagihan->status === 'lunas' || $tagihan->status === 'menunggu_verifikasi') {
            return redirect()->route('spp.customer.index')->with('info', 'Tagihan ini sudah ' . ucfirst(str_replace('_', ' ', $tagihan->status)) . '.');
        }
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
                $programDisplay = $tagihan->program; // Fallback ke program umum di MealBill
                break;
        }
        
        if (!$peserta) {
            // Ini seharusnya tidak terjadi jika data spp_bulanan valid, karena registration_id dan type ada
            abort(404, 'Data peserta terkait tidak ditemukan untuk tagihan ini. Harap hubungi admin.');
        }

        // SPP Bulanan adalah komponen tunggal untuk pembayaran ini
        $komponenList = ['SPP Bulanan' => $tagihan->nominal_uang_spp];

        return view('spp_bills_customer.bayar', compact('tagihan', 'peserta', 'komponenList', 'programDisplay'));
    }

    public function prosesPembayaran(Request $request, $tagihanId)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'registration_id' => 'required|integer',
            'registration_type' => 'required|string', // Pastikan ini adalah FQCN, bukan string nama program
            'total_bayar' => 'required|numeric',
        ]);

        $tagihanSpp = SppBill::findOrFail($tagihanId);

        // Otorisasi
        if ($tagihanSpp->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk memproses pembayaran tagihan ini.');
        }

        // Periksa lagi status tagihan sebelum memproses pembayaran
        if ($tagihanSpp->status === 'lunas' || $tagihanSpp->status === 'menunggu_verifikasi') {
            return redirect()->route('spp.bulanan.index')->with('info', 'Tagihan ini sudah ' . ucfirst(str_replace('_', ' ', $tagihanSpp->status)) . '. Pembayaran tidak diproses.');
        }

 DB::transaction(function () use ($request, $tagihanSpp) {
            // 1. Proses upload bukti transfer
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/buktipembayaran'), $filename);

            $payment = Payment::create([
                'registration_id' => $request->registration_id,
                'registration_type' => $request->registration_type,
                'jumlah' => $request->total_bayar,
                'bukti_transfer' => $filename, // Hanya nama file
                'status' => 'menunggu_verifikasi',
                'user_id' => Auth::id(),
                'spp_bill_id' => $tagihanSpp->id, // <-- Tautkan payment ke spp_bulanan_id
            ]);

            // Dapatkan nama paket dari tagihan SPP (yang sudah tersimpan saat generate)
            $namaPaket = $tagihanSpp->package_name; // Mengambil dari kolom 'paket' di SppBulanan
            $komponenNama = 'SPP Bulan ' . Carbon::create()->month($tagihanSpp->bulan)->format('F Y') . ' (' . $namaPaket . ')';

            // 3. Simpan detail komponen pembayaran ke payment_components
            PaymentComponent::create([
                'payment_id' => $payment->id,
                'komponen' => $komponenNama,
                'jumlah' => $tagihanSpp->nominal_uang_spp,
            ]);

            // 4. Update status tagihan SPP ke 'menunggu_verifikasi'
            $tagihanSpp->status = 'menunggu_verifikasi';
            $tagihanSpp->bukti_bayar_path = 'uploads/buktipembayaran/' . $filename;// Simpan path lengkap
            $tagihanSpp->tanggal_bayar = Carbon::now();
            $tagihanSpp->save();
        });

        return redirect()->route('spp.customer.index')->with('success', 'Pembayaran SPP berhasil diupload dan sedang menunggu verifikasi.');
    }
}