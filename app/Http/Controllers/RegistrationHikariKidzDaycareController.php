<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzDaycare;

class RegistrationHikariKidzDaycareController extends Controller
{
    public function create()
    {
        return view('registerkidzdaycare.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'child_order' => 'required|integer',
            'siblings_count' => 'required|integer',
            'height_cm' => 'required|integer',
            'weight_kg' => 'required|integer',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_job' => 'required|string|max:255',
            'mother_job' => 'required|string|max:255',
            'father_whatsapp' => 'required|string|max:15',
            'mother_whatsapp' => 'required|string|max:15',
            'address' => 'required|string',
            'age_group' => 'required|string|max:50',
            'package_type' => 'required|string|max:50',
            'medical_history' => 'nullable|string',
            'eating_habit' => 'required|string|max:50',
            'favorite_food' => 'required|string|max:255',
            'favorite_drink' => 'required|string|max:255',
            'favorite_toy' => 'required|string|max:255',
            'specific_habits' => 'nullable|string',
            'caretaker' => 'required|string|max:255',
            'trial_agreement' => 'required|boolean',
            'trial_date' => 'nullable|date',
            'start_date' => 'required|date',
            'reason_for_choosing' => 'required|string|max:255',
            'information_source' => 'required|string|max:255',
        ]);

        RegistrationHikariKidzDaycare::create($validatedData);

        return redirect()->route('registerkidzdaycare.create')->with('success', 'Data berhasil disimpan!');
    }
}

