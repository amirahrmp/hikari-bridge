<?php

namespace App\Http\Controllers;

use App\Models\JadwalKursus;
use App\Models\PesertaKursus;
use App\Models\Kursus;
use App\Models\Teacher;
use App\Http\Requests\StoreJadwalKursusRequest;
use App\Http\Requests\UpdateJadwalKursusRequest;
use Illuminate\Http\Request;

class JadwalKursusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kursus = Kursus::all();
        $teachers = Teacher::all();
        $jadwalKursus = JadwalKursus::with(['kursus','teacher','peserta'])->get();
        return view('jadwal_kursus.index', compact('jadwalKursus','kursus', 'teachers'));
    }

    public function show($id)
    {
        $jadwalKursus = JadwalKursus::with('peserta')->findOrFail($id);
        $pesertaList = PesertaKursus::all();
        return view('jadwal_kursus.show', compact('jadwalKursus', 'pesertaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kursus' => 'required|exists:kursus,id',
            'id_teacher' => 'required|exists:teacher,id',
            'hari' => 'required',
            'waktu' => 'required',
            'tipe_kursus' => 'required|in:online,offline',
        ]);

        JadwalKursus::create($validated);

        $notification = array(
            'message' => 'Jadwal kursus berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('jadwal_kursus.index')->with($notification);
    }

    public function addPeserta(Request $request, $id)
    {
        $validated = $request->validate([
            'id_peserta' => 'required|exists:peserta_kursus,id',
        ]);

        $jadwalKursus = JadwalKursus::findOrFail($id);
        $pesertaExists = $jadwalKursus->peserta()
                                   ->where('detail_jadwal_kursus.id_peserta', $request->id_peserta)
                                   ->exists();


        if ($pesertaExists) {
            $notification = array(
                'message' => 'Peserta sudah terdaftar di jadwal!',
                'alert-type' => 'error'
            );
            // Jika peserta sudah terdaftar, kirim pesan error
            return redirect()->route('jadwal_kursus.show', $id)
                            ->with($notification);
        }

        $jadwalKursus->peserta()->attach($validated['id_peserta']); // Menambahkan peserta ke jadwal kursus

        $notification = array(
            'message' => 'Peserta berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->route('jadwal_kursus.show', $id)->with($notification);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_kursus' => 'required|exists:kursus,id',
            'id_teacher' => 'required|exists:teacher,id',
            'hari' => 'required',
            'waktu' => 'required',
            'tipe_kursus' => 'required|in:online,offline',
        ]);

        $jadwalKursus = JadwalKursus::findOrFail($id);
        $jadwalKursus->update($validated);

        $notification = array(
            'message' => 'Jadwal kursus berhasil diperbarui!',
            'alert-type' => 'info'
        );
        return redirect()->route('jadwal_kursus.index')->with($notification);
    }

    public function destroy($id)
    {
        $jadwalKursus = JadwalKursus::findOrFail($id);
        $jadwalKursus->delete();
        $notification = array(
            'message' => 'Jadwal kursus berhasil dihapus!',
            'alert-type' => 'info'
        );
        return redirect()->route('jadwal_kursus.index')->with($notification);
    }

    public function removePeserta($jadwalId, $pesertaId)
    {
        $jadwalKursus = JadwalKursus::findOrFail($jadwalId);
        $jadwalKursus->peserta()->detach($pesertaId);

        $notification = array(
            'message' => 'Peserta berhasil dihapus!',
            'alert-type' => 'info'
        );

        return redirect()->route('jadwal_kursus.show', $jadwalId)->with($notification);
    }

    // view untuk peserta kursus
    public function showByEmail(Request $request)
    {
        $email = $request->input('email');
        $peserta = PesertaKursus::where('email', $email)->first();
        
        if (!$peserta) {
            return view('jadwal_kursus.peserta', [
                'message' => 'Anda belum memiliki jadwal kursus. Ayo daftar sekarang!',
            ]);
        }

        $jadwalKursus = $peserta->jadwalKursus;

        return view('jadwal_kursus.peserta', compact('peserta', 'jadwalKursus'));
    }

    // view untuk teacher
    public function showTeacherSchedule(Request $request)
    {
        $email = $request->input('email');
        $teacher = Teacher::where('email', $email)->first();

        if (!$teacher) {
            return view('jadwal_kursus.teacher', ['message' => 'Data jadwal tidak ditemukan.']);
        }

        $jadwalKursus = JadwalKursus::where('id_teacher', $teacher->id)->get();

        return view('jadwal_kursus.teacher', [
            'jadwalKursus' => $jadwalKursus,
            'teacher' => $teacher,
            'message' => null,
        ]);
    }

}
