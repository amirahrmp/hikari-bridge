@extends('layouts.master')

@section('laporan_pemasukan_select','active')
@section('title', 'Laporan Pemasukan')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Laporan Pemasukan</h4>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Bulan</label>
            <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
        </div>
        <div class="col-md-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
        </div>
        <div class="col-md-3">
            <label>Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
        </div>
        <div class="col-md-3">
    <label>Program</label>
    <select name="program" class="form-control">
        <option value="">Semua Program</option>
        <option value="Hikari Kidz Daycare" {{ request('program') == 'Hikari Kidz Daycare' ? 'selected' : '' }}>Hikari Kidz Daycare</option>
        <option value="Hikari Kidz Club" {{ request('program') == 'Hikari Kidz Club' ? 'selected' : '' }}>Hikari Kidz Club</option>
    </select>
</div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">Filter</button>
            <a href="{{ route('laporan.pemasukan.index') }}" class="btn btn-secondary">Reset</a>
            <a href="{{ route('laporan.pemasukan.print', request()->all()) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-light">
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
            @foreach($laporan as $index => $row)
                @php $grandTotal += $row['total']; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['program'] }}</td>
                    <td>{{ $row['paket'] }}</td>
                    <td style="white-space: pre-line;">{{ $row['keterangan'] }}</td>
                    <td>Rp{{ number_format($row['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end"><strong>Total</strong></td>
                <td><strong>Rp{{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection