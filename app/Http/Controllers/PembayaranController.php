<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Paket;
use App\Models\PaketHkc;
use App\Models\PaketHq;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paket = Paket::all();
        $paket_hkc = PaketHkc::all();
        $paket_hq = PaketHq::all();

        return view('pembayaran.blade', compact('paket', 'paket_hkc', 'paket_hq'));
    }
}
    
