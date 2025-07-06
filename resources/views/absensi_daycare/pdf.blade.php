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
            margin-bottom: 5px;
        }
        .report-period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12pt;
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
        /* Optional: Adjust column widths if needed */
        th:nth-child(2), td:nth-child(2) {
            width: 20%; /* Nama Anak */
        }
        th:nth-child(5), td:nth-child(5),
        th:nth-child(6), td:nth-child(6),
        th:nth-child(7), td:nth-child(7) {
            width: 15%; /* Durasi, Overtime, Denda */
        }
    </style>
</head>
<body>
    <h1>{{ $reportTitle }}</h1>
    <p class="report-period"><strong>{{ $reportPeriod }}</strong></p>

    {{-- For Daycare, we don't have "Hadir", "Izin", "Alfa" totals in the same way as HKC.
         If you need specific totals (e.g., total denda for the period), you'd calculate them here or in the controller.
         For now, we'll omit the "Total Hadir/Izin/Alfa" section. --}}

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Anak</th>
                <th>Jam Datang</th>
                <th>Jam Pulang</th>
                <th>Durasi Hadir</th>
                <th>Overtime</th>
                <th>Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensiRecords as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $record->full_name }}</td>
                    <td>{{ $record->jam_datang }}</td>
                    <td>{{ $record->jam_pulang ?? '-' }}</td>
                    <td>{{ $record->durasi_hadir ?? '-' }}</td>
                    <td>{{ $record->overtime_display ?? '-' }}</td>
                    <td>Rp {{ number_format((int) $record->denda, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data absensi untuk kriteria yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>