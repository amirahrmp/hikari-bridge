@extends('layouts.master')

@section('jadwal_makan_daycare_select','active')
@section('title', 'Jadwal Makan Daycare')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Jadwal Makan Daycare</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Jadwal Makan Daycare</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addJadwalMakanDaycareModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Hari</th>                                                      
                                                        <th>Snack Pagi</th>
                                                        <th>Makan Siang</th>
                                                        <th>Snack Sore</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($jadwal_makan_daycare as $p)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $p->hari }}</td>
                                                        <td>{{ $p->snack_pagi }}</td>
                                                        <td>{{ $p->makan_siang }}</td>
                                                        <td>{{ $p->snack_sore }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-jadwal_makan_daycare/'.$p->id) }}" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>  
                                                                                                                
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Jadwal Makan Daycare</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('jadwal_makan_daycare.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="hari" class="form-label">Hari:</label>
                                                                            <input type="text" class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari" value="{{ old('hari', $p->hari) }}" maxlength="50" required>
                                                                            @error('hari')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="snack_pagi" class="form-label">Snack Pagi:</label>
                                                                            <input type="text" class="form-control @error('snack_pagi') is-invalid @enderror" id="snack_pagi" name="snack_pagi" value="{{ old('snack_pagi', $p->snack_pagi) }}" maxlength="50" required>
                                                                            @error('snack_pagi')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="makan_siang" class="form-label">Makan Siang:</label>
                                                                            <input type="text" class="form-control @error('makan_siang') is-invalid @enderror" id="makan_siang" name="makan_siang" value="{{ old('makan_siang', $p->makan_siang) }}" maxlength="50" required>
                                                                            @error('makan_siang')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="snack_sore" class="form-label">Snack Sore:</label>
                                                                            <input type="text" class="form-control @error('snack_sore') is-invalid @enderror" id="snack_sore" name="snack_sore" value="{{ old('snack_sore', $p->snack_sore) }}" maxlength="50" required>
                                                                            @error('snack_sore')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
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
                                        <div class="modal fade" id="addJadwalMakanDaycareModal" tabindex="-1" aria-labelledby="addJadwalMakanDaycareModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addJadwalMakanDaycareModalLabel">Tambah Data Jadwal Makan Daycare</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('jadwal_makan_daycare.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">                                                        
                                                            <div class="mb-3">
                                                                <label for="hari" class="form-label">Hari:</label>
                                                                <input type="text" class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari" maxlength="50" required>
                                                                @error('hari')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="snack_pagi" class="form-label">Snack Pagi:</label>
                                                                <input type="text" class="form-control @error('snack_pagi') is-invalid @enderror" id="snack_pagi" name="snack_pagi" maxlength="50" required>
                                                                @error('snack_pagi')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                                    
                                                             <div class="mb-3">
                                                                <label for="makan_siang" class="form-label">Makan Siang:</label>
                                                                <input type="text" class="form-control @error('makan_siang') is-invalid @enderror" id="makan_siang" name="makan_siang" maxlength="50" required>
                                                                @error('makan_siang')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="snack_sore" class="form-label">Snack Sore:</label>
                                                                <input type="text" class="form-control @error('snack_sore') is-invalid @enderror" id="snack_sore" name="snack_sore" maxlength="50" required>
                                                                @error('snack_sore')
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
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Jadwal Makan Daycare dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('jadwal_makan_daycare.upload') }}" method="POST" enctype="multipart/form-data">
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