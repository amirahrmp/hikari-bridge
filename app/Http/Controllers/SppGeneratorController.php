<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzClub;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariQuran;
use App\Models\SppBulanan; // Pastikan model ini sudah Anda buat
use App\Models\PaketHkc;
use Carbon\Carbon;

class SppGeneratorController extends Controller
{
    public function index()
    {
        $registrations = collect()
            ->merge(RegistrationHikariKidzClub::all())
            ->merge(RegistrationHikariKidzDaycare::all())
            ->merge(RegistrationHikariQuran::all());

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $existingSppKeys = SppBulanan::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->get()
            ->map(function ($spp) {
                return $spp->registration_id . '-' . $spp->registration_type;
            })
            ->flip();

        // Mengarahkan ke view yang lokasinya lebih sederhana
        return view('spp_generator.index', compact('registrations', 'existingSppKeys', 'bulanIni', 'tahunIni'));
    }

    public function generate(Request $request)
    {
        $request->validate(['registration_id' => 'required', 'registration_type' => 'required']);
        $this->createSppBill($request->registration_id, $request->registration_type);
        return redirect()->back()->with('success', '1 Tagihan SPP baru berhasil dibuat.');
    }

    public function generateAll()
    {
        $registrations = collect()
            ->merge(RegistrationHikariKidzClub::all())
            ->merge(RegistrationHikariKidzDaycare::all())
            ->merge(RegistrationHikariQuran::all());
        
        $count = 0;
        foreach ($registrations as $reg) {
            if ($this->createSppBill($reg->id, get_class($reg))) {
                $count++;
            }
        }
        return redirect()->back()->with('success', "$count tagihan SPP baru berhasil dibuat untuk bulan ini.");
    }

    private function createSppBill($regId, $regType)
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        if (SppBulanan::where('registration_id', $regId)->where('registration_type', $regType)->where('bulan', $bulanIni)->where('tahun', $tahunIni)->exists()) {
            return false;
        }

        $registration = (new $regType)->find($regId);
        if (!$registration) return false;

        $nominalSpp = 0;
        $program = '';
        $namaLengkap = $registration->full_name ?? $registration->name ?? 'N/A';
        
        if ($registration instanceof RegistrationHikariKidzDaycare) {
            $program = 'Hikari Kidz Daycare';
            $nominalSpp = optional($registration->paket)->u_spp ?? 0;
        } elseif ($registration instanceof RegistrationHikariKidzClub) {
            $program = 'Hikari Kidz Club';
            $paket = PaketHkc::where('member', $registration->member)->where('kelas', $registration->kelas)->first();
            $nominalSpp = optional($paket)->u_spp ?? 0;
        } elseif ($registration instanceof RegistrationHikariQuran) {
            $program = 'Hikari Quran';
            $nominalSpp = optional($registration->pakethq)->u_spp ?? 0;
        }

        if ($nominalSpp > 0) {
            SppBulanan::create([
                'registration_id' => $regId,
                'registration_type' => $regType,
                'nama_lengkap' => $namaLengkap,
                'program' => $program,
                'bulan' => $bulanIni,
                'tahun' => $tahunIni,
                'nominal' => $nominalSpp,
            ]);
            return true;
        }
        
        return false;
    }
}
