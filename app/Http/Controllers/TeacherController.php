<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Http\Requests\StoreteacherRequest;
use App\Http\Requests\UpdateteacherRequest;
use Illuminate\Http\Request;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $teacher = Teacher::all();
        return view('teacher.index',
                    [
                        'teacher' => $teacher
                    ]
                  );
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_card' => 'required|numeric',
            'nik' => 'required|numeric',
            'nama_teacher' => 'required',
            'tipe_pengajar' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Membuat data teacher baru
        $teacher = new Teacher();
        $teacher->id_card = $request->input('id_card');
        $teacher->nik = $request->input('nik');
        $teacher->nama_teacher = $request->input('nama_teacher');
        $teacher->tipe_pengajar = $request->input('tipe_pengajar');
        $teacher->alamat = $request->input('alamat');
        $teacher->jk = $request->input('jk');
        $teacher->telp = $request->input('telp');
        $teacher->email = $request->input('email');
        $teacher->tmp_lahir = $request->input('tmp_lahir');
        $teacher->tgl_lahir = $request->input('tgl_lahir');
        $teacher->save();

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data tenaga pengajar berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        // Redirect ke halaman teacher dengan pesan sukses
        return redirect()->route('teacher.index')->with($notification);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Mengimpor data ke dalam database
        Excel::import(new TeacherImport, $file);

        $notification = array(
            'message' => 'Data tenaga pengajar berhasil ditambahkan!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_card' => 'required|numeric',
            'nik' => 'required|numeric',
            'nama_teacher' => 'required',
            'tipe_pengajar' => 'required',
            'alamat' => 'required',
            'jk' => 'required',
            'telp' => 'required|numeric|digits_between:5,15',
            'email' => 'required|email',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required|date',
        ]);

        // Cari teacher berdasarkan ID
        $teacher = Teacher::findOrFail($id);

        // Update data teacher
        $teacher->id_card = $request->input('id_card');
        $teacher->nik = $request->input('nik');
        $teacher->nama_teacher = $request->input('nama_teacher');
        $teacher->tipe_pengajar = $request->input('tipe_pengajar');
        $teacher->alamat = $request->input('alamat');
        $teacher->jk = $request->input('jk');
        $teacher->telp = $request->input('telp');
        $teacher->email = $request->input('email');
        $teacher->tmp_lahir = $request->input('tmp_lahir');
        $teacher->tgl_lahir = $request->input('tgl_lahir');
        
        // Simpan perubahan
        $teacher->save();  // Hanya satu kali save, karena sudah memodifikasi data

        // Menampilkan notifikasi sukses
        $notification = array(
            'message' => 'Data tenaga pengajar berhasil diubah!',
            'alert-type' => 'success'
        );

        // Redirect kembali ke halaman teacher dengan pesan sukses
        return redirect()->route('teacher.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'Data tenaga pengajar berhasil dihapus!',
                'alert-type'=>'info'
            );
        return redirect()->back()->with($notification);
    }
}
