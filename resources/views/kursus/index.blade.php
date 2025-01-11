@extends('layouts.master')

@section('kursus_select','active')
@section('title', 'Data Kursus')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Kursus</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Data Kursus</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addKursusModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Kursus</th>
                                                        <th>Nama Kursus</th>
                                                        <th>Jenis Kursus</th>                                                      
                                                        <th>Level</th>
                                                        <th>Kategori</th>
                                                        <th>Kelas</th>  
                                                        <th>Kapasitas</th>
                                                        <th>Waktu</th>
                                                        <th>Biaya</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($kursus as $p)
                                                    <tr>
                                                        <td>{{ $p->id_kursus }}</td>
                                                        <td>{{ $p->nama_kursus }}</td>
                                                        <td>{{ $p->jenis_kursus }}</td>
                                                        <td>{{ $p->level }}</td>
                                                        <td>{{ $p->kategori }}</td>
                                                        <td>{{ $p->kelas }}</td>
                                                        <td>{{ $p->kapasitas }}</td>
                                                        <td>{{ $p->waktu }}</td>
                                                        <td>{{ rupiah($p->biaya) }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-kursus/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>                                                      
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Kursus</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('kursus.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_kursus" class="form-label">ID Kursus:</label>
                                                                            <input type="text" class="form-control @error('id_kursus') is-invalid @enderror" id="id_kursus" name="id_kursus" value="{{ old('id_kursus', $p->id_kursus) }}" maxlength="15" required>
                                                                            @error('id_kursus')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="nama_kursus" class="form-label">Nama Kursus:</label>
                                                                            <input type="text" class="form-control @error('nama_kursus') is-invalid @enderror" id="nama_kursus" name="nama_kursus" value="{{ old('nama_kursus', $p->nama_kursus) }}" maxlength="50" required>
                                                                            @error('nama_kursus')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="jenis_kursus" class="form-label">Jenis Kursus:</label>
                                                                            <input type="text" class="form-control @error('jenis_kursus') is-invalid @enderror" id="jenis_kursus" name="jenis_kursus" value="{{ old('jenis_kursus', $p->jenis_kursus) }}" maxlength="50" required>
                                                                            @error('jenis_kursus')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="level" class="form-label">Level:</label>
                                                                            <input type="text" class="form-control @error('level') is-invalid @enderror" id="level" name="level" value="{{ old('level', $p->level) }}" maxlength="50" required>
                                                                            @error('level')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="kategori" class="form-label">Kategori:</label>
                                                                            <input type="text" class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori" value="{{ old('kategori', $p->kategori) }}" maxlength="50" required>
                                                                            @error('kategori')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="kelas" class="form-label">Kelas:</label>
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
                                                                            <label for="waktu" class="form-label">Waktu:</label>
                                                                            <input type="text" class="form-control @error('waktu') is-invalid @enderror" id="waktu" name="waktu" value="{{ old('waktu', $p->waktu) }}" maxlength="50" required>
                                                                            @error('waktu')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="biaya" class="form-label">Biaya:</label>
                                                                            <input type="number" class="form-control @error('biaya') is-invalid @enderror" id="biaya" name="biaya" value="{{ old('biaya', $p->biaya) }}" maxlength="50" required>
                                                                            @error('biaya')
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
                                        <div class="modal fade" id="addKursusModal" tabindex="-1" aria-labelledby="addKursusModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addKursusModalLabel">Tambah Data Kursus</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('kursus.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="id_kursus" class="form-label">ID Kursus:</label>
                                                                <input type="text" class="form-control @error('id_kursus') is-invalid @enderror" id="id_kursus" name="id_kursus" maxlength="15" required>
                                                                @error('id_kursus')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                                                    
                                                            <div class="mb-3">
                                                                <label for="nama_kursus" class="form-label">Nama Kursus:</label>
                                                                <input type="text" class="form-control @error('nama_kursus') is-invalid @enderror" id="nama_kursus" name="nama_kursus" maxlength="50" required>
                                                                @error('nama_kursus')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="jenis_kursus" class="form-label">Jenis Kursus:</label>
                                                                <input type="text" class="form-control @error('jenis_kursus') is-invalid @enderror" id="jenis_kursus" name="jenis_kursus" maxlength="50" required>
                                                                @error('jenis_kursus')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="level" class="form-label">Level:</label>
                                                                <input type="text" class="form-control @error('level') is-invalid @enderror" id="level" name="level" maxlength="50" required>
                                                                @error('level')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="kategori" class="form-label">Kategori:</label>
                                                                <input type="text" class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori" maxlength="50" required>
                                                                @error('kategori')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="kelas" class="form-label">Kelas:</label>
                                                                <input type="kelas" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" maxlength="50" required>
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
                                                                <label for="waktu" class="form-label">Waktu:</label>
                                                                <input type="waktu" class="form-control @error('waktu') is-invalid @enderror" id="waktu" name="waktu" maxlength="50" required>
                                                                @error('waktu')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="biaya" class="form-label">Biaya:</label>
                                                                <input type="number" class="form-control @error('biaya') is-invalid @enderror" id="biaya" name="biaya" maxlength="15" required>
                                                                @error('biaya')
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