@extends('layouts.master')

@section('gaji_staf_select','active')
@section('title', 'Gaji')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Gaji</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Pencatatan</a></li>
              <li class="breadcrumb-item"><a href="#">Gaji</a></li>
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
                                <div class="card-body p-0">
                                    <div>
                                        <button type="button" class="btn btn-primary mb-3" onclick="window.location.href='{{ route('gaji_staf.form') }}'">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>                                                                                
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Gaji</th>
                                                        <th>Tanggal Gaji</th>
                                                        <th>Keterangan</th>
                                                        <th>Periode</th>
                                                        <th>Total Gaji</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($gaji as $item)
                                                        <tr>
                                                            <td>{{ $item->id_gaji }}</td>
                                                            <td>{{ $item->tgl_gaji }}</td>
                                                            <td>{{ $item->keterangan }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->bulan_tahun)->format('F Y') }}</td>
                                                            <td>{{ $item->total_gaji_dibayarkan }}</td>
                                                            <td>
                                                                <button type="button" class="btn-sm btn-warning" title="Ubah data gaji" onclick="window.location.href='{{ route('gaji_staf.edit', $item->id) }}'">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>                                                                                                                                                                                          
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
            
                                    <div class="card-footer clearfix">
                                        
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