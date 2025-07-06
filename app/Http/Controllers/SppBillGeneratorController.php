<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariKidzClub;
use App\Models\Paket;
use App\Models\PaketHkc;
use App\Models\SppBill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class SppBillGeneratorController extends Controller
{
    /**
     * Menampilkan halaman generator tagihan SPP bulanan (Admin View).
     * Mengambil semua pendaftaran yang relevan dan memeriksa tagihan yang sudah ada.
     */
    public function index(Request $request)
    {
        $bulanFilter = $request->input('bulan', Carbon::now()->month);
        $tahunFilter = $request->input('tahun', Carbon::now()->year);

        $registrationsForSpp = collect();

        // Ambil pendaftaran Hikari Kidz Daycare yang memiliki nominal SPP di paketnya
        $daycareRegistrations = RegistrationHikariKidzDaycare::whereHas('paket', function ($query) {
            $query->where('u_spp', '>', 0);
        })->get();
        $registrationsForSpp = $registrationsForSpp->merge($daycareRegistrations);

        // Ambil pendaftaran Hikari Kidz Club yang memiliki nominal SPP di paketnya
        $clubRegistrations = RegistrationHikariKidzClub::all();
        $filteredClubRegistrations = $clubRegistrations->filter(function ($reg) {
            $paketHkc = $reg->getPaketHkc(); // Asumsi method ini ada di RegistrationHikariKidzClub
            return $paketHkc && $paketHkc->u_spp > 0;
        });
        $registrationsForSpp = $registrationsForSpp->merge($filteredClubRegistrations);


        // Dapatkan kunci tagihan SPP yang sudah ada untuk bulan dan tahun ini
        $existingSppKeys = SppBill::where('bulan', $bulanFilter)
            ->where('tahun', $tahunFilter)
            ->get()
            ->map(function ($spp) {
                return $spp->registration_id . '-' . $spp->registration_type;
            })
            ->flip();

        // Memformat data untuk view agar lebih mudah diakses, konsisten dengan MealBillGenerator
        $studentsWithSppBills = $registrationsForSpp->map(function ($reg) {
            $packageName = null;
            $nominalSpp = 0;
            $program = null;

                    if ($reg instanceof RegistrationHikariKidzDaycare) {
            $program = 'Hikari Kidz Daycare';
            $packageName = $reg->paket->nama_paket ?? 'Tidak Ada Paket';
            $nominalSpp = $reg->paket->u_spp ?? 0;
        } elseif ($reg instanceof RegistrationHikariKidzClub) {
            $program = 'Hikari Kidz Club';
            $paketHkc = $reg->getPaketHkc();
            $packageName = $paketHkc ? $paketHkc->member . ' (' . $paketHkc->kelas . ')' : 'Tidak Ada Paket';
            $nominalSpp = $paketHkc->u_spp ?? 0;
        }

            return [
                'registration_id' => $reg->id,
                'registration_type' => get_class($reg),
                'full_name' => $reg->full_name ?? $reg->name ?? '-',
                'program' => $program,
                'package_name' => $packageName,
                'nominal_uang_spp' => $nominalSpp,
            ];
        });

        // Sekarang kita passing $studentsWithSppBills ke view, bukan $registrationsForSpp
        return view('spp_generator.index', compact('studentsWithSppBills', 'existingSppKeys', 'bulanFilter', 'tahunFilter'));
    }

    /**
     * Membuat satu tagihan SPP berdasarkan ID dan tipe pendaftaran yang diberikan,
     * atau untuk semua pendaftaran yang relevan jika tidak ada ID spesifik.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'registration_id' => 'nullable|integer',
            'registration_type' => 'nullable|string',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $registrationId = $request->registration_id;
        $registrationType = $request->registration_type;

        $count = 0;
        $errors = [];

        if ($registrationId && $registrationType) {
            // Generate untuk satu pendaftaran
            if ($this->createSppBill($registrationId, $registrationType, $bulan, $tahun)) {
                $count++;
            } else {
                $errors[] = "Gagal membuat tagihan SPP untuk pendaftaran ID {$registrationId} (Tipe: {$registrationType}) atau tagihan sudah ada.";
            }
        } else {
            // Generate untuk semua pendaftaran yang membutuhkan SPP
            $registrationsToProcess = collect();

            $daycareRegistrations = RegistrationHikariKidzDaycare::whereHas('paket', function ($query) {
                $query->where('u_spp', '>', 0);
            })->get();
            $registrationsToProcess = $registrationsToProcess->merge($daycareRegistrations);

            $clubRegistrations = RegistrationHikariKidzClub::all();
            $filteredClubRegistrations = $clubRegistrations->filter(function ($reg) {
                $paketHkc = $reg->getPaketHkc();
                return $paketHkc && $paketHkc->u_spp > 0;
            });
            $registrationsToProcess = $registrationsToProcess->merge($filteredClubRegistrations);


            foreach ($registrationsToProcess as $reg) {
                if ($this->createSppBill($reg->id, get_class($reg), $bulan, $tahun)) {
                    $count++;
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }
        return redirect()->back()->with('success', "$count tagihan SPP baru berhasil dibuat.");
    }

    /**
     * Fungsi privat untuk membuat satu tagihan SPP.
     * Struktur mirip dengan createMealBill.
     */
    private function createSppBill($regId, $regType, $bulan, $tahun)
    {
        // Cek apakah tagihan sudah ada untuk bulan dan tahun ini
        if (SppBill::where('registration_id', $regId)
                      ->where('registration_type', $regType)
                      ->where('bulan', $bulan)
                      ->where('tahun', $tahun)
                      ->exists()) {
            Log::info("Tagihan SPP sudah ada untuk {$regId} ({$regType}) bulan {$bulan}/{$tahun}.");
            return false;
        }

        $registration = (new $regType)->find($regId);
        if (!$registration) {
            Log::warning("Pendaftaran tidak ditemukan: ID {$regId}, Tipe {$regType}");
            return false;
        }

        $idAnak = $registration->id_anak ?? null;
        $fullName = $registration->full_name ?? $registration->name ?? 'N/A';
        $program = '';
        $packageName = null;
        $userId = $registration->user_id ?? (optional($registration->user)->id);
        $nominalSpp = 0;
       
                $paketModel = null;
        switch ($regType) {
            case RegistrationHikariKidzDaycare::class:
                $program = 'Hikari Kidz Daycare';
                $paketModel = $registration->paket;
                break;
            case RegistrationHikariKidzClub::class:
                $program = 'Hikari Kidz Club';
                $paketModel = $registration->getPaketHkc();
                break;
            default:
                Log::error("Tipe pendaftaran tidak valid saat membuat tagihan uang makan: {$regType}");
                return false;
        }

        if ($paketModel) {
            if (isset($paketModel->nama_paket)) {
                $packageName = $paketModel->nama_paket;
            } elseif (isset($paketModel->member) && isset($paketModel->kelas)) {
                $packageName = $paketModel->member . ' (' . $paketModel->kelas . ')';
            }
        }
        $packageName = $packageName ?? 'N/A Paket'; // Fallback jika nama paket tetap null

        $nominalSpp = optional($paketModel)->u_spp ?? 0; // Ambil nominal SPP dari paket

        // **KONDISI KRITIS UNTUK PEMBUATAN TAGIHAN**
        // Jika nominal > 0 DAN User ID ada (tidak null)
        if ($nominalSpp > 0 && $userId !== null) {
            try {
                SppBill::create([
                    'registration_id' => $regId,
                    'registration_type' => $regType,
                    'id_anak' => $idAnak,
                    'full_name' => $fullName,
                    'program' => $program,
                    'package_name' => $packageName, // <-- INI YANG AKAN TERISI NAMA PAKET
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nominal_uang_spp' => $nominalSpp,
                    'user_id' => $userId,
                    'status' => 'belum_bayar',
                    'notes' => 'Tagihan Uang Makan bulan ' . Carbon::create()->month($bulan)->format('F') . ' ' . $tahun,
                ]);
                \Log::info("Tagihan Uang Makan berhasil dibuat untuk {$fullName} (ID: {$regId}, UserID: {$userId}).");
                return true;
            } catch (\Exception $e) {
                \Log::error("GAGAL menyimpan Tagihan Uang Makan untuk {$fullName}. Error: " . $e->getMessage());
                return false;
            }
        } else {
            \Log::warning("Tagihan SPP tidak dibuat untuk {$fullName}: Nominal {$nominalSpp} (harus > 0), UserID {$userId} (tidak boleh null).");
        }

        return false;
    }
}