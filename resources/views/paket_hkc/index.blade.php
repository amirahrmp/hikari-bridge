@extends('layouts.master')

@section('paket_hkc_select','active')
@section('title', 'Paket HKC')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Paket Hikari Kidz Club</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Paket Hikari Kidz Club</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPaketHkcModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Paket HKC</th>
                                                        <th>Tipe Member</th>                                                      
                                                        <th>Kelas</th>
                                                        <th>Uang Pendaftaran</th>
                                                        <th>Uang Perlengkapan</th>  
                                                        <th>Uang Sarana dan Prasarana</th>
                                                        <th>Uang SPP</th> 
                                                        <th>Tipe</th> 
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($paket_hkc as $p)
                                                    <tr>
                                                        <td>{{ $p->id_pakethkc }}</td>
                                                        <td>{{ $p->member }}</td>
                                                        <td>{{ $p->kelas }}</td>
                                                        <td>{{ rupiah(nominal: $p->u_pendaftaran) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_perlengkapan) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_sarana) }}</td>
                                                        <td>{{ rupiah(nominal:$p->u_spp) }}</td>
                                                        <td>{{ $p->tipe }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-paket_hkc/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
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
                                                                <form action="{{ route('paket_hkc.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_pakethkc" class="form-label">ID Paket:</label>
                                                                            <input type="text" class="form-control @error('id_pakethkc') is-invalid @enderror" id="id_pakethkc" name="id_pakethkc" value="{{ old('id_pakethkc', $p->id_pakethkc) }}" maxlength="10" required pattern="\d{1,10}">
                                                                            @error('id_pakethkc')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="member" class="form-label">Tipe Member:</label>
                                                                            <input type="text" class="form-control @error('member') is-invalid @enderror" id="member" name="member" value="{{ old('member', $p->member) }}" maxlength="50" required>
                                                                            @error('member')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="kelas" class="form-label">Tipe Kelas:</label>
                                                                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $p->kelas) }}" maxlength="50" required>
                                                                            @error('kelas')
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
                                                                            <label for="u_perlengkapan" class="form-label">Uang Perlengkapan:</label>
                                                                            <input type="number" class="form-control @error('u_perlengkapan') is-invalid @enderror" id="u_perlengkapan" name="u_perlengkapan" value="{{ old('u_perlengkapan', $p->u_perlengkapan) }}" maxlength="50" required>
                                                                            @error('u_perlengkapan')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="u_sarana" class="form-label">Uang Sarana dan Prasarana: <i>/thn</i></label>
                                                                            <input type="number" class="form-control @error('u_sarana') is-invalid @enderror" id="u_sarana" name="u_sarana" value="{{ old('u_sarana', $p->u_sarana) }}" maxlength="50" required>
                                                                            @error('u_sarana')
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
                                        <div class="modal fade" id="addPaketHkcModal" tabindex="-1" aria-labelledby="addPaketHkcModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addPaketHkcModalLabel">Tambah Data Paket</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('paket_hkc.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                        <div class="mb-3">
                                                                <label for="id_pakethkc" class="form-label">ID Paket:</label>
                                                                <input type="text" class="form-control @error('id_pakethkc') is-invalid @enderror" id="id_pakethkc" name="id_pakethkc" maxlength="15" required>
                                                                @error('id_pakethkc')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                                                                                        
                                                            <div class="mb-3">
                                                                <label for="member" class="form-label">Tipe Member:</label>
                                                                <input type="text" class="form-control @error('member') is-invalid @enderror" id="member" name="member" maxlength="50" required>
                                                                @error('member')
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
                                                                <label for="u_pendaftaran" class="form-label">Uang Pendaftaran:</label>
                                                                <input type="number" class="form-control @error('u_pendaftaran') is-invalid @enderror" id="u_pendaftaran" name="u_pendaftaran" maxlength="50" required>
                                                                @error('u_pendaftaran')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_perlengkapan" class="form-label">Uang Perlengkapan:</label>
                                                                <input type="number" class="form-control @error('u_perlengkapan') is-invalid @enderror" id="u_perlengkapan" name="u_perlengkapan" maxlength="50" required>
                                                                @error('u_perlengkapan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="u_sarana" class="form-label">Uang Sarana Dan Prasarana: <i>/thn</i></label>
                                                                <input type="number" class="form-control @error('u_sarana') is-invalid @enderror" id="u_sarana" name="u_sarana" maxlength="50" required>
                                                                @error('u_sarana')
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
                                                        <form action="{{ route('paket_hkc.upload') }}" method="POST" enctype="multipart/form-data">
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