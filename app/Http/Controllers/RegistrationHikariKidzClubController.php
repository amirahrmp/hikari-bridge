<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariKidzClub;

class RegistrationHikariKidzClubController extends Controller
{
    public function create()
    {
        return view('registerkidzclub.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        RegistrationHikariKidzClub::create($validatedData);

        return redirect()->route('registerkidzclub.create')->with('success', 'Data berhasil disimpan!');
    }
}
