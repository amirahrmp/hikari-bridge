<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Akses extends Model
{
    use HasFactory;

    // untuk mendapatkan kelompok user berdasarkan idnya
    public static function getGrupUser($id)
    {
        // query untuk mendapatkan kelompok
        $sql = "SELECT role
                FROM users WHERE id = ?";
        $role = DB::select($sql, [$id]);
        return $role;
    }
}
