@extends('layouts2.master')
@section('title', 'Dashboard')
@section('dashboard2_select', 'active')

@section('content')
<div class="content">
    <div class="container-fluid">
        <h5 class="mb-3"><strong>Dashboard</strong></h5>

        <h3 class="mb-3"><strong>Selamat Datang di Pusat Pembelajaran Hikari Bridge</strong></h3>

        <div class="mt-1 mb-3 button-container">
            <div class="row pl-0">
                <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white border shadow">
                        <div class="media p-4">
                            <div class="align-self-center mr-3 rounded-circle notify-icon bg-theme">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="media-body pl-2">
                                <h4 class="mt-0 mb-0">
                                    <strong>{{ $totalPeserta }}</strong>
                                </h4>
                                <p><small class="text-muted bc-description">Peserta Terdaftar</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Komponen lain dapat ditambahkan di sini --}}
            </div>
        </div>
    </div>
</div>
@endsection
