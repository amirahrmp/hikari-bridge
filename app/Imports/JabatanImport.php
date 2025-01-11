<?php

namespace App\Imports;

use App\Models\Jabatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JabatanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Jabatan([
            'nama_jabatan' => $row['nama_jabatan'],
            'gaji_pokok' => $row['gaji_pokok'],
            'transportasi' => $row['transportasi'],
            'uang_makan' => $row['uang_makan'],
        ]);
    }
}
