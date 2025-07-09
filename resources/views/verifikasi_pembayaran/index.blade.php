@extends('layouts.master') {{-- Sesuaikan dengan layout master admin Anda --}}

@section('title', 'Verifikasi Pembayaran Admin')
@section('pembayaran_admin_select','active')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-check-double me-2"></i> Verifikasi Pembayaran</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Verifikasi Pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-12">
                    <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                        <div class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                {{-- Pesan Sukses atau Error --}}
                                                @if(session('success'))
                                                    <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                                                        {{ session('success') }}
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @elseif(session('error'))
                                                    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                                                        {{ session('error') }}
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif

                                                @if($payments->isEmpty())
                                                    <div class="alert alert-info text-center py-4 mx-3 mt-3">
                                                        <i class="fas fa-info-circle me-2"></i> Tidak ada pembayaran yang perlu diverifikasi saat ini.
                                                    </div>
                                                @else
                                                    <div class="table-responsive p-3">
                                                        <table id="datatable" class="table table-bordered table-striped text-center align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">No.</th>
                                                                    <th style="width: 15%;">Nama Anak</th>
                                                                    <th style="width: 15%;">Program</th>
                                                                    <th style="width: 25%;">Komponen Pembayaran</th>
                                                                    <th style="width: 15%;">Total Pembayaran</th>
                                                                    <th style="width: 10%;">Bukti Transfer</th>
                                                                    <th style="width: 15%;">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($payments as $payment)
                                                                <tr>
                                                                    <td>{{ $loop->count - $loop->index }}</td>

                                                                    <td>
                                                                        {{-- AKSES NAMA ANAK DARI AKESOR getPesertaAttribute --}}
                                                                        {{ $payment->peserta->full_name ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $paket = '-';

                                                                            if ($payment->registration) {
                                                                                switch ($payment->registration_type) {
                                                                                    case \App\Models\RegistrationHikariKidzClub::class:
                                                                                        $paket = optional($payment->registration->getPaketHkc())->kelas ?? '-';
                                                                                        break;

                                                                                    case \App\Models\RegistrationHikariKidzDaycare::class:
                                                                                        $paket = optional($payment->registration->paket)->nama_paket ?? '-';
                                                                                        break;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        {{ $paket }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($payment->sppBulanan)
                                                                            <ul class="list-unstyled text-start mb-0 small">
                                                                                <li><i class="fas fa-check-circle text-success me-1"></i>SPP Bulanan ({{ $payment->sppBulanan->paket ?? $payment->sppBulanan->program }})</li>
                                                                                <li class="mt-2 pt-2 border-top">
                                                                                    <strong>SPP Tagihan:</strong> {{ \Carbon\Carbon::create()->month($payment->sppBulanan->bulan)->format('F Y') }} <br>
                                                                                    Nominal: Rp{{ number_format($payment->sppBulanan->nominal, 0, ',', '.') }}
                                                                                    <span class="badge bg-secondary ml-1">{{ ucfirst(str_replace('_', ' ', $payment->sppBulanan->status)) }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        @elseif ($payment->overtimeBill)
                                                                            <ul class="list-unstyled text-start mb-0 small">
                                                                                <li><i class="fas fa-check-circle text-success me-1"></i>Denda Overtime ({{ $payment->overtimeBill->package_name ?? $payment->overtimeBill->program }})</li>
                                                                                <li class="mt-2 pt-2 border-top">
                                                                                    <strong>Denda Overtime:</strong> {{ \Carbon\Carbon::create()->month($payment->overtimeBill->bulan)->format('F Y') }} <br>
                                                                                    Nominal: Rp{{ number_format($payment->overtimeBill->total_denda, 0, ',', '.') }}
                                                                                    <span class="badge bg-secondary ml-1">{{ ucfirst(str_replace('_', ' ', $payment->overtimeBill->status)) }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        @elseif ($payment->mealBill)
                                                                            <ul class="list-unstyled text-start mb-0 small">
                                                                                <li><i class="fas fa-check-circle text-success me-1"></i>Uang Makan ({{ $payment->mealBill->package_name ?? $payment->mealBill->program }})</li>
                                                                                <li class="mt-2 pt-2 border-top">
                                                                                    <strong>Uang Makan:</strong> {{ \Carbon\Carbon::create()->month($payment->mealBill->bulan)->format('F Y') }} <br>
                                                                                    Nominal: Rp{{ number_format($payment->mealBill->nominal_uang_makan, 0, ',', '.') }}
                                                                                    <span class="badge bg-secondary ml-1">{{ ucfirst(str_replace('_', ' ', $payment->mealBill->status)) }}</span>
                                                                                </li>
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
                                                                                <span class="text-muted">Pembayaran Awal</span>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td><strong class="text-dark">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</strong></td>
                                                                    <td>
                                                                        @if ($payment->bukti_transfer)
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
                                                                            <form action="{{ route('admin.pembayaran.approve', $payment->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button type="submit" class="btn btn-success btn-sm mb-1" onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')">
                                                                                    <i class="fas fa-check-circle me-1"></i> Verifikasi
                                                                                </button>
                                                                            </form>
                                                                            {{-- Tombol Tolak dengan Modal --}}
                                                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectPaymentModal{{ $payment->id }}">
                                                                                <i class="fas fa-times-circle me-1"></i> Tolak
                                                                            </button>

                                                                            <div class="modal fade" id="rejectPaymentModal{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header bg-danger text-white">
                                                                                            <h5 class="modal-title" id="rejectPaymentModalLabel{{ $payment->id }}">Tolak Pembayaran #{{ $payment->id }}</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <form action="{{ route('admin.pembayaran.reject', $payment->id) }}" method="POST">
                                                                                            @csrf
                                                                                            @method('PATCH')
                                                                                            <div class="modal-body">
                                                                                                <div class="form-group">
                                                                                                    <label for="reason{{ $payment->id }}">Alasan Penolakan:</label>
                                                                                                    <textarea class="form-control" id="reason{{ $payment->id }}" name="reason" rows="3" required></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                                                <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @elseif ($payment->status === 'terverifikasi')
                                                                            <span class="badge badge-success px-3 py-2 rounded-pill">
                                                                                <i class="fas fa-check"></i> Terverifikasi
                                                                            </span>
                                                                        @elseif ($payment->status === 'ditolak')
                                                                            <span class="badge badge-danger px-3 py-2 rounded-pill">
                                                                                <i class="fas fa-times"></i> Ditolak
                                                                            </span>
                                                                            @if($payment->notes)
                                                                                <small class="d-block mt-1 text-danger">{{ $payment->notes }}</small>
                                                                            @endif
                                                                        @else
                                                                            <span class="badge badge-secondary px-3 py-2 rounded-pill">
                                                                                <i class="fas fa-question"></i> Tidak Diketahui
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div> <div class="card-footer clearfix"></div>
                                        </div>
                                    </div>
                                </div> </div></div></div></div>
            </div>
        </div>
    </section>
</div>

{{-- Custom CSS untuk tampilan ini. Bisa digabungkan ke file CSS utama Anda. --}}
<style>
    /* Styling for badges (gunakan yang ada di template Anda) */
    .badge-warning {
        background-color: #ffc107;
        color: #343a40;
    }
    .badge-success {
        background-color: #28a745;
        color: #fff;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    .badge-danger { /* Added for 'ditolak' status */
        background-color: #dc3545;
        color: #fff;
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
    .px-3 { padding-left: 1rem !important; padding-right: 1rem !important; }
    .py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }

    /* For icons next to text */
    .me-1 { margin-right: 0.25rem !important; }
    .me-2 { margin-right: 0.5rem !important; }

    /* Specific table styles (dari template Anda) */
    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075); /* Default Bootstrap hover, sesuaikan jika ada custom hover */
    }
    /* Kalau mau hover yang lebih menonjol seperti di payment/index.blade.php */
    .table-hover tbody tr:hover {
        background-color: #e6ffe6; /* Light green on hover for admin payment table */
    }

    /* Image styling within table */
    .img-thumbnail {
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
        padding: 0.25rem;
        background-color: #fff;
    }
    .img-fluid.rounded.shadow-sm {
        border: 1px solid #ddd;
        transition: transform 0.2s ease-in-out;
    }
    .img-fluid.rounded.shadow-sm:hover {
        transform: scale(1.05);
    }

    /* Override some general styles for a consistent look */
    .content-wrapper {
        background-color: #f4f6f9; /* Light background for content area */
    }
    .button-container {
        border-radius: 0.25rem; /* Small border radius for the container */
    }
    .card {
        border-radius: 0.25rem; /* Matching the button-container */
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075)!important; /* Standard small shadow */
    }
    .card-header {
        background-color: #fff; /* Default card header background */
        border-bottom: 1px solid rgba(0,0,0,.125); /* Default border */
    }
    .card-header.bg-gradient-success {
        background-image: linear-gradient(to right, #28a745, #218838); /* Gradient for a richer green header */
        color: white;
        border-bottom: none; /* Remove border if gradient */
    }
    .card-title {
        color: #343a40; /* Default dark text for card title */
    }
    .card-footer.clearfix {
        background-color: #fff; /* Default card footer background */
        border-top: 1px solid rgba(0,0,0,.125); /* Default border */
    }
</style>
@endsection

@push('scripts')
<script>
    $(function () {
        // Cek apakah DataTables sudah diinisialisasi pada elemen #datatable
        // Menggunakan $.fn.DataTable.isDataTable() adalah cara yang disarankan
        if ($.fn.DataTable.isDataTable('#datatable')) {
            // Jika sudah, hancurkan instans DataTables yang ada
            $('#datatable').DataTable().destroy();
        }

        // Kemudian, inisialisasi DataTables
        $('#datatable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            // Tambahkan opsi-opsi lain jika ada, contoh:
            // "language": {
            //     "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            // }
        });
    });
</script>
@endpush