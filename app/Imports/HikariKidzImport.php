<?php

namespace App\Imports;

use App\Models\HikariKidz;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HikariKidzImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new HikariKidz([
            'id_hikari_kidz' => $row['id_hikari_kidz'],
            'nama_hikari_kidz' => $row['nama_hikari_kidz'],
            'jenis_hikari_kidz' => $row['jenis_hikari_kidz'],
            'level' => $row['level'],
            'kategori' => $row['kategori'],
            'kelas' => $row['kelas'],
            'kapasitas' => $row['kapasitas'],
            'waktu' => $row['waktu'],
            'biaya' => $row['biaya'],
        ]);
    }
}
