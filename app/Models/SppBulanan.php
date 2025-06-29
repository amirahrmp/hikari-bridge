<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class SppBulanan extends Model
    {
        use HasFactory;

        protected $table = 'spp_bulanan';

        protected $fillable = [
            'registration_id',
            'registration_type',
            'nama_lengkap',
            'program',
            'bulan',
            'tahun',
            'nominal',
            'status',
        ];
    }
    