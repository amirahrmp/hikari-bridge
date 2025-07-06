@extends('layouts.master') {{-- Sesuaikan dengan layout master AdminLTE Anda --}}
@section('title', 'Tagihan Tambahan ')
@section('additional_bill_select', 'active') {{-- Pastikan Anda punya menu ini di sidebar Anda --}}

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-money-bill-wave mr-2"></i>Tagihan Tambahan </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Tagihan Tambahan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    {{-- Pesan Sukses atau Error --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! session('error') !!} {{-- Gunakan {!! !!} karena mungkin ada tag <br> --}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            {{-- Perbaikan: Menggunakan \Carbon\Carbon::create() --}}
                            <h3 class="card-title">Data Overtime untuk Bulan: <strong>{{ \Carbon\Carbon::create()->month($bulanFilter)->format('F') }} {{ $tahunFilter }}</strong></h3>
                            <div class="card-tools">
                                <form action="{{ route('additional_bills.generate') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="bulan" value="{{ $bulanFilter }}">
                                    <input type="hidden" name="tahun" value="{{ $tahunFilter }}">
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Anda yakin ingin membuat tagihan overtime untuk semua anak yang memiliki denda bulan ini?')">
                                        <i class="fas fa-sync-alt mr-1"></i> Buat untuk Semua Anak Denda
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <form action="{{ route('admin.additional_bills.index') }}" method="GET" class="form-inline">
                                    <label for="bulan" class="mr-2">Bulan:</label>
                                    <select name="bulan" id="bulan" class="form-control mr-2">
                                        @for ($i = 1; $i <= 12; $i++)
                                            {{-- Perbaikan: Menggunakan \Carbon\Carbon::create() --}}
                                            <option value="{{ $i }}" {{ $bulanFilter == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                    <label for="tahun" class="mr-2">Tahun:</label>
                                    <select name="tahun" id="tahun" class="form-control mr-2">
                                        {{-- Perbaikan: Menggunakan \Carbon\Carbon::now()->year --}}
                                        @php
                                            $currentYear = \Carbon\Carbon::now()->year;
                                            $startYear = $currentYear - 2; // Misalnya, 2 tahun ke belakang
                                            $endYear = $currentYear + 1;    // Misalnya, 1 tahun ke depan
                                        @endphp
                                        @for ($i = $startYear; $i <= $endYear; $i++)
                                            <option value="{{ $i }}" {{ $tahunFilter == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Program (Paket)</th> {{-- Label diubah --}}
                                            <th>Total Denda Bulan Ini</th>
                                            <th>Hari Overtime</th>
                                            <th class="text-center">Status Tagihan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($studentsWithOvertime as $index => $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data['full_name'] }}</td>
                                            <td>{{ $data['package_name'] }}</td> {{-- Menampilkan nama paket --}}
                                            <td>Rp {{ number_format($data['total_denda'], 0, ',', '.') }}</td>
                                            <td>{{ $data['jumlah_hari_overtime'] }} hari</td>
                                            <td class="text-center">
                                                @if($data['bill_status'] == 'Sudah Dibuat')
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ $data['bill_status'] }}</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> {{ $data['bill_status'] }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($data['bill_status'] == 'Belum Dibuat')
                                                    <form action="{{ route('additional_bills.generate') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="registration_id" value="{{ $data['registration_id'] }}">
                                                        <input type="hidden" name="registration_type" value="{{ $data['registration_type'] }}">
                                                        <input type="hidden" name="bulan" value="{{ $bulanFilter }}">
                                                        <input type="hidden" name="tahun" value="{{ $tahunFilter }}">
                                                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Anda yakin ingin membuat tagihan overtime untuk {{ $data['full_name'] }}?')">
                                                            <i class="fas fa-plus"></i> Buat Tagihan
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">Tidak ada aksi</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data denda overtime untuk bulan ini.</td>
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
</div>
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