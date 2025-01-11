<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Coa extends Model
{
    use HasFactory;
    protected $table = 'coa';
    // list kolom yang bisa diisi
    protected $fillable = ['id','kode_akun','nama_akun','header_akun'];
}
