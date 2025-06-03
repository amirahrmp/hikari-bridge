<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
use App\Models\Paket;
use App\Models\PaketHkc;
use App\Models\PaketHq;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::latest()->get();
        return view('payment.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $registration_id = $request->query('registration_id');
        $registration_type = $request->query('registration_type');

        // Ambil data peserta berdasarkan tipe pendaftaran
        $peserta = null;
        $paket = null;

        if ($registration_type === 'Hikari Kidz Club') {
            $peserta = RegistrationHikariKidzClub::findOrFail($registration_id);
            $paket = PaketHkc::where('member', $peserta->member)
                             ->where('kelas', $peserta->kelas)
                             ->first();
        } elseif ($registration_type === 'Hikari Kidz Daycare') {
            $peserta = RegistrationHikariKidzDaycare::findOrFail($registration_id);
            $paket = $peserta->paket; // Relasi paket Daycare
        } elseif ($registration_type === 'Hikari Quran') {
            $peserta = RegistrationHikariQuran::findOrFail($registration_id);
            $paket = $peserta->pakethq; // Relasi paket HQ
        } else {
            abort(404, 'Tipe pendaftaran tidak valid.');
        }

        return view('payment.create', compact('registration_id', 'registration_type', 'peserta', 'paket'));
    }

public function store(Request $request)
{
   $validatedData =  $request->validate([
        'registration_id' => 'required|integer',
        'registration_type' => 'required|string',
        'komponen' => 'required|array',
        'komponen.*' => 'string',
        'jumlah' => 'required|numeric',
        'bukti_transfer' =>  'required|file|mimes:jpg,jpeg,png|max:2048',
    ]);

     if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/buktipembayaran'), $filename);
            $validatedData['bukti_transfer'] = $filename;
        }

    // Validasi komponen wajib "Uang Pendaftaran"
    if (!in_array('Uang Pendaftaran', $request->komponen)) {
        return back()->withErrors(['komponen' => 'Uang Pendaftaran wajib dipilih.'])->withInput();
    }

    $peserta = null;
    $paket = null;
    $nominalKomponen = [];

    if ($request->registration_type === 'Hikari Kidz Club') {
        $peserta = RegistrationHikariKidzClub::findOrFail($request->registration_id);
        $paket = PaketHkc::where('member', $peserta->member)
                         ->where('kelas', $peserta->kelas)
                         ->first();

        $nominalKomponen = [
            'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
            'Uang Pangkal' => $paket->u_perlengkapan ?? 0,
            'SPP Bulanan' => $paket->u_spp ?? 0,
            'Uang Makan' => 0, // Tidak ada di PaketHkc
        ];

    } elseif ($request->registration_type === 'Hikari Kidz Daycare') {
        $peserta = RegistrationHikariKidzDaycare::findOrFail($request->registration_id);
        $paket = $peserta->paket; // akses relasi Paket

        $nominalKomponen = [
            'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
            'Uang Pangkal' => $paket->u_pangkal ?? 0,
            'SPP Bulanan' => $paket->u_spp ?? 0,
            'Uang Makan' => $paket->u_makan ?? 0,
        ];

    } elseif ($request->registration_type === 'Hikari Quran') {
        $peserta = RegistrationHikariQuran::findOrFail($request->registration_id);
        $paket = $peserta->pakethq; // akses relasi PaketHq

        $nominalKomponen = [
            'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
            'Uang Pangkal' => 0, // PaketHq tidak punya pangkal
            'SPP Bulanan' => $paket->u_spp ?? 0,
            'Uang Makan' => 0, // Tidak ada
        ];

    } else {
        abort(404, 'Tipe pendaftaran tidak valid.');
    }

    // Upload bukti transfer (satu file untuk semua komponen)


    // Simpan pembayaran per komponen
    foreach ($request->komponen as $komponen) {
        $jumlah = $nominalKomponen[$komponen] ?? 0;

        Payment::create([
            'registration_id' => $request->registration_id,
            'registration_type' => $request->registration_type,
            'komponen' => $komponen,
            'jumlah' => $jumlah,
            'bukti_transfer' =>  $validatedData['bukti_transfer'],
        ]);
    }

    return redirect()->route('payment.index')->with('success', 'Pembayaran berhasil disimpan.');
}
}