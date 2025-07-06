@extends('layouts2.master') {{-- Adjust to your customer layout --}}
@section('title', 'Tagihan SPP Anda')
@section('spp_bill_customer_select', 'active') {{-- Assuming this is the active menu item for SPP bills --}}

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
                        <h3 class="card-title">Daftar Tagihan SPP Anda</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Nama Anak</th>
                                        <th>Program</th>
                                        <th>Paket</th> {{-- Added for clarity --}}
                                        <th>Nominal SPP</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tagihanSpp as $tagihan)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::create()->month($tagihan->bulan)->format('F') }} {{ $tagihan->tahun }}</td>
                                        <td>{{ $tagihan->full_name }}</td>
                                        <td>{{ $tagihan->program }}</td> {{-- Display 'program' from SppBill --}}
                                        <td>{{ $tagihan->package_name }}</td> {{-- Display 'package_name' from SppBill --}}
                                        <td>Rp{{ number_format($tagihan->nominal_uang_spp, 0, ',', '.') }}</td>
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
                                                <a href="{{ route('spp.customer.bayar', $tagihan->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada tagihan SPP untuk Anda.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection