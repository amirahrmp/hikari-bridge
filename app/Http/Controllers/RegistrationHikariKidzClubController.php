<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzClub;
use App\Models\PaketHkc;
use App\Models\PesertaHikariKidz;
use Illuminate\Support\Facades\Auth;

class RegistrationHikariKidzClubController extends Controller
{
    public function create()
    {
        $paket_hkc = PaketHkc::all();
        return view('registerkidzclub.create', compact('paket_hkc'));
    }

    public function index()
    {
        $registrasi = RegistrationHikariKidzClub::where('user_id', Auth::id())->get();
        return view('registerkidzclub.index', compact('registrasi'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'file_upload' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'parent_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'address' => 'required|string',
            'agama' => 'required|in:islam,kristen,hindu,budha,konghucu',
            'nonmuslim' => 'nullable|in:paket1,paket2',
            'member' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'information_source' => 'nullable|string|max:255',
            'information_source_other' => 'nullable|required_if:information_source,other|string|max:255',
            'promotor' => 'required|string|max:255',
        ]);

        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kidzclub'), $filename);
            $validatedData['file_upload'] = $filename;
        }

        $paket_hkc = PaketHkc::where('member', $request->member)
                             ->where('kelas', $request->kelas)
                             ->first();

        if ($paket_hkc) {
            $validatedData['member'] = $paket_hkc->member;
            $validatedData['kelas'] = $paket_hkc->kelas;
            $validatedData['total_bayar'] =
                ($paket_hkc->u_pendaftaran ?? 0) +
                ($paket_hkc->u_perlengkapan ?? 0) +
                ($paket_hkc->u_sarana ?? 0) +
                ($paket_hkc->u_spp ?? 0);
        }

        if ($request->information_source === 'other') {
            $validatedData['information_source'] = $request->information_source_other;
        }
        unset($validatedData['information_source_other']);

        $peserta = PesertaHikariKidz::where('full_name', $validatedData['full_name'])
            ->where('birth_date', $validatedData['birth_date'])
            ->where('parent_name', $validatedData['parent_name'])
            ->first();

        if (!$peserta) {
            $lastIdAnak = PesertaHikariKidz::max('id_anak');
            $newIdAnak = $lastIdAnak ? $lastIdAnak + 1 : 1;

            $peserta = PesertaHikariKidz::create([
                'id_anak' => $newIdAnak,
                
                'full_name' => $validatedData['full_name'],
                'nickname' => $validatedData['nickname'],
                'birth_date' => $validatedData['birth_date'],
                'parent_name' => $validatedData['parent_name'],
                'address' => $validatedData['address'],
                'whatsapp_number' => $validatedData['whatsapp_number'],
                'tipe' => 'HKC',
                'file_upload' => $validatedData['file_upload'],
            ]);
        }

        $registration = new RegistrationHikariKidzClub($validatedData);
        $registration->id_anak = $peserta->id_anak;
        $registration->user_id = Auth::id();
        $registration->save();

        return redirect()->route('registerkidzclub.create')->with('success', 'Data berhasil disimpan!');
    }
}
