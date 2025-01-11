<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Users::all();
        return view('users.index',
                    [
                        'users' => $users
                    ]
                  );
    }

    public function updateRole(Request $request, $id)
    {
        // Validasi role
        $request->validate([
            'role' => 'required|in:admin,keuangan,staf,daycare,teacher,umum',
        ]);

        // Cari user berdasarkan ID
        $users = Users::findOrFail($id);

        // Update role
        $users->role = $request->input('role');
        $users->save();
        
        if($users->save())
        {
            $notification=array
            (
                'message'=>'Data role user berhasil diubah!',
                'alert-type'=>'success'
            );
            // Redirect kembali ke halaman dengan pesan sukses
            return redirect()->route('users.index')->with($notification);
        }
        else {
            $notification=array
            (
                'message'=>'Data role user gagal diubah!',
                'alert-type'=>'error'
            );
            // Redirect kembali ke halaman dengan pesan sukses
            return redirect()->route('users.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Users $id)
    {
        $id->delete();
        $notification=array
            (
                'message'=>'User berhasil dihapus!',
                'alert-type'=>'success'
            );
        return redirect()->back()->with($notification);
    }
}
