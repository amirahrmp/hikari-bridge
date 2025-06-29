@extends('layouts2.master') {{-- Pastikan ini mengarah ke layout master yang benar --}}

@section('title', 'Riwayat Pembayaran') {{-- Judul halaman --}}

@section('content')
<div class="container mt-5 mb-5"> {{-- Tambahkan mb-5 untuk margin bawah --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white py-3">
            <h2 class="mb-0 text-center"><i class="fas fa-history me-2"></i> Riwayat Pembayaran</h2> {{-- Icon dan judul di tengah --}}
        </div>
        <div class="card-body p-4"> {{-- Tambahkan padding --}}

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Tombol close --}}
                </div>
            @endif

            @if($payments->isEmpty())
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-info-circle me-2"></i> Belum ada data pembayaran yang tercatat.
                </div>
            @else
                <div class="table-responsive"> {{-- Tambahkan div responsive untuk tabel --}}
                    <table class="table table-bordered table-hover text-center align-middle"> {{-- Tambahkan table-hover, text-center, align-middle --}}
                            <thead class="bg-success text-white"> {{-- Pastikan warna hijau di sini --}}
                                <tr>
                                    <th style="width: 5%;">No.</th> {{-- Lebar tetap --}}
                                    <th style="width: 15%;">Nama Anak</th>
                                    <th style="width: 15%;">Program</th>
                                    <th style="width: 25%;">Komponen Pembayaran</th>
                                    <th style="width: 15%;">Total Pembayaran</th>
                                    <th style="width: 10%;">Bukti Transfer</th>
                                    <th style="width: 15%;">Status</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                            <tr class="table-row-hover"> {{-- Kelas untuk hover effect pada baris --}}
                                <td>{{ $loop->count - $loop->index }}</td> {{-- Nomor urut terbalik --}}
                                
                                <td>
                                    @php
                                        $peserta = $payment->peserta;
                                        $childName = '-';

                                        if ($peserta) {
                                            if ($payment->registration_type === 'Hikari Kidz Club' || $payment->registration_type === 'Hikari Kidz Daycare') {
                                                $childName = $peserta->full_name ?? $peserta->name ?? '-';
                                            } elseif ($payment->registration_type === 'Hikari Quran') {
                                                $childName = $peserta->name ?? '-';
                                            }
                                        }
                                        echo $childName;
                                    @endphp
                                </td>

                                <td>{{ ucfirst($payment->registration_type) }}</td>
                                <td>
                                    @if ($payment->components->isNotEmpty())
                                        <ul class="list-unstyled text-start mb-0 small"> {{-- list-unstyled, text-start, mb-0, small --}}
                                            @foreach ($payment->components as $component)
                                                <li><i class="fas fa-check-circle text-success me-1"></i>{{ $component->komponen }} (Rp{{ number_format($component->jumlah, 0, ',', '.') }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                {{-- PERUBAHAN DI SINI: text-success menjadi text-dark (atau hapus kelasnya agar default hitam) --}}
                                <td><strong class="text-dark">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</strong></td> 
                                <td>
                                    @if ($payment->bukti_transfer)
                                        <a href="{{ asset('uploads/buktipembayaran/' . $payment->bukti_transfer) }}" target="_blank" class="d-block text-center">
                                            <img src="{{ asset('uploads/buktipembayaran/' . $payment->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid rounded shadow-sm" style="max-width: 80px; height: auto;"> {{-- Styling gambar --}}
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
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                            <i class="fas fa-question-circle me-1"></i> Tidak Diketahui
                                        </span>
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
    /* General body background for a cleaner look */
    body {
        background-color: #f8f9fa; /* Light grey background */
    }

    /* Card styling */
    .card {
        border-radius: 0.75rem; /* Slightly rounded corners */
        overflow: hidden; /* Ensures content respects border-radius */
    }

    /* Header styling */
    .card-header.bg-success {
        background-color: #28a745 !important; /* Darker green for header */
        border-bottom: none; /* Remove default border */
    }

    /* Table styling */
    .table-bordered {
        border-radius: 0.5rem; /* Rounded table corners */
        overflow: hidden; /* For consistent border-radius */
        border: 1px solid #dee2e6; /* Bootstrap default border color */
    }

    .table th, .table td {
        padding: 1rem; /* More generous padding */
        vertical-align: middle; /* Center content vertically */
    }

    /* Table head styling */
    .table thead th {
        border-bottom: none; /* No border below header */
        font-weight: 600; /* Slightly bolder font */
        background-color: #28a745; /* Ensure header background is this green */
        color: white;
    }

    /* Table row hover effect */
    .table tbody tr.table-row-hover:hover {
        background-color: #e6ffe6; /* Light green on hover */
        cursor: pointer; /* Indicate clickable row */
    }

    /* Specific styling for components list */
    .list-unstyled li {
        margin-bottom: 0.25rem; /* Small margin between list items */
    }
    .list-unstyled li:last-child {
        margin-bottom: 0; /* No margin on the last item */
    }

    /* Image styling */
    .img-fluid.rounded.shadow-sm {
        border: 1px solid #ddd; /* Light border around image */
        transition: transform 0.2s ease-in-out;
    }

    .img-fluid.rounded.shadow-sm:hover {
        transform: scale(1.05); /* Slight zoom on hover */
    }

    /* Badge styling for better visual */
    .badge {
        font-size: 0.85em; /* Slightly larger text in badges */
        padding: 0.4em 0.8em; /* More padding */
        font-weight: 600; /* Bolder text */
    }

    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #343a40 !important; /* Dark text for warning */
    }

    .badge.bg-success {
        background-color: #28a745 !important;
        color: white !important;
    }

    .badge.bg-secondary {
        background-color: #6c757d !important;
        color: white !important;
    }

    /* Custom scrollbar for webkit browsers */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection