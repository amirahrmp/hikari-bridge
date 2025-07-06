<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiDaycare;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\RegistrationHikariKidzClub; // Tambahkan ini jika Overtime juga ada di Club
use App\Models\User;
use App\Models\OvertimeBill;
use App\Models\Paket;
use App\Models\PaketHkc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdditionalBillController extends Controller
{
    /**
     * Menampilkan daftar anak dengan potensi tagihan overtime.
     */
    public function index(Request $request)
    {
        $bulanFilter = $request->input('bulan', Carbon::now()->month);
        $tahunFilter = $request->input('tahun', Carbon::now()->year);

        $studentsWithOvertime = [];

        // Ambil semua pendaftaran Daycare yang aktif (asumsi overtime hanya untuk Daycare)
        $daycareRegistrations = RegistrationHikariKidzDaycare::all();

        foreach ($daycareRegistrations as $registration) {
            // Dapatkan semua absensi anak ini untuk bulan dan tahun yang dipilih
            $absensiRecords = AbsensiDaycare::where('id_anak', $registration->id_anak)
                ->whereYear('created_at', $tahunFilter)
                ->whereMonth('created_at', $bulanFilter)
                ->whereNotNull('jam_datang')
                ->whereNotNull('jam_pulang')
                ->get();

            $totalDendaBulanIni = 0;
            $totalOvertimeMenitBulanIni = 0;
            $jumlahHariOvertime = 0;

            $paketType = Str::of(optional($registration->paket)->tipe ?? 'fullday')->lower()->replace(' ', ''); // Ambil tipe paket dari relasi

            foreach ($absensiRecords as $absensi) {
                $jamDatang = Carbon::parse($absensi->jam_datang);
                $jamPulang = Carbon::parse($absensi->jam_pulang);

                $maksMenit = 0;
                switch ($paketType) {
                    case 'halfday':
                        $maksMenit = 310; // 08.00–13.10
                        break;
                    case 'fulldaylong':
                        $maksMenit = 670; // 08.00–19.10
                        break;
                    case 'fullday':
                    default:
                        $maksMenit = 490; // 08.00–16.10
                        break;
                }

                $durasiMenit = $jamPulang->diffInMinutes($jamDatang);
                $overtimeMenit = max(0, $durasiMenit - $maksMenit);

                // Denda per hari dihitung saat absensi disimpan, kita hanya menjumlahkannya
                $totalDendaBulanIni += $absensi->denda ?? 0;
                $totalOvertimeMenitBulanIni += $overtimeMenit;

                if ($overtimeMenit > 0) {
                    $jumlahHariOvertime++;
                }
            }

            // Cek apakah tagihan overtime sudah dibuat untuk bulan ini
            $overtimeBillExists = OvertimeBill::where('registration_id', $registration->id)
                ->where('registration_type', RegistrationHikariKidzDaycare::class)
                ->where('bulan', $bulanFilter)
                ->where('tahun', $tahunFilter)
                ->exists();

            if ($totalDendaBulanIni > 0 || $overtimeBillExists) { // Tampilkan jika ada denda atau sudah ada tagihan
                $studentsWithOvertime[] = [
                    'registration_id' => $registration->id,
                    'registration_type' => RegistrationHikariKidzDaycare::class,
                    'id_anak' => $registration->id_anak,
                    'full_name' => $registration->full_name,
                    'program' => 'Hikari Kidz Daycare', // Asumsi program ini hanya untuk daycare
                    'package_name' => optional($registration->paket)->nama_paket ?? $paketType, // Ambil nama paket atau tipe
                    'total_denda' => $totalDendaBulanIni,
                    'total_overtime_minutes' => $totalOvertimeMenitBulanIni,
                    'jumlah_hari_overtime' => $jumlahHariOvertime,
                    'bill_status' => $overtimeBillExists ? 'Sudah Dibuat' : 'Belum Dibuat',
                ];
            }
        }

        return view('additional_bills.index', compact('studentsWithOvertime', 'bulanFilter', 'tahunFilter'));
    }

    /**
     * Membuat tagihan overtime untuk satu anak atau semua anak yang memiliki denda.
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
            // Generate untuk satu anak
            if ($this->createOvertimeBill($registrationId, $registrationType, $bulan, $tahun)) {
                $count++;
            } else {
                $errors[] = "Gagal membuat tagihan untuk anak dengan ID {$registrationId} atau sudah ada.";
            }
        } else {
            // Generate untuk semua anak yang memiliki denda di bulan ini
            $daycareRegistrations = RegistrationHikariKidzDaycare::all();
            foreach ($daycareRegistrations as $registration) {
                // Periksa apakah ada denda untuk anak ini di bulan yang dipilih
                $totalDendaBulanIni = AbsensiDaycare::where('id_anak', $registration->id_anak)
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $bulan)
                    ->sum('denda');

                if ($totalDendaBulanIni > 0) {
                    if ($this->createOvertimeBill($registration->id, RegistrationHikariKidzDaycare::class, $bulan, $tahun)) {
                        $count++;
                    } else {
                        $errors[] = "Gagal membuat tagihan untuk {$registration->full_name} atau sudah ada.";
                    }
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }
        return redirect()->back()->with('success', "Berhasil membuat {$count} tagihan tambahan.");
    }

    /**
     * Fungsi privat untuk membuat satu tagihan overtime.
     */
    private function createOvertimeBill($registrationId, $registrationType, $bulan, $tahun)
    {
        // Cek apakah tagihan sudah ada untuk bulan dan tahun ini
        if (OvertimeBill::where('registration_id', $registrationId)
            ->where('registration_type', $registrationType)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists()) {
            \Log::info("Tagihan Overtime sudah ada untuk {$registrationId} ({$registrationType}) bulan {$bulan}/{$tahun}.");
            return false;
        }

        $registration = (new $registrationType)->find($registrationId);
        if (!$registration) {
            \Log::warning("Pendaftaran tidak ditemukan: ID {$registrationId}, Tipe {$registrationType}");
            return false;
        }

        $idAnak = $registration->id_anak ?? null;
        $fullName = $registration->full_name ?? $registration->name ?? 'N/A';
        $program = '';
        $userId = $registration->user_id ?? (optional($registration->user)->id);
        $packageName = null;

        switch ($registrationType) {
            case RegistrationHikariKidzDaycare::class:
                $program = 'Hikari Kidz Daycare';
                $paket = $registration->paket;
                $packageName = optional($paket)->nama_paket ?? 'N/A Paket';
                break;
            // Jika Overtime juga berlaku untuk Hikari Kidz Club, tambahkan case di sini
            case RegistrationHikariKidzClub::class:
                $program = 'Hikari Kidz Club';
                $paket = $registration->getPaketHkc();
                $packageName = (optional($paket)->member && optional($paket)->kelas) ? (optional($paket)->member . ' (' . optional($paket)->kelas . ')') : 'N/A Paket';
                break;
            default:
                \Log::error("Tipe pendaftaran tidak valid saat membuat tagihan tambahan: {$registrationType}");
                return false;
        }

        // Hitung total denda dan total overtime menit dari absensi untuk bulan dan tahun ini
        $absensiRecords = AbsensiDaycare::where('id_anak', $idAnak)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->whereNotNull('denda') // Hanya yang ada denda
            ->get();

        $totalDenda = $absensiRecords->sum('denda');
        $totalOvertimeMenit = $absensiRecords->sum('overtime'); // Kolom overtime di absensi_daycare

        if ($totalDenda > 0 && $userId !== null) {
            try {
                OvertimeBill::create([
                    'registration_id' => $registrationId,
                    'registration_type' => $registrationType,
                    'id_anak' => $idAnak,
                    'full_name' => $fullName,
                    'program' => $program,
                    'package_name' => $packageName, // Simpan nama paket di sini
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'total_overtime_minutes' => $totalOvertimeMenit,
                    'total_denda' => $totalDenda,
                    'user_id' => $userId,
                    'status' => 'belum_bayar',
                    'notes' => 'Tagihan denda overtime bulan ' . Carbon::create()->month($bulan)->format('F') . ' ' . $tahun,
                ]);
                \Log::info("Tagihan Overtime berhasil dibuat untuk {$fullName} (ID: {$registrationId}, UserID: {$userId}).");
                return true;
            } catch (\Exception $e) {
                \Log::error("GAGAL menyimpan Tagihan Overtime untuk {$fullName}. Error: " . $e->getMessage());
                return false;
            }
        } else {
            \Log::warning("Tagihan Overtime tidak dibuat untuk {$fullName}: Total Denda {$totalDenda} (harus > 0), UserID {$userId} (tidak boleh null).");
        }

        return false;
    }
}