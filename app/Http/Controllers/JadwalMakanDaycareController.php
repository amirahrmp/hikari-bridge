<?php

namespace App\Http\Controllers;

use App\Models\JadwalMakanDaycare;
use App\Http\Requests\StoreJadwalMakanDaycareRequest;
use App\Http\Requests\UpdateJadwalMakanDaycareRequest;

class JadwalMakanDaycareController extends Controller
{
    public function index()
    {
        $jadwalMakanDaycare = JadwalMakanDaycare::all();
        return view('jadwal_makan_daycare.index', [
            'jadwal_makan_daycare' => $jadwalMakanDaycare
        ]);
    }

    public function store(StoreJadwalMakanDaycareRequest $request)
    {
        $request->validate([
            'hari' => 'required',
            'snack_pagi' => 'required',
            'makan_siang' => 'required',
            'snack_sore' => 'required',
        ]);

        $jadwal = new JadwalMakanDaycare();
        $jadwal->hari = $request->input('hari');
        $jadwal->snack_pagi = $request->input('snack_pagi');
        $jadwal->makan_siang = $request->input('makan_siang');
        $jadwal->snack_sore = $request->input('snack_sore');
        $jadwal->save();

        $notification = [
            'message' => 'Data Jadwal Makan Daycare berhasil ditambahkan!',
            'alert-type' => 'success'
        ];

        return redirect()->route('jadwal_makan_daycare.index')->with($notification);
    }

    public function update(UpdateJadwalMakanDaycareRequest $request, JadwalMakanDaycare $jadwal_makan_daycare)
    {
        $request->validate([
            'hari' => 'required',
            'snack_pagi' => 'required',
            'makan_siang' => 'required',
            'snack_sore' => 'required',
        ]);

        $jadwal_makan_daycare->hari = $request->hari;
        $jadwal_makan_daycare->snack_pagi = $request->snack_pagi;
        $jadwal_makan_daycare->makan_siang = $request->makan_siang;
        $jadwal_makan_daycare->snack_sore = $request->snack_sore;
        $jadwal_makan_daycare->save();

        $notification = [
            'message' => 'Data Jadwal Makan Daycare berhasil diubah!',
            'alert-type' => 'success'
        ];

        return redirect()->route('jadwal_makan_daycare.index')->with($notification);
    }

    public function destroy($id)
    {
        $jadwal = JadwalMakanDaycare::findOrFail($id);
        $jadwal->delete();

        $notification = [
            'message' => 'Data Jadwal Makan Daycare berhasil dihapus!',
            'alert-type' => 'info'
        ];

        return redirect()->back()->with($notification);
    }

    public function userView()
{
    $jadwalMakanDaycare = JadwalMakanDaycare::all();
    return view('jadwal_makan_daycare_user', [
        'jadwal_makan_daycare_user' => $jadwalMakanDaycare
    ]);
}
}
