@extends('layouts.master')

@section('riwayat_absensi_hkc_select','active')
@section('title', 'Riwayat Absensi Hikari Kidz Club')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Riwayat Absensi Hikari Kidz Club</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card p-3">
                <h2 class="mb-3">Filter Riwayat Absensi</h2>
                <form method="GET" action="{{ route('absensi_hkc.riwayat') }}" class="mb-3 d-flex align-items-center">
                    <label for="history_date" class="mr-2">Tanggal:</label>
                    <input type="date" id="history_date" name="history_date" value="{{ request('history_date') }}" class="form-control w-auto mr-2">

                    <label for="history_month" class="mr-2">Bulan:</label>
                    <input type="month" id="history_month" name="history_month" value="{{ request('history_month', \Carbon\Carbon::now()->format('Y-m')) }}" class="form-control w-auto mr-2">

                    <button type="submit" class="btn btn-secondary">Tampilkan</button>
                    <a href="{{ route('absensi_hkc.riwayat') }}" class="btn btn-info ml-2">Reset Filter</a>

                    {{-- NEW: Cetak PDF Button --}}
                    {{-- Pass current filters to the PDF export route --}}
                    <a href="{{ route('absensi_hkc.exportPdf', ['history_date' => request('history_date'), 'history_month' => request('history_month')]) }}" class="btn btn-primary ml-2" target="_blank">
                        <i class="fas fa-print"></i> Cetak PDF
                    </a>
                </form>

                {{-- Display the current filter context --}}
                @if($historyDate)
                    <h2 class="mb-3 mt-4">Daftar Absensi untuk Tanggal {{ \Carbon\Carbon::parse($historyDate)->format('d-m-Y') }}</h2>
                @elseif($historyMonth)
                    <h2 class="mb-3 mt-4">Daftar Absensi untuk Bulan {{ \Carbon\Carbon::parse($historyMonth)->isoFormat('MMMM YYYY', 'id') }}</h2>
                @else
                    <h2 class="mb-3 mt-4">Daftar Absensi untuk Hari Ini ({{ \Carbon\Carbon::now()->format('d-m-Y') }})</h2>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Anak</th>
                            <th>Nama Anak</th>
                            <!-- <th>Program</th> -->
                            <th>Tipe</th>
                            <th>Keterangan</th>
                            <th>Tanggal Absen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historicalAbsensi as $record)
                            <tr>
                                <td>{{ $record->id_anak }}</td>
                                <td>{{ $record->nama_anak }}</td>
                                <!-- <td>{{ $record->program }}</td> -->
                                <td>{{ $record->nama_paket }}</td>
                                <td>{{ $record->keterangan }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    {{-- Mengarahkan ke route index dengan ID absensi untuk mode edit --}}
                                    <a href="{{ route('absensi_hkc.index', $record->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada riwayat absensi untuk kriteria yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection