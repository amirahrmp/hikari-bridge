<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Per Produk</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h1>Laporan Penjualan Per Produk</h1>

    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Terjual</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesReports as $report)
            <tr>
                <td>{{ $report['item']->nama_item }}</td>
            	<td>{{ $report['total_sales'] }}</td>
                <td>{{ rupiah($report['total_revenue']) }}</td>
            </tr>
            @endforeach

			@if ($salesReports)
                <tr>
                    <td colspan="2" class="text-left"><strong>Grand Total</strong></td>
                    <td><strong>Rp. {{ number_format($grandTotalRevenue,2) }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
