@extends('layouts2.master') {{-- Sesuaikan dengan layout customer Anda --}}
@section('title', 'Tagihan Denda Overtime Anda')
@section('overtime_bill_customer_select', 'active')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">Daftar Tagihan Denda Overtime Anda</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Nama Anak</th>
                                        <th>Program</th>
                                        <th>Total Denda</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tagihanOvertime as $tagihan)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::create()->month($tagihan->bulan)->format('F') }} {{ $tagihan->tahun }}</td>
                                        <td>{{ $tagihan->full_name }}</td>
                                        <td>{{ $tagihan->package_name }}</td> {{-- Mengambil dari kolom 'package_name' --}}
                                        <td>Rp{{ number_format($tagihan->total_denda, 0, ',', '.') }}</td>
                                        <td>
                                            @if($tagihan->status == 'lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($tagihan->status == 'menunggu_verifikasi')
                                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                            @elseif($tagihan->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else {{-- belum_bayar --}}
                                                <span class="badge bg-danger">Belum Bayar</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($tagihan->status == 'belum_bayar' || $tagihan->status == 'ditolak')
                                                <a href="{{ route('overtime.customer.bayar', $tagihan->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada tagihan denda overtime untuk Anda.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection