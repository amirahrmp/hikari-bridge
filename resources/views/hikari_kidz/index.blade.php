@extends('layouts.master')

@section('hikari_kidz_select','active')
@section('title', 'Data Hikari Kidz')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Data Hikari Kidz</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Data Hikari Kidz</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addHikariKidzModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Hikari Kidz</th>
                                                        <th>Nama Hikari Kidz</th>
                                                        <th>Jenis Hikari Kidz</th>                                                    
                                                        <th>Paket</th>
                                                        <th>Kelas</th>  
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($hikari_kidz as $p)
                                                    <tr>
                                                        <td>{{ $p->id_hikari_kidz }}</td>
                                                        <td>{{ $p->nama_hikari_kidz }}</td>
                                                        <td>{{ $p->jenis_hikari_kidz }}</td>
                                                        <td>{{ $p->paket }}</td>
                                                        <td>{{ $p->kelas }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-hikari_kidz/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>                                                      
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Hikari Kidz</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('hikari_kidz.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_hikari_kidz" class="form-label">ID Hikari Kidz:</label>
                                                                            <input type="text" class="form-control @error('id_hikari_kidz') is-invalid @enderror" id="id_hikari_kidz" name="id_hikari_kidz" value="{{ old('id_hikari_kidz', $p->id_hikari_kidz) }}" maxlength="15" required>
                                                                            @error('id_hikari_kidz')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="nama_hikari_kidz" class="form-label">Nama hikari Kidz:</label>
                                                                            <input type="text" class="form-control @error('nama_hikari_kidz') is-invalid @enderror" id="nama_hikari_kidz" name="nama_hikari_kidz" value="{{ old('nama_hikari_kidz', $p->nama_hikari_kidz) }}" maxlength="50" required>
                                                                            @error('nama_hikari_kidz')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="jenis">Jenis Hikari Kidz</label>
                                                                            <select name="jenis" id="jenis" class="form-control" required>
                                                                                <option value="">-- Pilih Jenis Hikari Kidz Club --</option>
                                                                                <option value="Kids Club" {{ $p->jenis_hikari_kidz == 'Kids Club' ? 'selected' : '' }}>Kids Club</option>
                                                                                <option value="Daycare" {{ $p->jenis_hikari_kidz == 'Daycare' ? 'selected' : '' }}>Daycare</option>
                                                                            </select>
                                                                        </div>
                                                                    
                                                                        <!-- <div class="mb-3">
                                                                            <label for="paket" class="form-label">Paket:</label>
                                                                            <input type="text" class="form-control @error('paket') is-invalid @enderror" id="paket" name="paket" value="{{ old('paket', $p->paket) }}" maxlength="50" required>
                                                                            @error('paket')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div> -->

                                                                        <div class="form-group">
                                                                            <label for="id_paket">Nama Paket</label>
                                                                            <select name="id_paket" id="id_paket" class="form-control" required>
                                                                                @foreach($pkt as $paket)
                                                                                    <option value="{{ $paket->id }}" {{ $p->id_paket == $paket->id ? 'selected' : '' }}>{{ $paket->nama_paket }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="kelas" class="form-label">Kelas:</label>
                                                                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $p->kelas) }}" maxlength="50" required>
                                                                            @error('kelas')
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
                                        <div class="modal fade" id="addHikariKidzModal" tabindex="-1" aria-labelledby="addHikariKidzModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addHikariKidzModalLabel">Tambah Data Hikari Kidz</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('hikari_kidz.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="id_hikari_kidz" class="form-label">ID Hikari Kidz:</label>
                                                                <input type="text" class="form-control @error('id_hikari_kidz') is-invalid @enderror" id="id_hikari_kidz" name="id_hikari_kidz" maxlength="15" required>
                                                                @error('id_hikari_kidz')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                                                    
                                                            <div class="mb-3">
                                                                <label for="nama_hikari_kidz" class="form-label">Nama hikari Kidz:</label>
                                                                <input type="text" class="form-control @error('nama_hikari_kidz') is-invalid @enderror" id="nama_hikari_kidz" name="nama_hikari_kidz" maxlength="50" required>
                                                                @error('nama_hikari_kidz')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="jenis">Jenis Hikari Kidz</label>
                                                                <select name="jenis" id="jenis" class="form-control" required>
                                                                    <option value="">-- Pilih Jenis Hikari Kidz Club --</option>
                                                                    <option value="Kids Club" {{ $pkt->jenis_hikari_kidz == 'Kids Club' ? 'selected' : '' }}>Kids Club</option>
                                                                    <option value="Daycare" {{ $p->jenis_hikari_kidz == 'Daycare' ? 'selected' : '' }}>Daycare</option>
                                                                </select>
                                                            </div>
                                                    
                                                            <!-- <div class="mb-3">
                                                                <label for="paket" class="form-label">Paket:</label>
                                                                <input type="text" class="form-control @error('paket') is-invalid @enderror" id="paket" name="paket" maxlength="50" required>
                                                                @error('paket')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div> -->

                                                            <div class="form-group">
                                                                <label for="id_paket">Nama Paket</label>
                                                                <select name="id_paket" id="id_paket" class="form-control" required>
                                                                    <option value="">-- Pilih Nama Paket --</option>
                                                                    @foreach($pkt as $paket)
                                                                        <option value="{{ $paket->id }}">{{ $paket->nama_paket }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="kelas" class="form-label">Kelas:</label>
                                                                <input type="kelas" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" maxlength="50" required>
                                                                @error('kelas')
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