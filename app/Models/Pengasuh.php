<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengasuh extends Model
{
    use HasFactory;
    protected $table = 'pengasuh';
    // list kolom yang bisa diisi
    protected $fillable = [
        'id',
        'id_card',
        'nik',
        'nama_pengasuh',
        'tipe_pengasuh',
        'alamat',
        'jk',
        'telp',
        'email',
        'tmp_lahir',
        'tgl_lahir'];
}
