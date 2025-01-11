@extends('layouts.master')

@section('peserta_kursus_select','active')
@section('title', 'Peserta Kursus')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Peserta Kursus</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Peserta Kursus</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPesertaKursusModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Peserta</th>
                                                        <th>Nama</th>
                                                        <th>Nama Ortu</th>                                                      
                                                        <th>JK</th>
                                                        <th>Telp</th>
                                                        <th>Email</th>  
                                                        <th>Tgl Lahir</th>
                                                        <th>Alamat</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($peserta_kursus as $p)
                                                    <tr>
                                                        <td>{{ $p->id_peserta }}</td>
                                                        <td>{{ $p->nama_peserta }}</td>
                                                        <td>{{ $p->nama_ortu }}</td>
                                                        <td>{{ $p->jk }}</td>
                                                        <td>{{ $p->telp }}</td>
                                                        <td>{{ $p->email }}</td>
                                                        <td>{{ $p->tgl_lahir }}</td>
                                                        <td>{{ $p->alamat }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-peserta_kursus/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>                                                      
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Peserta Kursus</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('peserta_kursus.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_peserta" class="form-label">ID Peserta:</label>
                                                                            <input type="text" class="form-control @error('id_peserta') is-invalid @enderror" id="id_peserta" name="id_peserta" value="{{ old('id_peserta', $p->id_peserta) }}" maxlength="10" required pattern="\d{1,10}">
                                                                            @error('id_peserta')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="nama_peserta" class="form-label">Nama:</label>
                                                                            <input type="text" class="form-control @error('nama_peserta') is-invalid @enderror" id="nama_peserta" name="nama_peserta" value="{{ old('nama_peserta', $p->nama_peserta) }}" maxlength="50" required>
                                                                            @error('nama_peserta')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="nama_ortu" class="form-label">Nama Ortu:</label>
                                                                            <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $p->nama_ortu) }}" maxlength="50" required>
                                                                            @error('nama_ortu')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="alamat" class="form-label">Alamat:</label>
                                                                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" maxlength="100" required>{{ old('alamat', $p->alamat) }}</textarea>
                                                                            @error('alamat')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="jk" class="form-label">Jenis Kelamin:</label>
                                                                            <select name="jk" id="jk" class="form-control @error('jk') is-invalid @enderror">
                                                                                <option value="L" {{ $p->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                                                                <option value="P" {{ $p->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                                            </select>
                                                                            @error('jk')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="telp" class="form-label">Telp:</label>
                                                                            <input type="text" class="form-control @error('telp') is-invalid @enderror" id="telp" name="telp" value="{{ old('telp', $p->telp) }}" maxlength="15" required pattern="\d{5,15}">
                                                                            @error('telp')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="email" class="form-label">Email:</label>
                                                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $p->email) }}" maxlength="50" required>
                                                                            @error('email')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="tgl_lahir" class="form-label">Tanggal Lahir:</label>
                                                                            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $p->tgl_lahir) }}" required>
                                                                            @error('tgl_lahir')
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
                                        <div class="modal fade" id="addPesertaKursusModal" tabindex="-1" aria-labelledby="addPesertaKursusModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addPesertaKursusModalLabel">Tambah Data Peserta Kursus</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('peserta_kursus.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="id_peserta" class="form-label">ID Peserta:</label>
                                                                <input type="text" class="form-control @error('id_peserta') is-invalid @enderror" id="id_peserta" name="id_peserta" maxlength="10" required pattern="\d{1,10}">
                                                                @error('id_peserta')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                                                    
                                                            <div class="mb-3">
                                                                <label for="nama_peserta" class="form-label">Nama:</label>
                                                                <input type="text" class="form-control @error('nama_peserta') is-invalid @enderror" id="nama_peserta" name="nama_peserta" maxlength="50" required>
                                                                @error('nama_peserta')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="nama_ortu" class="form-label">Nama Ortu:</label>
                                                                <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" id="nama_ortu" name="nama_ortu" maxlength="50" required>
                                                                @error('nama_ortu')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="alamat" class="form-label">Alamat:</label>
                                                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" maxlength="100" required></textarea>
                                                                @error('alamat')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="jk" class="form-label">Jenis Kelamin:</label>
                                                                <select name="jk" id="jk" class="form-control @error('jk') is-invalid @enderror">
                                                                    <option value="L">Laki-Laki</option>
                                                                    <option value="P">Perempuan</option>
                                                                </select>
                                                                @error('jk')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="telp" class="form-label">Telp:</label>
                                                                <input type="text" class="form-control @error('telp') is-invalid @enderror" id="telp" name="telp" maxlength="15" required pattern="\d{5,15}">
                                                                @error('telp')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email:</label>
                                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" maxlength="50" required>
                                                                @error('email')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="tgl_lahir" class="form-label">Tanggal Lahir:</label>
                                                                <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" required>
                                                                @error('tgl_lahir')
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
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Peserta Kursus dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('peserta_kursus.upload') }}" method="POST" enctype="multipart/form-data">
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