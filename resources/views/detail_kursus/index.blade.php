@extends('layouts.master')

@section('detail_kursus_select','active')
@section('title', 'Detail Kursus')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Kursus</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Detail Kursus</a></li>
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
                                        <form method="GET" action="{{ route('kursus.detail') }}" class="mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select name="kursus_id" class="form-control" onchange="this.form.submit()">
                                                        <option value="">-- Pilih Nama Kursus --</option>
                                                        @foreach($kursusList as $kursusOption)
                                                            <option value="{{ $kursusOption->id }}" 
                                                                {{ request()->get('kursus_id') == $kursusOption->id ? 'selected' : '' }}>
                                                                {{ $kursusOption->nama_kursus }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                        
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addKursusModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                                    
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Peserta</th>
                                                        <th>Status</th>
                                                        <th>Nama Peserta</th>
                                                        <th>Tgl Masuk Kursus</th>
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
                                                        <td colspan="9" class="text-center">Tidak ada data peserta untuk kursus ini.</td>
                                                    </tr>
                                                @else
                                                    @foreach($peserta as $p)
                                                    <tr>
                                                        <td>{{ $p->id_peserta }}</td>
                                                        <td class="text-center">
                                                            <!-- Kondisi untuk menentukan label warna -->
                                                            @if($p->pivot->status == 'Aktif')
                                                                <span class="badge badge-success">Aktif</span> <!-- Label Hijau untuk Aktif -->
                                                            @else
                                                                <span class="badge badge-danger">Nonaktif</span> <!-- Label Merah untuk Nonaktif -->
                                                            @endif
                                                        </td>
                                                        <td>{{ $p->nama_peserta }}</td>
                                                        <td>{{ $p->pivot->tgl_masuk_kursus }}</td>
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
                                        <div class="modal fade" id="addKursusModal" tabindex="-1" aria-labelledby="addKursusModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addKursusModalLabel">Tambah Data Peserta Kursus</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('kursus.store') }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <!-- Input ID Kursus -->
                                                            <div class="mb-3">
                                                                <label for="id_kursus" class="form-label">Nama Kursus</label>
                                                                <select name="id_kursus" id="id_kursus" class="form-control" required>
                                                                    <option value="">-- Pilih Nama Kursus --</option>
                                                                    @foreach($kursusList as $kursusOption)
                                                                        <option value="{{ $kursusOption->id }}">{{ $kursusOption->nama_kursus }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Input ID Peserta -->
                                                            <div class="mb-3">
                                                                <label for="id_peserta" class="form-label">Nama Peserta</label>
                                                                <select name="id_peserta" id="id_peserta" class="form-control" required>
                                                                    <option value="">-- Pilih Peserta --</option>
                                                                    @foreach($pesertaList as $pesertaOption)
                                                                        <option value="{{ $pesertaOption->id }}">{{ $pesertaOption->nama_peserta }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tgl_masuk_kursus" class="form-label">Tanggal Masuk Kursus</label>
                                                                <input type="date" name="tgl_masuk_kursus" id="tgl_masuk_kursus" class="form-control" required>
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
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Kursus dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('kursus.upload') }}" method="POST" enctype="multipart/form-data">
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