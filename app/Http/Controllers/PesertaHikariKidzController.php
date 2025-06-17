<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PesertaHikariKidz;
use App\Models\ProgramLain;
use App\Imports\PesertaHikariKidzImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesertaHikariKidzController extends Controller
{
    public function index()
    {
        $peserta_hikari_kidz = PesertaHikariKidz::orderByRaw('CAST(id_anak AS UNSIGNED) ASC')->get();
        return view('peserta_hikari_kidz.index', compact('peserta_hikari_kidz'));
    }

    public function ubahStatus(Request $request, $id)
    {
        $peserta = PesertaHikariKidz::where('id_anak', $id)->firstOrFail();

        // Toggle status antara Menunggu ↔ Terverifikasi
        $peserta->status = ($peserta->status === 'Terverifikasi') ? 'Menunggu' : 'Terverifikasi';
        $peserta->save();

        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }

      public function generateParticipantVCard($id_anak)
    {
        // 1. Cari data peserta berdasarkan ID
        $peserta = PesertaHikariKidz::findOrFail($id_anak);

        // 2. Siapkan data untuk vCard
        // Kita gunakan nama orang tua, dan tambahkan nama panggilan anak agar mudah dikenali
        $contactName = $peserta->parent_name . ' (Wali ' . $peserta->nickname . ')';

        // Gunakan fungsi format nomor WA yang sudah Anda miliki
        $phoneNumber = $this->formatNomorWhatsapp($peserta->whatsapp_number);

        // Validasi jika nomor WA kosong setelah diformat
        if (empty($phoneNumber)) {
            return redirect()->back()->with('error', 'Nomor WhatsApp peserta tidak valid atau kosong.');
        }
        // AYANGGGG BUTTUUTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT

        // 3. Buat konten vCard secara dinamis
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "FN:" . $contactName . "\n";
        $vcard .= "ORG:Peserta Hikari Kidz\n"; // Bisa diisi nama lembaga Anda
        $vcard .= "TEL;TYPE=CELL:" . $phoneNumber . "\n";
        $vcard .= "END:VCARD";

        // 4. Siapkan nama file yang akan diunduh
        $fileName = 'Kontak-' . Str::slug($peserta->nickname) . '.vcf';
        // 5. Siapkan headers untuk download
        $headers = [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return response($vcard, 200, $headers);
    }

    public function destroy(PesertaHikariKidz $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data paket berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }

    public function show($id)
    {
        $peserta = PesertaHikariKidz::where('id_anak', $id)->firstOrFail();
        return view('peserta_hikari_kidz.detail', compact('peserta'));
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PesertaHikariKidzImport, $request->file('excel_file'));

        return redirect()->back()->with([
            'message' => 'Data Anak Peserta Hikari Kidz berhasil ditambahkan dari Excel!',
            'alert-type' => 'success'
        ]);
    }

    public function kirimPesanWhatsapp($id)
    {
        try {
            $peserta = PesertaHikariKidz::where('id_anak', $id)->firstOrFail();
            
            $nomorAsli = $peserta->whatsapp_number;
            $nomor = $this->formatNomorWhatsapp($peserta->whatsapp_number);
            
            // Log untuk debug
            Log::info("WhatsApp Debug - ID: $id, Nomor Asli: $nomorAsli, Nomor Format: $nomor");
            
            $nama = $peserta->full_name;
            $ortu = $peserta->parent_name;

            $pesan = "Halo $ortu, kami dari Hikari Bridge. Ini informasi terkait pendaftaran/keikutsertaan anak Anda, $nama. Mohon diperhatikan untuk keikutsertaan dalam program Hikari Kidz.";
            
            // Gunakan api.whatsapp.com untuk lebih reliable
            $url = "https://api.whatsapp.com/send?phone=$nomor&text=" . urlencode($pesan);
            
            Log::info("WhatsApp URL: $url");
            
            return redirect()->away($url);
            
        } catch (\Exception $e) {
            Log::error("WhatsApp Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuka WhatsApp: ' . $e->getMessage());
        }
    }

    private function formatNomorWhatsapp($nomor)
    {
        // Hapus semua karakter selain angka
        $nomor = preg_replace('/[^0-9]/', '', $nomor);
        
        // Jika kosong, return kosong
        if (empty($nomor)) {
            return '';
        }
        
        // Deteksi apakah sudah format internasional
        if ($this->isInternationalFormat($nomor)) {
            return $nomor;
        }
        
        // Jika dimulai dengan 0 (format Indonesia), ganti dengan 62
        if (substr($nomor, 0, 1) === '0') {
            return '62' . substr($nomor, 1);
        }
        
        // Jika nomor pendek dan sepertinya Indonesia, tambahkan 62
        if (strlen($nomor) >= 9 && strlen($nomor) <= 13 && !$this->isInternationalFormat($nomor)) {
            return '62' . $nomor;
        }
        
        // Return nomor apa adanya jika tidak bisa dideteksi
        return $nomor;
    }
    
    private function isInternationalFormat($nomor)
    {
        // Daftar country code yang umum
        $countryCodes = [
            '62',   // Indonesia
            '60',   // Malaysia
            '65',   // Singapore
            '66',   // Thailand
            '63',   // Philippines
            '84',   // Vietnam
            '1',    // US/Canada (tapi harus hati-hati, bisa salah)
            '44',   // UK
            '91',   // India
            '86',   // China
            '81',   // Japan
            '82',   // South Korea
            '61',   // Australia
            '49',   // Germany
            '33',   // France
            '39',   // Italy
            '34',   // Spain
            '55',   // Brazil
            '52',   // Mexico
            '27',   // South Africa
        ];
        
        foreach ($countryCodes as $code) {
            if (substr($nomor, 0, strlen($code)) === $code) {
                // Untuk country code 1 digit, pastikan panjang nomor masuk akal
                if ($code === '1' && strlen($nomor) !== 11) {
                    continue;
                }
                return true;
            }
        }
        
        return false;
    }
    
    // Helper function untuk generate WhatsApp link di view
    public static function generateWhatsAppUrl($nomorWhatsapp, $pesan = '')
    {
        $controller = new self();
        $nomor = $controller->formatNomorWhatsapp($nomorWhatsapp);
        
        if (empty($pesan)) {
            return "https://api.whatsapp.com/send?phone=$nomor";
        }
        
        return "https://api.whatsapp.com/send?phone=$nomor&text=" . urlencode($pesan);
    }

    public function verifikasi()
    {
        $peserta_hikari_kidz = PesertaHikariKidz::where('status', 'Terverifikasi')->get();
        return view('peserta_hikari_kidz.verifikasi', compact('peserta_hikari_kidz'));
    }

    public function ubahStatusKeaktifan($id, Request $request)
    {
        $peserta = PesertaHikariKidz::findOrFail($id);
        $status = $request->input('status_keaktifan');

        if (!in_array($status, ['Aktif', 'Cuti', 'Tidak Aktif'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        $peserta->status_keaktifan = $status;
        $peserta->save();

        return redirect()->back()->with('success', 'Status keaktifan berhasil diperbarui.');
    }

    public function programLain()
    {
        return $this->hasMany(ProgramLain::class, 'peserta_id');
    }


}