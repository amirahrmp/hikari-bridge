@extends('layouts.master')

@section('paket_hq_select','active')
@section('title', 'Paket HQ')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Paket Hikari Quran</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Paket Hikari Quran</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPaketHqModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Paket Quran</th>                                                    
                                                        <th>Kelas</th>
                                                        <th>Kapasitas</th>
                                                        <th>Kurasi</th>
                                                        <th>Uang Pendaftaran</th>
                                                        <th>Uang Modul</th>
                                                        <th>Uang SPP</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($paket_hq as $p)
                                                    <tr>
                                                        <td>{{ $p->id_pakethq }}</td>
                                                        <td>{{ $p->kelas }}</td>
                                                        <td>{{ $p->kapasitas }}</td>
                                                        <td>{{ $p->durasi }}</td>
                                                        <td>{{ rupiah(nominal: $p->u_pendaftaran) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_modul) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_spp) }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-paket_hq/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>                                                      
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Paket</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('paket_hq.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_pakethq" class="form-label">ID Paket:</label>
                                                                            <input type="text" class="form-control @error('id_pakethq') is-invalid @enderror" id="id_pakethq" name="id_pakethq" value="{{ old('id_pakethq', $p->id_pakethq) }}" maxlength="10" required pattern="\d{1,10}">
                                                                            @error('id_pakethq')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="kelas" class="form-label">Jenis Kelas:</label>
                                                                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $p->kelas) }}" maxlength="50" required>
                                                                            @error('kelas')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="kapasitas" class="form-label">Kapasitas:</label>
                                                                            <input type="text" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $p->kapasitas) }}" maxlength="50" required>
                                                                            @error('kapasitas')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="durasi" class="form-label">Durasi:</label>
                                                                            <input type="text" class="form-control @error('durasi') is-invalid @enderror" id="durasi" name="durasi" value="{{ old('durasi', $p->durasi) }}" maxlength="50" required>
                                                                            @error('durasi')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="u_pendaftaran" class="form-label">Uang Pendaftaran: </label>
                                                                            <input type="number" class="form-control @error('u_pendaftaran') is-invalid @enderror" id="u_pendaftaran" name="u_pendaftaran" value="{{ old('u_pendaftaran', $p->u_pendaftaran) }}" maxlength="50" required>
                                                                            @error('u_pendaftaran')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="u_modul" class="form-label">Uang Modul:</label>
                                                                            <input type="number" class="form-control @error('u_modul') is-invalid @enderror" id="u_modul" name="u_modul" value="{{ old('u_modul', $p->u_modul) }}" maxlength="50" required>
                                                                            @error('u_modul')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="u_spp" class="form-label">Uang SPP: </label>
                                                                            <input type="number" class="form-control @error('u_spp') is-invalid @enderror" id="u_spp" name="u_spp" value="{{ old('u_spp', $p->u_spp) }}" maxlength="50" required>
                                                                            @error('u_spp')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-primary toastrDefaultSuccess">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Modal Tambah Data -->
                                        <div class="modal fade" id="addPaketHqModal" tabindex="-1" aria-labelledby="addPaketHqModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addPaketHqModalLabel">Tambah Data Paket</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('paket_hq.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                        <div class="mb-3">
                                                                <label for="id_pakethq" class="form-label">ID Paket:</label>
                                                                <input type="text" class="form-control @error('id_pakethq') is-invalid @enderror" id="id_pakethq" name="id_pakethq" maxlength="15" required>
                                                                @error('id_pakethq')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="kelas" class="form-label">Kelas:</label>
                                                                <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" maxlength="50" required>
                                                                @error('kelas')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="kapasitas" class="form-label">Kapasitas:</label>
                                                                <input type="text" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" maxlength="50" required>
                                                                @error('kapasitas')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="durasi" class="form-label">Durasi:</label>
                                                                <input type="text" class="form-control @error('durasi') is-invalid @enderror" id="durasi" name="durasi" maxlength="50" required>
                                                                @error('durasi')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                    
                                                            <div class="mb-3">
                                                                <label for="u_pendaftaran" class="form-label">Uang Pendaftaran:</label>
                                                                <input type="number" class="form-control @error('u_pendaftaran') is-invalid @enderror" id="u_pendaftaran" name="u_pendaftaran" maxlength="50" required>
                                                                @error('u_pendaftaran')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_modul" class="form-label">Uang Modul:</label>
                                                                <input type="number" class="form-control @error('u_modul') is-invalid @enderror" id="u_modul" name="u_modul" maxlength="50" required>
                                                                @error('u_modul')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_spp" class="form-label">Uang SPP: </label>
                                                                <input type="number" class="form-control @error('u_spp') is-invalid @enderror" id="u_spp" name="u_spp" maxlength="50" required>
                                                                @error('u_spp')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary toastrDefaultSuccess">Simpan</button>
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
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Paket dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('paket_hq.upload') }}" method="POST" enctype="multipart/form-data">
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