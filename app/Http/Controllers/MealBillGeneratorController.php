<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariKidzClub;
use App\Models\Paket;
use App\Models\PaketHkc;
use App\Models\MealBill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MealBillGeneratorController extends Controller
{
    /**
     * Menampilkan daftar anak dengan potensi tagihan uang makan.
     */
    public function index(Request $request)
    {
        $bulanFilter = $request->input('bulan', Carbon::now()->month);
        $tahunFilter = $request->input('tahun', Carbon::now()->year);

        $registrationsForMeal = collect();

        // Ambil pendaftaran Hikari Kidz Daycare
        $daycareRegistrations = RegistrationHikariKidzDaycare::whereHas('paket', function ($query) {
            $query->where('u_makan', '>', 0);
        })->get();
        $registrationsForMeal = $registrationsForMeal->merge($daycareRegistrations);

        // Ambil pendaftaran Hikari Kidz Club
        $clubRegistrations = RegistrationHikariKidzClub::all();
        $filteredClubRegistrations = $clubRegistrations->filter(function ($reg) {
            $paketHkc = $reg->getPaketHkc();
            return $paketHkc && $paketHkc->u_makan > 0;
        });
        $registrationsForMeal = $registrationsForMeal->merge($filteredClubRegistrations);

        // Dapatkan kunci tagihan uang makan yang sudah ada untuk bulan dan tahun ini
        $existingMealKeys = MealBill::where('bulan', $bulanFilter)
            ->where('tahun', $tahunFilter)
            ->get()
            ->map(function ($meal) {
                return $meal->registration_id . '-' . $meal->registration_type;
            })
            ->flip();

        // Pass 'registrationsForMeal' as 'studentsWithMealBills' to match the view's expectation for the loop variable
        $studentsWithMealBills = $registrationsForMeal->map(function ($reg) {
        $packageName = null;
        $nominalUangMakan = 0;
        $program = null;

        if ($reg instanceof RegistrationHikariKidzDaycare) {
            $program = 'Hikari Kidz Daycare';
            $packageName = $reg->paket->nama_paket ?? 'Tidak Ada Paket';
            $nominalUangMakan = $reg->paket->u_makan ?? 0;
        } elseif ($reg instanceof RegistrationHikariKidzClub) {
            $program = 'Hikari Kidz Club';
            $paketHkc = $reg->getPaketHkc();
            $packageName = $paketHkc ? $paketHkc->member . ' (' . $paketHkc->kelas . ')' : 'Tidak Ada Paket';
            $nominalUangMakan = $paketHkc->u_makan ?? 0;
        }

        return [
            'registration_id' => $reg->id,
            'registration_type' => get_class($reg),
            'full_name' => $reg->full_name ?? $reg->name ?? '-',
            'program' => $program,
            'package_name' => $packageName,
            'nominal_uang_makan' => $nominalUangMakan,
        ];
    });
        return view('meal_generator.index', compact('studentsWithMealBills', 'existingMealKeys', 'bulanFilter', 'tahunFilter'));
    }

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
            // Generate untuk satu anak
            if ($this->createMealBill($registrationId, $registrationType, $bulan, $tahun)) {
                $count++;
            } else {
                $errors[] = "Gagal membuat tagihan uang makan untuk anak dengan ID {$registrationId} atau tagihan sudah ada.";
            }
        } else {
            // Generate untuk semua anak yang memiliki nominal uang makan di paketnya
            $registrationsToProcess = collect();

            $daycareRegistrations = RegistrationHikariKidzDaycare::whereHas('paket', function ($query) {
                $query->where('u_makan', '>', 0);
            })->get();
            $registrationsToProcess = $registrationsToProcess->merge($daycareRegistrations);

            $clubRegistrations = RegistrationHikariKidzClub::all();
            $filteredClubRegistrations = $clubRegistrations->filter(function ($reg) {
                $paketHkc = $reg->getPaketHkc();
                return $paketHkc && $paketHkc->u_makan > 0;
            });
            $registrationsToProcess = $registrationsToProcess->merge($filteredClubRegistrations);

            foreach ($registrationsToProcess as $registration) {
                if ($this->createMealBill($registration->id, get_class($registration), $bulan, $tahun)) {
                    $count++;
                } else {
                    $errors[] = "Gagal membuat tagihan uang makan ";
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }
        return redirect()->back()->with('success', "Berhasil membuat {$count} tagihan uang makan baru.");
    }

    /**
     * Fungsi privat untuk membuat satu tagihan uang makan.
     */
    private function createMealBill($regId, $regType, $bulan, $tahun)
    {
        // Cek apakah tagihan sudah ada untuk bulan dan tahun ini
        if (MealBill::where('registration_id', $regId)
            ->where('registration_type', $regType)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists()) {
            Log::info("Tagihan Uang Makan sudah ada untuk {$regId} ({$regType}) bulan {$bulan}/{$tahun}.");
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
        $nominalUangMakan = 0;

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

        // Dapatkan nama paket spesifik
        if ($paketModel) {
            if (isset($paketModel->nama_paket)) {
                $packageName = $paketModel->nama_paket;
            } elseif (isset($paketModel->member) && isset($paketModel->kelas)) {
                $packageName = $paketModel->member . ' (' . $paketModel->kelas . ')';
            }
        }
        $packageName = $packageName ?? 'N/A Paket';

        $nominalUangMakan = optional($paketModel)->u_makan ?? 0;

        if ($nominalUangMakan > 0 && $userId !== null) {
            try {
                MealBill::create([
                    'registration_id' => $regId,
                    'registration_type' => $regType,
                    'id_anak' => $idAnak,
                    'full_name' => $fullName,
                    'program' => $program,
                    'package_name' => $packageName, // <-- INI YANG AKAN TERISI NAMA PAKET
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nominal_uang_makan' => $nominalUangMakan,
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
            \Log::warning("Tagihan Uang Makan tidak dibuat untuk {$fullName}: Nominal {$nominalUangMakan} (harus > 0), UserID {$userId} (tidak boleh null).");
        }

        return false;
    }
}