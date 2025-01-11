<table>
    <thead>
        <th>No</th>
        <th>Date</th>
        <th>Penjualan</th>
        <th>Pembelian Bahan Baku</th>
        <th>Pengeluaran</th>
        <th>Laba/Rugi</th>
    </thead>
    <tbody>
        @forelse ($reports as $report)
            <tr>    
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report['date'] }}</td>
                <td>{{ rupiah($report['revenue']) }}</td>
                <td>{{ rupiah($report['purchase']) }}</td>
                <td>{{ rupiah($report['pengeluaran']) }}</td>
                <td>{{ rupiah($report['laba_rugi']) }}</td>
            </tr>
        @empty
            <tr>
                <td>No records found</td>
            </tr>
        @endforelse

        @if ($reports)
            <tr>
                <td colspan="5" class="text-left"><strong>Total Laba/Rugi Bersih</strong></td>
                <td><strong>Rp. {{ number_format($total_laba_rugi,2) }}</strong></td>
            </tr>
        @endif
    </tbody>
</table>