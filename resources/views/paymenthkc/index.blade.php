@extends('layouts2.master')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white py-3">
            <h2 class="mb-0 text-center"><i class="fas fa-history me-2"></i> Riwayat Pembayaran</h2>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($payments->isEmpty())
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-info-circle me-2"></i> Belum ada data pembayaran yang tercatat.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th style="width: 5%;">No.</th>
                                    <th style="width: 15%;">Nama Anak</th>
                                    <th style="width: 20%;">Program</th> {{-- Lebar sedikit diperluas untuk nama program yang lebih detail --}}
                                    <th style="width: 20%;">Komponen Pembayaran</th>
                                    <th style="width: 10%;">Total Pembayaran</th>
                                    <th style="width: 10%;">Bukti Transfer</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Kuitansi</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                            <tr class="table-row-hover">
                                <td>{{ $loop->count - $loop->index }}</td>

                                <td>
                                    {{-- AKSES NAMA ANAK DARI AKSESOR getPesertaAttribute --}}
                                    {{ $payment->peserta->full_name ?? ($payment->peserta->name ?? '-') }}
                                </td>

                                <td>
                                    {{-- Menggunakan accessor getProgramDisplayAttribute yang sudah lengkap --}}
                                    {{ $payment->program_display }}
                                </td>
                                <td>
                                    @if ($payment->sppBulanan)
                                        <ul class="list-unstyled text-start mb-0 small">
                                            <li><i class="fas fa-check-circle text-success me-1"></i>SPP Bulanan</li>
                                            <li><i class="fas fa-link text-info me-1"></i>Terkait: <a href="{{ route('spp.bulanan.index') }}" class="text-info">Tagihan SPP</a></li>
                                        </ul>
                                    @elseif ($payment->overtimeBill)
                                        <ul class="list-unstyled text-start mb-0 small">
                                            <li><i class="fas fa-check-circle text-success me-1"></i>Denda Overtime</li>
                                            <li><i class="fas fa-link text-info me-1"></i>Terkait: <a href="{{ route('overtime.customer.index') }}" class="text-info">Tagihan Overtime</a></li>
                                        </ul>
                                    @elseif ($payment->mealBill)
                                        <ul class="list-unstyled text-start mb-0 small">
                                            <li><i class="fas fa-check-circle text-success me-1"></i>Uang Makan</li>
                                            <li><i class="fas fa-link text-info me-1"></i>Terkait: <a href="{{ route('meal.customer.index') }}" class="text-info">Tagihan Uang Makan</a></li>
                                        </ul>
                                    @else
                                        {{-- Untuk pembayaran pendaftaran awal yang memiliki banyak komponen --}}
                                        @if ($payment->components->isNotEmpty())
                                            <ul class="list-unstyled text-start mb-0 small">
                                                @foreach ($payment->components as $component)
                                                    <li><i class="fas fa-check-circle text-success me-1"></i>{{ $component->komponen }} (Rp{{ number_format($component->jumlah, 0, ',', '.') }})</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Pembayaran Awal (Tanpa Detail Komponen)</span>
                                        @endif
                                    @endif
                                </td>
                                <td><strong class="text-dark">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if ($payment->bukti_transfer)
                                        {{-- Gunakan asset() untuk public path --}}
                                        <a href="{{ asset('uploads/buktipembayaran/' . $payment->bukti_transfer) }}" target="_blank" class="d-block text-center">
                                            <img src="{{ asset('uploads/buktipembayaran/' . $payment->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid rounded shadow-sm" style="max-width: 80px; height: auto;">
                                            <small class="d-block mt-1 text-muted">Lihat Bukti</small>
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($payment->status === 'menunggu_verifikasi')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-hourglass-half me-1"></i> Menunggu Persetujuan
                                        </span>
                                    @elseif ($payment->status === 'terverifikasi')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i> Disetujui
                                        </span>
                                    @elseif ($payment->status === 'ditolak')
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                            <i class="fas fa-question-circle me-1"></i> Tidak Diketahui
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($payment->status === 'terverifikasi')
                                        <a href="{{ route('paymenthkc.receipt', $payment->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-file-invoice"></i> Lihat
                                        </a>
                                        <a href="{{ route('paymenthkc.receipt', $payment->id) }}?print=1" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    @else
                                        <span class="text-muted small">Menunggu Verifikasi</span>
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
<style>
    /* Custom styles for payment index (from your provided code) */
    body { background-color: #f8f9fa; }
    .card { border-radius: 0.75rem; overflow: hidden; }
    .card-header.bg-success { background-color: #28a745 !important; border-bottom: none; }
    .table-bordered { border-radius: 0.5rem; overflow: hidden; border: 1px solid #dee2e6; }
    .table th, .table td { padding: 1rem; vertical-align: middle; }
    .table thead th { border-bottom: none; font-weight: 600; background-color: #28a745; color: white; }
    .table tbody tr.table-row-hover:hover { background-color: #e6ffe6; cursor: pointer; }
    .list-unstyled li { margin-bottom: 0.25rem; }
    .list-unstyled li:last-child { margin-bottom: 0; }
    .img-fluid.rounded.shadow-sm { border: 1px solid #ddd; transition: transform 0.2s ease-in-out; }
    .img-fluid.rounded.shadow-sm:hover { transform: scale(1.05); }
    .badge { font-size: 0.85em; padding: 0.4em 0.8em; font-weight: 600; }
    .badge.bg-warning { background-color: #ffc107 !important; color: #343a40 !important; }
    .badge.bg-success { background-color: #28a745 !important; color: white !important; }
    .badge.bg-secondary { background-color: #6c757d !important; color: white !important; }
    .badge.bg-danger { background-color: #dc3545 !important; color: white !important; }
    .table-responsive::-webkit-scrollbar { height: 8px; }
    .table-responsive::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
    .table-responsive::-webkit-scrollbar-thumb:hover { background: #555; }
</style>
@endsection