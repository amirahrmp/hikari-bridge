<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Menggunakan font Inter */
            margin: 20px;
            color: #343a40; /* Warna teks lebih gelap */
            -webkit-print-color-adjust: exact;
            background-color: #f8f9fa; /* Latar belakang sedikit abu-abu */
        }
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px; /* Sedikit lebih banyak spasi */
            padding: 20px;
            background-color: #e9ecef; /* Latar belakang header lebih terang */
            border-radius: 8px; /* Sudut membulat */
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* Sedikit bayangan */
        }
        .report-header h4, .report-header h5 {
            margin: 5px 0;
            color: #212529; /* Warna teks header lebih kuat */
            font-weight: 600; /* Lebih tebal */
        }
        .report-header p {
            margin: 0;
            font-size: 0.95em; /* Ukuran font sedikit lebih besar */
            color: #6c757d; /* Warna teks abu-abu */
        }
        .report-header strong.text-success {
            color: #198754 !important; /* Warna hijau Bootstrap yang lebih kuat */
            font-weight: 700; /* Lebih tebal */
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            border-radius: 8px; /* Sudut membulat untuk tabel */
            overflow: hidden; /* Penting untuk sudut membulat pada tabel */
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* Bayangan tabel yang lebih jelas */
        }
        .table th,
        .table td {
            padding: 12px 15px; /* Padding lebih besar */
            vertical-align: middle; /* Tengah vertikal */
            border-top: 1px solid #e0e0e0; /* Warna border lebih lembut */
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #a7d9b7; /* Warna border bawah header lebih kuat */
            background-color: #d1e7dd; /* Hijau muda untuk header tabel */
            text-align: center;
            color: #212529; /* Warna teks header */
            font-weight: 600; /* Lebih tebal */
        }
        .table tbody tr:nth-of-type(odd) {
            background-color: #f3fcf7; /* Latar belakang hijau sangat muda untuk baris ganjil */
        }
        .table-bordered {
            border: 1px solid #e0e0e0; /* Border tabel utama */
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e0e0e0;
        }
        .text-end {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .text-success {
            color: #198754 !important; /* Warna hijau Bootstrap */
        }
        .text-bold {
            font-weight: 700 !important; /* Lebih tebal */
        }
        .total-row th {
            background-color: #e2e6ea; /* Latar belakang abu-abu untuk baris total */
            color: #212529;
            font-size: 1.1em; /* Ukuran font lebih besar */
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                background-color: #fff; /* Latar belakang putih saat dicetak */
            }
            .container-fluid {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .report-header, .table {
                box-shadow: none !important; /* Hapus bayangan saat dicetak */
                border-radius: 0 !important; /* Hapus sudut membulat saat dicetak */
            }
            .table thead th, .total-row th {
                background-color: #d1e7dd !important; /* Pastikan warna latar belakang tercetak */
            }
            .table tbody tr:nth-of-type(odd) {
                background-color: #f3fcf7 !important; /* Pastikan warna latar belakang tercetak */
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="report-header">
            <h4><b>Hikari Bridge</b></h4>
            <h5><b>Laporan Pemasukan</b></h5>
            @php
                $startDateDisplay = request('tanggal_awal', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
                $endDateDisplay = request('tanggal_akhir', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));

                try {
                    $parsedStartDate = \Carbon\Carbon::parse($startDateDisplay);
                    $parsedEndDate = \Carbon\Carbon::parse($endDateDisplay);
                    $formattedPeriod = $parsedStartDate->format('d-F-Y') . ' s/d ' . $parsedEndDate->format('d-F-Y');
                } catch (\Exception $e) {
                    $formattedPeriod = \Carbon\Carbon::now()->startOfMonth()->format('d-F-Y') . ' s/d ' . \Carbon\Carbon::now()->endOfMonth()->format('d-F-Y');
                }
            @endphp
            <p>Periode: <strong class="text-success">{{ $formattedPeriod }}</strong></p>
            @if(request('program'))
                <p>Program: <strong class="text-success">{{ request('program') }}</strong></p>
            @endif
        </div>

        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Anak</th>
                    <th>Program</th>
                    <th>Nama Paket</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @forelse ($laporan as $index => $row)
                    @php $grandTotal += $row['total']; @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $row['tanggal'] }}</td>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['program'] }}</td>
                        <td>{{ $row['paket'] }}</td>
                        <td style="white-space: pre-line;">{{ $row['keterangan'] }}</td>
                        <td class="text-end text-bold text-success">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data pemasukan untuk kriteria yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="6" class="text-end">Total Pemasukan</th>
                    <th class="text-end text-bold text-success">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        // Otomatis memicu dialog cetak saat halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
