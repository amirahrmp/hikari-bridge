<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Teacher - {{ $date }}</title>
    <style>
        .total-info {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid black;
        }

        th:nth-child(2), td:nth-child(2) {
            width: 200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <h1>Presensi Teacher - Tanggal: {{ $date }}</h1>

    <div class="total-info">
        <p><strong>Total Hadir:</strong> {{ $totalHadir }}</p>
        <p><strong>Total Izin:</strong> {{ $totalIzin }}</p>
        <p><strong>Total Alfa:</strong> {{ $totalAlfa }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Card</th>
                <th>Nama Teacher</th>
                <th>Keterangan</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensi as $item)
                <tr>
                    <td>{{ $item->id_card }}</td>
                    <td>{{ $item->nama_teacher }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ $item->waktu_masuk }}</td>
                    <td>{{ $item->waktu_keluar }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
