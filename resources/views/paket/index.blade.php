@extends('layouts.master')

@section('paket_select','active')
@section('title', 'Paket')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Paket</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Paket</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPaketModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Paket</th>
                                                        <th>Nama Paket</th>                                                      
                                                        <th>Durasi Jam</th>
                                                        <th>Uang Pendaftaran</th>
                                                        <th>Uang Pangkal</th>  
                                                        <th>Uang Kegiatan</th>
                                                        <th>Uang SPP</th> 
                                                        <th>Uang Makan</th>
                                                        <th>Tipe</th> 
                                                        <th>Biaya Penitipan</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($paket as $p)
                                                    <tr>
                                                        <td>{{ $p->id_paket }}</td>
                                                        <td>{{ $p->nama_paket }}</td>
                                                        <td>{{ $p->durasi_jam }}</td>
                                                        <td>{{ rupiah(nominal: $p->u_pendaftaran) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_pangkal) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_kegiatan) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_spp) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_makan) }}</td>
                                                        <td>{{ $p->tipe }}</td>
                                                        <td>{{ rupiah(nominal:$p->biaya_penitipan) }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-paket/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
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
                                                                <form action="{{ route('paket.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_paket" class="form-label">ID Paket:</label>
                                                                            <input type="text" class="form-control @error('id_paket') is-invalid @enderror" id="id_paket" name="id_paket" value="{{ old('id_paket', $p->id_paket) }}" maxlength="10" required pattern="\d{1,10}">
                                                                            @error('id_paket')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="nama_paket" class="form-label">Nama Paket:</label>
                                                                            <input type="text" class="form-control @error('nama_paket') is-invalid @enderror" id="nama_paket" name="nama_paket" value="{{ old('nama_paket', $p->nama_paket) }}" maxlength="50" required>
                                                                            @error('nama_paket')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="durasi_jam" class="form-label">Durasi Jam:</label>
                                                                            <input type="text" class="form-control @error('durasi_jam') is-invalid @enderror" id="durasi_jam" name="durasi_jam" value="{{ old('durasi_jam', $p->durasi_jam) }}" maxlength="50" required>
                                                                            @error('durasi_jam')
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
                                                                            <label for="u_pangkal" class="form-label">Uang Pangkal:</label>
                                                                            <input type="number" class="form-control @error('u_pangkal') is-invalid @enderror" id="u_pangkal" name="u_pangkal" value="{{ old('u_pangkal', $p->u_pangkal) }}" maxlength="50" required>
                                                                            @error('u_pangkal')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="u_kegiatan" class="form-label">Uang Kegiatan: <i>/thn</i></label>
                                                                            <input type="number" class="form-control @error('u_kegiatan') is-invalid @enderror" id="u_kegiatan" name="u_kegiatan" value="{{ old('u_kegiatan', $p->u_kegiatan) }}" maxlength="50" required>
                                                                            @error('u_kegiatan')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="u_spp" class="form-label">Uang SPP: <i>/bln</i></label>
                                                                            <input type="number" class="form-control @error('u_spp') is-invalid @enderror" id="u_spp" name="u_spp" value="{{ old('u_spp', $p->u_spp) }}" maxlength="50" required>
                                                                            @error('u_spp')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="u_makan" class="form-label">Uang Makan: <i>/bln</i></label>
                                                                            <input type="number" class="form-control @error('u_makan') is-invalid @enderror" id="u_makan" name="u_makan" value="{{ old('u_makan', $p->u_makan) }}" maxlength="50" required>
                                                                            @error('u_makan')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="tipe" class="form-label">Tipe:</label>
                                                                            <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror">
                                                                                <option value="Bulanan" {{ $p->tipe == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                                                <option value="Harian" {{ $p->tipe == 'Harian' ? 'selected' : '' }}>Harian</option>
                                                                            </select>
                                                                            @error('tipe')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="biaya_penitipan" class="form-label">Biaya Penitipan:</label>
                                                                            <input type="number" class="form-control @error('biaya_penitipan') is-invalid @enderror" id="biaya_penitipan" name="biaya_penitipan" value="{{ old('biaya_penitipan', $p->biaya_penitipan) }}" maxlength="50" required>
                                                                            @error('biaya_penitipan')
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
                                        <div class="modal fade" id="addPaketModal" tabindex="-1" aria-labelledby="addPaketModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addPaketModalLabel">Tambah Data Paket</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('paket.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                        <div class="mb-3">
                                                                <label for="id_paket" class="form-label">ID Paket:</label>
                                                                <input type="text" class="form-control @error('id_paket') is-invalid @enderror" id="id_paket" name="id_paket" maxlength="15" required>
                                                                @error('id_paket')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                                                                                        
                                                            <div class="mb-3">
                                                                <label for="nama_paket" class="form-label">Nama Paket:</label>
                                                                <input type="text" class="form-control @error('nama_paket') is-invalid @enderror" id="nama_paket" name="nama_paket" maxlength="50" required>
                                                                @error('nama_paket')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="durasi_jam" class="form-label">Durasi Jam:</label>
                                                                <input type="text" class="form-control @error('durasi_jam') is-invalid @enderror" id="durasi_jam" name="durasi_jam" maxlength="50" required>
                                                                @error('durasi_jam')
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
                                                                <label for="u_pangkal" class="form-label">Uang Pangkal:</label>
                                                                <input type="number" class="form-control @error('u_pangkal') is-invalid @enderror" id="u_pangkal" name="u_pangkal" maxlength="50" required>
                                                                @error('u_pangkal')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_kegiatan" class="form-label">Uang Kegiatan: <i>/thn</i></label>
                                                                <input type="number" class="form-control @error('u_kegiatan') is-invalid @enderror" id="u_kegiatan" name="u_kegiatan" maxlength="50" required>
                                                                @error('u_kegiatan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_spp" class="form-label">Uang SPP: <i>/bln</i></label>
                                                                <input type="number" class="form-control @error('u_spp') is-invalid @enderror" id="u_spp" name="u_spp" maxlength="50" required>
                                                                @error('u_spp')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_makan" class="form-label">Uang Makan: <i>/bln</i></label>
                                                                <input type="number" class="form-control @error('u_makan') is-invalid @enderror" id="u_makan" name="u_makan" maxlength="50" required>
                                                                @error('u_makan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                    
                                                            <div class="mb-3">
                                                                <label for="tipe" class="form-label">Tipe:</label>
                                                                <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror">
                                                                    <option value="Bulanan">Bulanan</option>
                                                                    <option value="Harian">Harian</option>
                                                                </select>
                                                                @error('tipe')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="biaya_penitipan" class="form-label">Biaya Penitipan:</label>
                                                                <input type="number" class="form-control @error('biaya_penitipan') is-invalid @enderror" id="biaya_penitipan" name="biaya_penitipan" maxlength="50" required>
                                                                @error('biaya_penitipan')
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
                                                        <form action="{{ route('paket.upload') }}" method="POST" enctype="multipart/form-data">
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
