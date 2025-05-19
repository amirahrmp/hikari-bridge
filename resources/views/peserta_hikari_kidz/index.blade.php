@extends('layouts.master')

@section('peserta_hikari_kidz_select','active')
@section('title', 'Peserta Hikari Kidz')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Peserta Hikari Kidz</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Peserta Hikari Kidz</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPesertaHikariKidzModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Anak</th>
                                                        <th>Nama Lengkap</th>
                                                        <th>Nama Panggilan</th>                                                      
                                                        <th>Tanggal Lahir</th>
                                                        <th>Nama Orang Tua</th>
                                                        <th>Alamat</th> 
                                                        <th>No Telp</th> 
                                                        <th>Tipe</th>
                                                        <th>Foto Anak</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($peserta_hikari_kidz as $p)
                                                    <tr>
                                                        <td>{{ $p->id_anak }}</td>
                                                        <td>{{ $p->full_name }}</td>
                                                        <td>{{ $p->nickname }}</td>
                                                        <td>{{ $p->birth_date }}</td>
                                                        <td>{{ $p->parent_name }}</td>
                                                        <td>{{ $p->address }}</td>
                                                        <td>{{ $p->whatsapp_number }}</td>
                                                        <td>{{ $p->tipe }}</td>
                                                        <td>{{ $p->file_upload }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-peserta_hikari_kidz/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>                                                      
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Peserta Hikari Kidz</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('peserta_hikari_kidz.update', $p->id) }}" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_anak" class="form-label">ID Anak:</label>
                                                                            <input type="text" class="form-control @error('id_anak') is-invalid @enderror" id="id_anak" name="id_anak" value="{{ old('id_anak', $p->id_anak) }}" maxlength="10" required pattern="\d{1,10}">
                                                                            @error('id_anak')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="full_name" class="form-label">Nama Lengkap:</label>
                                                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $p->full_name) }}" maxlength="50" required>
                                                                            @error('full_name')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="nickname" class="form-label">Nama Panggilan:</label>
                                                                            <input type="text" class="form-control @error('nickname') is-invalid @enderror" id="nickname" name="nickname" value="{{ old('nickname', $p->nickname) }}" maxlength="50" required>
                                                                            @error('nickname')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="birth_date" class="form-label">Tanggal Lahir:</label>
                                                                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $p->birth_date) }}" required>
                                                                            @error('birth_date')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="parent_name" class="form-label">Nama Orang Tua:</label>
                                                                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" value="{{ old('parent_name', $p->parent_name) }}" maxlength="50" required>
                                                                            @error('parent_name')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="address" class="form-label">Alamat:</label>
                                                                            <input type="address" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $p->address) }}" maxlength="50" required>
                                                                            @error('address')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="whatsapp_number" class="form-label">No Telp:</label>
                                                                            <input type="number" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $p->whatsapp_number) }}" maxlength="15" required pattern="\d{5,15}">
                                                                            @error('whatsapp_number')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="tipe" class="form-label">Tipe:</label>
                                                                            <input type="tipe" class="form-control @error('tipe') is-invalid @enderror" id="tipe" name="tipe" value="{{ old('tipe', $p->tipe) }}" maxlength="50" required>
                                                                            @error('tipe')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="file_upload" class="form-label">Foto Anak:</label>
                                                                            <input type="file" class="form-control @error('file_upload') is-invalid @enderror" id="file_upload" name="file_upload" maxlength="50" accept=".jpg,.jpeg,.png">
                                                                            @error('file_upload')
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
                                        <div class="modal fade" id="addPesertaHikariKidzModal" tabindex="-1" aria-labelledby="addPesertaHikariKidzModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addPesertaHikariKidzModalLabel">Tambah Data Peserta Hikari Kidz</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('peserta_hikari_kidz.store') }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="id_anak" class="form-label">ID Anak:</label>
                                                                <input type="text" class="form-control @error('id_anak') is-invalid @enderror" id="id_anak" name="id_anak" maxlength="10" required pattern="\d{1,10}">
                                                                @error('id_anak')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                                                    
                                                            <div class="mb-3">
                                                                <label for="full_name" class="form-label">Nama Lengkap:</label>
                                                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" maxlength="50" required>
                                                                @error('full_name')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="nickname" class="form-label">Nama Panggilan:</label>
                                                                <input type="text" class="form-control @error('nickname') is-invalid @enderror" id="nickname" name="nickname" maxlength="50" required>
                                                                @error('nickname')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="birth_date" class="form-label">Tanggal Lahir:</label>
                                                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" required>
                                                                @error('birth_date')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="parent_name" class="form-label">Nama Orang Tua:</label>
                                                                <input type="parent_name" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" maxlength="50" required>
                                                                @error('parent_name')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="address" class="form-label">Alamat:</label>
                                                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" maxlength="100" required></textarea>
                                                                @error('address')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="whatsapp_number" class="form-label">No Telp:</label>
                                                                <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" maxlength="15" required pattern="\d{5,15}">
                                                                @error('whatsapp_number')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tipe" class="form-label">Tipe:</label>
                                                                <input type="tipe" class="form-control @error('tipe') is-invalid @enderror" id="tipe" name="tipe" maxlength="50" required>
                                                                @error('tipe')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="file_upload" class="form-label">Foto Anak:</label>
                                                                <input type="file" class="form-control @error('file_upload') is-invalid @enderror" id="file_upload" name="file_upload" maxlength="50" accept=".jpg,.jpeg,.png required>
                                                                @error('file_upload')
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
                                                        <form action="{{ route('peserta_hikari_kidz.upload') }}" method="POST" enctype="multipart/form-data">
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