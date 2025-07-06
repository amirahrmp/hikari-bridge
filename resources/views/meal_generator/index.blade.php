@extends('layouts.master') {{-- Sesuaikan dengan layout master AdminLTE Anda --}}
@section('title', 'Generator Tagihan Uang Makan')
@section('meal_bill_admin_select', 'active') {{-- Pastikan Anda punya menu ini di sidebar Anda --}}

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-utensils mr-2"></i>Tagihan Uang Makan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Tagihan Uang Makan</li>
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
                            {!! session('error') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Uang Makan untuk Bulan: <strong>{{ \Carbon\Carbon::create()->month($bulanFilter)->format('F') }} {{ $tahunFilter }}</strong></h3>
                            <div class="card-tools">
                                <form action="{{ route('meal.generator.generate') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="bulan" value="{{ $bulanFilter }}">
                                    <input type="hidden" name="tahun" value="{{ $tahunFilter }}">
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Anda yakin ingin membuat tagihan uang makan untuk semua anak yang belum punya tagihan bulan ini?')">
                                        <i class="fas fa-sync-alt mr-1"></i> Buat untuk Semua Anak
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <form action="{{ route('meal.generator.index') }}" method="GET" class="form-inline">
                                    <label for="bulan" class="mr-2">Bulan:</label>
                                    <select name="bulan" id="bulan" class="form-control mr-2">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $bulanFilter == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                    <label for="tahun" class="mr-2">Tahun:</label>
                                    <select name="tahun" id="tahun" class="form-control mr-2">
                                        @php
                                            $currentYear = \Carbon\Carbon::now()->year;
                                            $startYear = $currentYear - 2;
                                            $endYear = $currentYear + 1;
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
                                            <th>Program (Paket)</th>
                                            <th>Nominal Uang Makan</th>
                                            <th class="text-center">Status Tagihan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($studentsWithMealBills as $index => $data)
                                        @php
                                            $mealKey = $data['registration_id'] . '-' . $data['registration_type'];
                                            $isAlreadyGenerated = isset($existingMealKeys[$mealKey]);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data['full_name'] }}</td>
                                            <td>{{ $data['package_name'] ?? $data['program'] }}</td>
                                            <td>Rp {{ number_format($data['nominal_uang_makan'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if($isAlreadyGenerated)
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sudah Dibuat</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> Belum Dibuat</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($isAlreadyGenerated)
                                                    <span class="text-muted">Tidak ada aksi</span>
                                                @else
                                                    <form action="{{ route('meal.generator.generate') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="registration_id" value="{{ $data['registration_id'] }}">
                                                        <input type="hidden" name="registration_type" value="{{ $data['registration_type'] }}">
                                                        <input type="hidden" name="bulan" value="{{ $bulanFilter }}">
                                                        <input type="hidden" name="tahun" value="{{ $tahunFilter }}">
                                                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Anda yakin ingin membuat tagihan uang makan untuk {{ $data['full_name'] }}?')">
                                                            <i class="fas fa-plus"></i> Buat Tagihan
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data uang makan yang memerlukan tagihan untuk bulan ini.</td>
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
