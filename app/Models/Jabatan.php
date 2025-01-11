<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan';
    // list kolom yang bisa diisi
    protected $fillable = ['id','nama_jabatan','gaji_pokok','transportasi','uang_makan'];

    public function staf()
    {
        return $this->hasMany(Staf::class, 'jabatan_id');
    }

}
