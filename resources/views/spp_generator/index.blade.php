@extends('layouts.master') {{-- Pastikan ini mengarah ke layout master AdminLTE Anda --}}
@section('title', 'Generator Tagihan SPP Bulanan')
@section('spp_generator_select', 'active') {{-- Untuk highlight menu sidebar --}}

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-cogs mr-2"></i>Generator Tagihan SPP</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Generator SPP</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
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

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Buat Tagihan SPP untuk Bulan: <strong>{{ \Carbon\Carbon::create()->month($bulanIni)->format('F') }} {{ $tahunIni }}</strong></h3>
                            <div class="card-tools">
                                <form action="{{ route('spp.generator.generate_all') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Anda yakin ingin membuat tagihan SPP untuk semua anak yang belum punya tagihan bulan ini?')">
                                        <i class="fas fa-sync-alt mr-1"></i> Buat untuk Semua
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Program</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($registrations as $index => $registration)
                                        @php
                                            $sppKey = $registration->id . '-' . get_class($registration);
                                            $isAlreadyGenerated = isset($existingSppKeys[$sppKey]);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $registration->full_name ?? $registration->name }}</td>
                                            <td>
                                                @if($registration instanceof \App\Models\RegistrationHikariKidzDaycare) Hikari Kidz Daycare
                                                @elseif($registration instanceof \App\Models\RegistrationHikariKidzClub) Hikari Kidz Club
                                                @elseif($registration instanceof \App\Models\RegistrationHikariQuran) Hikari Quran
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($isAlreadyGenerated)
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Tagihan Dibuat</span>
                                                @else
                                                    <form action="{{ route('spp.generator.generate') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                                                        <input type="hidden" name="registration_type" value="{{ get_class($registration) }}">
                                                        <button type="submit" class="btn btn-sm btn-info">
                                                            <i class="fas fa-plus"></i> Buat Tagihan
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data pendaftaran ditemukan.</td>
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
