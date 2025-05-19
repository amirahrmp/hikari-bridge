<?php

namespace App\Imports;

use App\Models\Pengasuh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengasuhImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Pengasuh([
            'id_card' => $row['id_card'],
            'nik' => $row['nik'],
            'nama_pengasuh' => $row['nama'],
            'tipe_pengasuh' => $row['tipe_pengasuh'],
            'alamat' => $row['alamat'],
            'jk' => $row['jk'],
            'telp' => $row['telp'],
            'email' => $row['email'],
            'tmp_lahir' => $row['tmp_lahir'],
            'tgl_lahir' => \Carbon\Carbon::parse($row['tgl_lahir'])->format('Y-m-d'),
        ]);
    }
}
