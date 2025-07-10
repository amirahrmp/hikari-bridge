<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //untuk memvalidasi apakah username dan password adalah 
    // admin dan admin
    public function show(Request $request)
    {
        // menangkap post dari views
        if(($request->email=='admin@gmail.com') and ($request->pwd=='admin')){
            return view('awal');
        }else{
            return "gagal login";
        }
        
    }

    protected function authenticated(Request $request, $user)
{
    // Simpan role di session
    Session::put('role', $user->role);
    Session::put('loginId', $user->id);

    // Redirect berdasarkan role
    if (in_array($user->role, ['admin', 'keuangan', 'staf', 'teacher', 'daycare'])) {
        return redirect()->route('dashboard'); // dashboard admin
    } else {
        return redirect()->route('dashboard2'); // dashboard user biasa
    }
}
}
