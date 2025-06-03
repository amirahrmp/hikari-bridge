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

    <div class="mb-3">
    <form method="GET" action="{{ route('absensi_daycare.riwayat') }}" class="form-inline">
        <label class="mr-2">Filter Tanggal:</label>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control mr-3">

        <label class="mr-2">atau Bulan:</label>
        <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control mr-3">

        <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
        <a href="{{ route('absensi_daycare.riwayat') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>


    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
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
            @forelse ($riwayat as $absen)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($absen->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $absen->full_name }}</td>
                    <td>{{ $absen->jam_datang }}</td>
                    <td>{{ $absen->jam_pulang ?? '-' }}</td>
                    <td>
                        @if ($absen->jam_datang && $absen->jam_pulang)
                            {{ \Carbon\Carbon::parse($absen->jam_datang)->diff(\Carbon\Carbon::parse($absen->jam_pulang))->format('%h jam %i menit') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $absen->overtime_display ?? '-' }}</td>
                    <td>Rp {{ number_format((int) $absen->denda, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>
@endsection
