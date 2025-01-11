<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Teacher([
            'id_card' => $row['id_card'],
            'nik' => $row['nik'],
            'nama_teacher' => $row['nama'],
            'tipe_pengajar' => $row['tipe_pengajar'],
            'alamat' => $row['alamat'],
            'jk' => $row['jk'],
            'telp' => $row['telp'],
            'email' => $row['email'],
            'tmp_lahir' => $row['tmp_lahir'],
            'tgl_lahir' => \Carbon\Carbon::parse($row['tgl_lahir'])->format('Y-m-d'),
        ]);
    }
}
