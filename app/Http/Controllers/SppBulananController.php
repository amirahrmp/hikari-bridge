<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\SppBulanan;
    use Illuminate\Support\Facades\Auth;

    class SppBulananController extends Controller
    {
        public function index()
        {
            // PENTING: Logika ini mengasumsikan Anda memiliki cara untuk menghubungkan
            // user yang sedang login dengan data pendaftaran anaknya.
            // Untuk saat ini, kita akan tampilkan SEMUA tagihan sebagai contoh.
            // Anda perlu menyesuaikan query ini nantinya.
            
            // Contoh jika User punya relasi ke pendaftaran:
            // $user = Auth::user();
            // $registrationIds = $user->registrations()->pluck('id');
            // $tagihanSpp = SppTagihan::whereIn('registration_id', $registrationIds)->...

            // Untuk sekarang, kita tampilkan semua
            $tagihanSpp = SppBulanan::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

            return view('spp_bulanan.index', compact('tagihanSpp'));
        }

        public function bayar($tagihanId)
        {
            $tagihan = SppBulanan::findOrFail($tagihanId);

            // Redirect ke halaman form pembayaran, sambil membawa data tagihan
            // Anda bisa buat view baru atau integrasikan dengan 'payment.create'
            // dengan menambahkan parameter baru.
            
            // Contoh sederhana:
            return view('spp_bulanan.bayar', compact('tagihan'));
        }

        public function prosesPembayaran(Request $request, $tagihanId)
        {
            // Logika untuk memproses upload bukti bayar dan mengubah status tagihan
            // ...
            $tagihan = SppBulanan::findOrFail($tagihanId);
            $tagihan->status = 'menunggu_verifikasi';
            // ... simpan bukti bayar
            $tagihan->save();

            return redirect()->route('spp.bulanan.index')->with('success', 'Bukti pembayaran berhasil diupload dan sedang menunggu verifikasi.');
        }
    }
    