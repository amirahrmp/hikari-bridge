<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }} - {{ $reportPeriod }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 5px; /* Reduced margin */
        }
        .report-period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12pt;
        }
        .total-info {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-left: 5px; /* Small indent */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        th:nth-child(2), td:nth-child(2) {
            width: 25%;
        }
    </style>
</head>
<body>
    <h1>{{ $reportTitle }}</h1>
    <p class="report-period"><strong>{{ $reportPeriod }}</strong></p>

    @php
        $totalHadir = $absensiRecords->where('keterangan', 'Hadir')->count();
        $totalIzin = $absensiRecords->where('keterangan', 'Izin')->count();
        $totalAlfa = $absensiRecords->where('keterangan', 'Alfa')->count();
    @endphp

    <div class="total-info">
        <p><strong>Total Hadir:</strong> {{ $totalHadir }}</p>
        <p><strong>Total Izin:</strong> {{ $totalIzin }}</p>
        <p><strong>Total Alfa:</strong> {{ $totalAlfa }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Anak</th>
                <th>Nama Anak</th>
                <!-- <th>Program</th> -->
                <th>Tipe</th>
                <th>Keterangan</th>
                <th>Tanggal Absen</th> {{-- Added for clarity in monthly reports --}}
            </tr>
        </thead>
        <tbody>
            @forelse($absensiRecords as $record)
                <tr>
                    <td>{{ $record->id_anak }}</td>
                    <td>{{ $record->nama_anak }}</td>
                    <!-- <td>{{ $record->program }}</td> -->
                    <td>{{ $record->nama_paket }}</td>
                    <td>{{ $record->keterangan }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data absensi untuk kriteria yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>