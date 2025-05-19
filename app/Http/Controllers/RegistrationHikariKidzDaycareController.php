<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzDaycare;
use App\Models\Paket;

class RegistrationHikariKidzDaycareController extends Controller
{
    public function create()
    {
        $paket = Paket::all();
        return view('registerkidzdaycare.create ',['paket' => $paket]);
    }

    public function store(Request $request)
    {   
        $validatedData = $request->validate([
            'full_name' => 'nullable|string|max:255', 
            'nickname' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'file_upload' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'child_order' => 'nullable|integer',
            'siblings_count' => 'nullable|integer',
            'height_cm' => 'nullable|integer',
            'weight_kg' => 'nullable|integer',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:255',
            'mother_job' => 'nullable|string|max:255',
            'father_whatsapp' => 'nullable|string|max:15',
            'mother_whatsapp' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'age_group' => 'nullable|string|max:50',
            'package_type' => 'nullable|string|max:50',
            'medical_history' => 'nullable|string',
            'eating_habit' => 'nullable|string|max:50',
            'favorite_food' => 'nullable|string|max:255',
            'favorite_drink' => 'nullable|string|max:255',
            'favorite_toy' => 'nullable|string|max:255',
            'specific_habits' => 'nullable|string',
            'caretaker' => 'nullable|array',
            'trial_agreement' => 'nullable|string|max:255',
            'trial_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'reason_for_choosing' => 'nullable|array',
            'information_source' => 'nullable|string|max:255',
    
        ]);

        // Proses file upload
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/daycare'), $filename);
            $validatedData['file_upload'] = $filename;
        }
        if ($request->package_type) {
            $paket = Paket::find($request->package_type); // Menemukan paket berdasarkan ID
            if ($paket) {
                $validatedData['package_type'] = $paket->nama_paket; // Menyimpan nama paket yang dipilih
                // Menghitung total bayar
                $total_bayar =  $paket->u_pendaftaran + 
                                $paket->u_pangkal + 
                                $paket->u_kegiatan + 
                                $paket->u_spp + 
                                $paket->u_makan;
                $validatedData['total_bayar'] = $total_bayar; // Menyimpan total bayar ke validatedData
            }
        }

        $information_source = $request->input('information_source');
        if ($information_source[0] == 'other') {
            $validatedData['information_source'] = $request->input('information_source_other');
        }
        
        $caretaker = $request->input('caretaker');
        if($caretaker[0] == "other"){
            $validatedData['caretaker'] = $request->input('caretaker_other');  
        } 
        $validatedData['caretaker'] = json_encode($validatedData['caretaker']);
        $validatedData['reason_for_choosing'] = json_encode($validatedData['reason_for_choosing']);
        
        RegistrationHikariKidzDaycare::create($validatedData);

        return redirect()->route('registerkidzdaycare.create')->with('success', 'Data berhasil disimpan!');
    }
}

