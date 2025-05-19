@extends('layouts.master')

@section('detail_hikari_kidz_select','active')
@section('title', 'Detail Hikari Kidz')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Hikari Kidz</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Detail Hikari Kidz</a></li>
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
                                        <form method="GET" action="{{ route('hikari_kidz.detail') }}" class="mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select name="hikari_kidz_id" class="form-control" onchange="this.form.submit()">
                                                        <option value="">-- Pilih Nama Hikari Kidz --</option>
                                                        @foreach($hikari_kidzList as $hikari_kidzOption)
                                                            <option value="{{ $hikari_kidzOption->id }}" 
                                                                {{ request()->get('hikari_kidz_id') == $hikari_kidzOption->id ? 'selected' : '' }}>
                                                                {{ $hikari_kidzOption->nama_hikari_kidz }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                        
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addHikariKidzModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                                    
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Anak</th>
                                                        <th>Status</th>
                                                        <th>Nama Anak</th>
                                                        <th>Tgl Masuk Hikari Kidz</th>
                                                        <th>Nama Ortu</th>
                                                        <th>Telepon</th>                                                      
                                                        <th>Alamat</th>
                                                        <th>Email</th>  
                                                        <th>Ubah Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if($peserta->isEmpty())
                                                    <tr>
                                                        <td colspan="9" class="text-center">Tidak ada data peserta untuk hikari kidz ini.</td>
                                                    </tr>
                                                @else
                                                    @foreach($peserta as $p)
                                                    <tr>
                                                        <td>{{ $p->id_anak }}</td>
                                                        <td class="text-center">
                                                            <!-- Kondisi untuk menentukan label 3warna -->
                                                            @if($p->pivot->status == 'Aktif')
                                                                <span class="badge badge-success">Aktif</span> <!-- Label Hijau untuk Aktif -->
                                                            @else
                                                                <span class="badge badge-danger">Nonaktif</span> <!-- Label Merah untuk Nonaktif -->
                                                            @endif
                                                        </td>
                                                        <td>{{ $p->nama_anak }}</td>
                                                        <td>{{ $p->pivot->tgl_masuk_hikari_kidz}}</td>
                                                        <td>{{ $p->nama_ortu }}</td>
                                                        <td>{{ $p->telp }}</td>
                                                        <td>{{ $p->alamat }}</td>
                                                        <td>{{ $p->email }}</td>
                                                        <td class="text-center">
                                                            <form action="{{ route('ubah.status', ['id' => $p->pivot->id]) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="current_status" value="{{ $p->pivot->status }}">
                                                                <button type="submit" class="btn-sm btn-warning">
                                                                    <i class="fa fa-pen"></i>
                                                                </button>
                                                            </form>                                                  
                                                        </td>
                                                    </tr>
                                                    
                                                @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Modal untuk Tambah Data -->
                                        <div class="modal fade" id="addHikariKidzModal" tabindex="-1" aria-labelledby="addHikariKidzModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addHikariKidzModalLabel">Tambah Data Peserta Hikari Kidz</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('hikari_kidz.store') }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <!-- Input ID Hikari Kidz -->
                                                            <div class="mb-3">
                                                                <label for="id_hikari_kidz" class="form-label">Nama Hikari Kidz</label>
                                                                <select name="id_hikari_kidz" id="id_hikari_kidz" class="form-control" required>
                                                                    <option value="">-- Pilih Nama Hikari Kidz --</option>
                                                                    @foreach($hikari_kidzList as $hikari_kidzOption)
                                                                        <option value="{{ $hikari_kidzOption->id }}">{{ $hikari_kidzOption->nama_hikari_kidz }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Input ID Anak -->
                                                            <div class="mb-3">
                                                                <label for="id_anak" class="form-label">Nama Anak</label>
                                                                <select name="id_anak" id="id_anak" class="form-control" required>
                                                                    <option value="">-- Pilih Peserta --</option>
                                                                    @foreach($pesertaList as $pesertaOption)
                                                                        <option value="{{ $pesertaOption->id }}">{{ $pesertaOption->nama_anak }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tgl_masuk_hikari_kidz" class="form-label">Tanggal Masuk Hikari Kidz</label>
                                                                <input type="date" name="tgl_masuk_hikari_kidz" id="tgl_masuk_hikari_kidz" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Modal Upload Excel -->
                                        <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Hikari Kidz dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('hikari_kidz.upload') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="excel_file">Pilih File Excel</label>
                                                                <input type="file" name="excel_file" class="form-control" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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