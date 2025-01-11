<?php

namespace App\Imports;

use App\Models\Kursus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KursusImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Kursus([
            'id_kursus' => $row['id_kursus'],
            'nama_kursus' => $row['nama_kursus'],
            'jenis_kursus' => $row['jenis_kursus'],
            'level' => $row['level'],
            'kategori' => $row['kategori'],
            'kelas' => $row['kelas'],
            'kapasitas' => $row['kapasitas'],
            'waktu' => $row['waktu'],
            'biaya' => $row['biaya'],
        ]);
    }
}
