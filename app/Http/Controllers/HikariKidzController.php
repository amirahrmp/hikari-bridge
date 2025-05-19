<?php

namespace App\Http\Controllers;

use App\Models\HikariKidz;
use App\Models\Paket;
use App\Http\Requests\StoreHikariKidzRequest;
use App\Http\Requests\UpdateHikariKidzRequest;
use Attribute;
use Illuminate\Http\Request;

class HikariKidzController extends Controller
{
    
    public function index()
    {
        $pkt = Paket::all();
        $hikari_kidz = HikariKidz::with('paket')->get();
        return view('hikari_kidz.index', compact('hikari_kidz', 'pkt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hikari_kidz' => 'required|unique:hikari_kidz,id_hikari_kidz',
            'nama_hikari_kidz' => 'required',
            'jenis_hikari_kidz' => 'required',
            'id_paket' => 'required|exists:paket,id',
            'kelas' => 'required',
        ]);

        HikariKidz::create($validated);

        $notification = array(
            'message' => 'Hikari Kidz berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('hikari_kidz.index')->with($notification);

        // $paket = Paket::findOrFail($request->id_paket);

        // HikariKidz::create([
        //     'id_hikari_kidz' => $request->id_hikari_kidz,
        //     'nama_hikari_kidz' => $request->nama_hikari_kidz,
        //     'jenis_hikari_kidz' => $request->jenis_hikari_kidz,
        //     'id_paket' => $paket->id,
        //     'kelas' => $request->kelas,
        // ]);
        
        // return redirect()->route('hikari_kidz.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new HikariKidzImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_hikari_kidz' => 'required|unique:hikari_kidz,id_hikari_kidz',
            'nama_hikari_kidz' => 'required',
            'jenis_hikari_kidz' => 'required',
            'id_paket' => 'required|exists:paket,id',
            'kelas' => 'required',
        ]);

        $hikari_kidz = HikariKidz::findOrFail($id);
        // $paket = Paket::findOrFail($request->id_paket);
        $hikari_kidz->update(attributes: $validated);

        $notification = array(
            'message' => 'Hikari Kidz berhasil diperbarui!',
            'alert-type' => 'info'
        );
        return redirect()->route('hikari_kidz.index')->with($notification);

        // $hikari_kidz->update([
        //     'id_hikari_kidz' => $request->id_hikari_kidz,
        //     'nama_hikari_kidz' => $request->nama_hikari_kidz,
        //     'jenis_hikari_kidz' => $request->jenis_hikari_kidz,
        //     'id_paket' => $paket->id,
        //     'kelas' => $request->kelas, 
        // ]);

        // return redirect()->route('hikari_kidz.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $hikari_kidz = HikariKidz::findOrFail($id);
        $hikari_kidz->delete();

        return redirect()->back()->with('info', 'Data berhasil dihapus!');
    }

    // public function show(Request $request)
    // {
    //     $hikari_kidzList = HikariKidz::with('paket')->get();
    //     $pesertaList = PesertaHikariKidz::all();
    //     $peserta = collect();

    //     if ($request->has('id_hikari_kidz') && !empty($request->id_hikari_kidz)) {
    //         $hikari_kidz = HikariKidz::with('pesertaHikariKidz')->find($request->id_hikari_kidz);
    //         $peserta = $hikari_kidz ? $hikari_kidz->pesertaHikariKidz : collect();
    //     }

    //     return view('detail_hikari_kidz.index', compact('hikari_kidzList', 'peserta', 'pesertaList'));
    // }
}