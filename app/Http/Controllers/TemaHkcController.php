<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemaHkc;
use Carbon\Carbon;

class TemaHkcController extends Controller
{
    public function index()
{
    $bulanOrder = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $temaHkc = TemaHkc::all()->sort(function ($a, $b) use ($bulanOrder) {
        // Urutkan berdasarkan tahun_ajaran ASC
        if ($a->tahun_ajaran == $b->tahun_ajaran) {
            // Jika tahun sama, urutkan berdasarkan index bulan
            return array_search($a->bulan, $bulanOrder) - array_search($b->bulan, $bulanOrder);
        }

        return $a->tahun_ajaran <=> $b->tahun_ajaran;
    });

    return view('tema_hkc.index', compact('temaHkc'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'tema' => 'required|string|max:255',
            'bulan' => 'required|string',  // format "YYYY-MM"
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $tema = new TemaHkc();
        $tema->tema = $validated['tema'];
        $tema->bulan = $validated['bulan'];
        $tema->tahun_ajaran = $validated['tahun_ajaran'];

        $tema->save();

        return redirect()->route('tema_hkc.index')->with('success', 'Tema berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tema' => 'required|string|max:255',
            'bulan' => 'required|string',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $tema = TemaHkc::findOrFail($id);
        $tema->tema = $validated['tema'];
        $tema->bulan = $validated['bulan'];
        $tema->tahun_ajaran = $validated['tahun_ajaran'];

        $tema->save();

        return redirect()->route('tema_hkc.index')->with('success', 'Tema berhasil diperbarui');
    }

    public function destroy($id)
    {
        TemaHkc::findOrFail($id)->delete();
        return redirect()->route('tema_hkc.index')->with('success', 'Tema berhasil dihapus');
    }

    public function boot()
{
    Carbon::setLocale('id');
}
}
