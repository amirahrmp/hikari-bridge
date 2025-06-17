@extends('layouts2.master')

@section('program_lain_user_select','active')
@section('title', 'Tagihan Kegiatan Tambahan')

@section('content')

<br><br><br>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Tagihan Kegiatan Tambahan</h2>
            <div class="form-description mb-4">
                Daftar tagihan untuk kegiatan tambahan yang diikuti anak Anda. Mohon segera melakukan pembayaran jika belum lunas.
            </div>

            <!-- Alert jika ada pesan sukses -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($tagihan->isEmpty())
                <div class="alert alert-info">Tidak ada tagihan kegiatan tambahan saat ini.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Deskripsi</th>
                                <th>Biaya</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihan as $item)
                                <tr>
                                    <td>{{ $item->kegiatan->nama_kegiatan }}</td>
                                    <td>{{ $item->kegiatan->deskripsi ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->kegiatan->biaya, 0, ',', '.') }}</td>
                                    <td>
                                        @if (strtolower($item->status_pembayaran) == 'belum dibayar')
                                            <span class="badge bg-danger text-white">Belum Lunas</span>
                                        @else
                                            <span class="badge bg-success text-white">Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection
