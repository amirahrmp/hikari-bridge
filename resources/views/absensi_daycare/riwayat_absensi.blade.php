@extends('layouts.master')

@section('riwayat_absensi_select','active')
@section('title', 'Riwayat Absensi')

@section('content')
<div class="container">
    <h2 class="mb-4">Riwayat Absensi Daycare</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Tanggal</th>
                <th>Nama Anak</th>
                <th>Jam Datang</th>
                <th>Jam Pulang</th>
                <th>Overtime (menit)</th>
                <th>Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $absen)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($absen->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $absen->full_name }}</td>
                    <td>{{ $absen->jam_datang }}</td>
                    <td>{{ $absen->jam_pulang ?? '-' }}</td>
                    <td>{{ $absen->overtime ?? 0 }}</td>
                    <td>Rp {{ number_format((int) $absen->denda, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
