@extends('layouts2.master')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('dashboard_select', 'active')
@section('title', 'Dashboard')

@section('content')
<div class="content">
    <div class="container-fluid">
        <h5 class="mb-3"><strong>Dashboard</strong></h5>
        
        @if(Session::get('role') == 'teacher')
            <h3 class="mb-3"><strong>Selamat Datang! Anda Masuk Sebagai Teacher</strong></h3>
        @else
            <h3 class="mb-3"><strong>Selamat Datang di Pusat Pembelajaran Hikari Bridge</strong></h3>
        @endif
        
        <!-- Dashboard widget -->
        <div class="mt-1 mb-3 button-container">
            <div class="row pl-0">
                @if(Session::get('role') != 'teacher')
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                        <div class="bg-white border shadow">
                            <div class="media p-4">
                                <div class="align-self-center mr-3 rounded-circle notify-icon bg-theme">
                                    <i class="fa fa-exchange"></i>
                                </div>
                                <div class="media-body pl-2">
                                    <h4 class="mt-0 mb-0"><strong>{{ number_format((float)($revenuetoday ?? 0), 2, ',', '.') }}</strong></h4>
                                    <p><small class="text-muted bc-description">Kursus Terdaftar</small></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                        <div class="bg-white border shadow">
                            <div class="media p-4">
                                <div class="align-self-center mr-3 rounded-circle notify-icon bg-theme">
                                    <i class="fa fa-money"></i>
                                </div>
                                <div class="media-body pl-2">
                                    <h4 class="mt-0 mb-0"><strong>{{ rupiah((float)($transaction ?? 0)) }}</strong></h4>
                                    <p><small class="text-muted bc-description">Tagihan Pembayaran</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(Session::get('role') == 'teacher')
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                        <div class="bg-white border shadow">
                            <div class="media p-4">
                                <div class="align-self-center mr-3 rounded-circle notify-icon bg-theme">
                                    <i class="fa fa-exchange"></i>
                                </div>
                                <div class="media-body pl-2">
                                    <h4 class="mt-0 mb-0"><strong>{{ number_format((float)($revenuetoday ?? 0), 2, ',', '.') }}</strong></h4>
                                    <p><small class="text-muted bc-description">Kursus Saya</small></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                        <div class="bg-white border shadow">
                            <div class="media p-4">
                                <div class="align-self-center mr-3 rounded-circle notify-icon bg-theme">
                                    <i class="fa fa-money"></i>
                                </div>
                                <div class="media-body pl-2">
                                    <h4 class="mt-0 mb-0"><strong>{{ rupiah((float)($transaction ?? 0)) }}</strong></h4>
                                    <p><small class="text-muted bc-description">Siswa Saya</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
