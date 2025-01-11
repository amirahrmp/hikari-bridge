<?php

namespace App\Imports;

use App\Models\PesertaKursus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PesertaKursusImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new PesertaKursus([
            'id_peserta' => $row['id_peserta'],
            'nama_peserta' => $row['nama'],
            'nama_ortu' => $row['nama_ortu'],
            'alamat' => $row['alamat'],
            'jk' => $row['jk'],
            'telp' => $row['telp'],
            'email' => $row['email'],
            'tgl_lahir' => \Carbon\Carbon::parse($row['tgl_lahir'])->format('Y-m-d'),
        ]);
    }
}
