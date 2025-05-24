@extends('layouts.master')

@section('jadwal_makan_daycare_user_select','active')
@section('title', 'Jadwal Makan Daycare')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Jadwal Makan Daycare</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</li>
              <li class="breadcrumb-item"><a href="#">Jadwal Makan Daycare</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row mt-3">
            <div class="col-sm-12">
                <!--Datatable-->
                <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">           
                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table id="datatable" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Hari</th>
                                                            <th>Snack Pagi</th>
                                                            <th>Makan Siang</th>
                                                            <th>Snack Sore</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($jadwal_makan_daycare as $p)
                                                        <tr>
                                                            <td>{{ $p->hari }}</td>
                                                            <td>{{ $p->snack_pagi }}</td>
                                                            <td>{{ $p->makan_siang }}</td>
                                                            <td>{{ $p->snack_sore }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer clearfix">
                                            <!-- Kosongkan jika tidak ada tombol untuk user -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content -->

                </div>
                <!--/Datatable-->

            </div>
        </div>
    </div>
</div>

@endsection
