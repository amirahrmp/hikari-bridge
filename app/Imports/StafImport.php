<?php

namespace App\Imports;

use App\Models\Staf;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StafImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Staf([
            'id_card' => $row['id_card'],
            'status' => $row['status'],
            'nama_staf' => $row['nama'],
            'jabatan' => $row['jabatan'],
            'departemen' => $row['departemen'],
            'waktu_kerja' => $row['waktu_kerja'],
            'tipe_staf' => $row['tipe_staf'],
            'telp' => $row['telp'],
            'tgl_masuk_kerja' => $row['tgl_masuk_kerja'],
            'nik' => $row['nik'],
            'alamat' => $row['alamat'],
            'jk' => $row['jk'],
            'email' => $row['email'],
            'tmp_lahir' => $row['tmp_lahir'],
            'tgl_lahir' => \Carbon\Carbon::parse($row['tgl_lahir'])->format('Y-m-d'),
        ]);
    }
}
