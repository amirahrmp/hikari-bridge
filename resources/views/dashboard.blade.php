@extends('layouts.master')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('dashboard_select','active')
@section('title', 'Dashboard')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
    
    @if(Session::get('role')=='admin')
    <h3 class="mb-3" ><strong>Selamat Datang! Anda masuk sebagai admin</strong></h3>
    @else
    <h3 class="mb-3" ><strong>Selamat Datang! Di Data Center Hikari Bridge</strong></h3>
    @endif
    <!--Dashboard widget-->
    <div class="mt-1 mb-3 button-container">
        <div class="row pl-0">
            <!-- Staf Tetap -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                <div class="bg-success text-white border shadow">
                    <div class="media p-4">
                        <div class="align-self-center mr-3 notify-icon bg-white">
                            <i class="fa fa-users text-success"></i> <!-- Ikon untuk grup -->
                        </div>
                        <div class="media-body pl-2">
                            <h4 class="mt-0 mb-0"><strong>{{ $stafTetap }}</strong></h4>
                            <p>Staf Tetap</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Staf Non Tetap -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                <div class="bg-success text-white border shadow">
                    <div class="media p-4">
                        <div class="align-self-center mr-3 notify-icon bg-white">
                            <i class="fa fa-user-clock text-success"></i> <!-- Ikon untuk waktu -->
                        </div>
                        <div class="media-body pl-2">
                            <h4 class="mt-0 mb-0"><strong>{{ $stafNonTetap }}</strong></h4>
                            <p>Staf Non Tetap</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Staf Daycare -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                <div class="bg-success text-white border shadow">
                    <div class="media p-4">
                        <div class="align-self-center mr-3 notify-icon bg-white">
                            <i class="fa fa-baby-carriage text-success"></i> <!-- Ikon daycare -->
                        </div>
                        <div class="media-body pl-2">
                            <h4 class="mt-0 mb-0"><strong>{{ $stafDaycare }}</strong></h4>
                            <p>Staf Daycare</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Peserta Aktif -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                <div class="bg-white border shadow">
                    <div class="media p-4">
                        <div class="align-self-center mr-3 notify-icon bg-theme">
                            <i class="fa fa-graduation-cap text-theme"></i> <!-- Ikon peserta -->
                        </div>
                        <div class="media-body pl-2">
                            <h4 class="mt-0 mb-0"><strong>{{ $pesertaAktif }}</strong></h4>
                            <p>Peserta Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Tenaga Pengajar -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                <div class="bg-white border shadow">
                    <div class="media p-4">
                        <div class="align-self-center mr-3 notify-icon bg-theme">
                            <i class="fa fa-chalkboard-teacher text-theme"></i> <!-- Ikon pengajar -->
                        </div>
                        <div class="media-body pl-2">
                            <h4 class="mt-0 mb-0"><strong>{{ $totalTeacher }}</strong></h4>
                            <p>Tenaga Pengajar</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pemasukkan Bulan Ini -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                <div class="bg-white border shadow">
                    <div class="media p-4">
                        <div class="align-self-center mr-3 notify-icon bg-theme">
                            <i class="fa fa-money-bill-alt text-theme"></i> <!-- Ikon uang -->
                        </div>
                        <div class="media-body pl-2">
                            <h4 class="mt-0 mb-0"><strong>{{ rupiah(0) }}</strong></h4>
                            <p>Pemasukkan Bulan Ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>        
    <!--/Dashboard widget-->
    
    <div class="row mt-3">      
        <div class="col-sm-6">
            <!--Revenue Graph-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                <h5 class="mb-2">Grafik Pemasukkan</h5>
                
                <div style="width: 100%;">
                    <canvas id="myChart"></canvas>
                </div>
            
            </div>
            <!--/Revenue Graph-->
        </div>
    </div>
</div>
</div>
    

@endsection