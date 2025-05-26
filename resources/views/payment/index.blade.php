@extends('layouts2.master')

@section('content')
<div class="container mt-5">
    <h2>Daftar Pembayaran</h2>

    <a href="{{ route('payment.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran Manual</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($payments->isEmpty())
        <div class="alert alert-info">Belum ada data pembayaran.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Registration Type</th>
                    <th>Komponen</th>
                    <th>Jumlah</th>
                    <th>Bukti Transfer</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ ucfirst($payment->registration_type) }}</td>
                    <td>{{ $payment->komponen }}</td>
                    <td>Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if ($payment->bukti_transfer)
                            <img src="{{ asset('storage/' . $payment->bukti_transfer) }}" alt="Bukti Transfer" width="100">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
