<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
