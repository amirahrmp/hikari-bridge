<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationHikariQuran;

class RegistrationHikariQuranController extends Controller
{
    public function create()
    {
        return view('registerquran.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'file_upload' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'parent_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'address' => 'required|string',
            'education' => 'required|in:kids,teens,dewasa', 
            'sumberinfo' => 'required|in:facebook,instagram,whatsapp,teman,kantor,spanduk,brosur,tetangga,other',
            'promotor' => 'required|string|max:255',
        ]);

        // Proses file upload
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/quran'), $filename);
            $validatedData['file_upload'] = $filename;
        }

        $sumberinfo = $request->input('sumberinfo');
        if ($sumberinfo === 'other') {
            $validatedData['sumberinfo'] = $request->input('sumberinfo_other');
        }
        
        
        RegistrationHikariQuran::create($validatedData);

        return redirect()->route('registerquran.create')->with('success', 'Data berhasil disimpan!');
    }
}
