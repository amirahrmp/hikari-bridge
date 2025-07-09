@extends('layouts.master')

@section('jadwal_hikari_kidz_select','active')
@section('title', 'Jadwal Hikari Kidz Daycare')


@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Jadwal Hikari Kidz Daycare</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Jadwal Hikari Kidz Daycare</a></li>
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
                                      <form method="GET" action="{{ route('jadwal_hikari_kidz.index') }}" class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select name="tipe_daycare" class="form-control" onchange="this.form.submit()">
                                                    <option value="">-- Pilih Tipe Daycare --</option>
                                                    <option value="Half Day" {{ request()->get('tipe_daycare') == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                                                    <option value="Full Day" {{ request()->get('tipe_daycare') == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                                                    <option value="Full Day Long Hours" {{ request()->get('tipe_daycare') == 'Full Day Long Hours' ? 'selected' : '' }}>Full Day Long Hours</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>

                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addJadwalHikariKidzModal">
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
                                                        <th>Tipe Daycare</th>
                                                        <th>Waktu Mulai</th>
                                                        <th>Waktu Selesai</th>
                                                        <th>Kegiatan</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($jadwalhikarikidz as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucfirst($item->tipe_daycare) }}</td>
                                                    <td>{{ $item->waktu_mulai }}</td>
                                                    <td>{{ $item->waktu_selesai }}</td>
                                                    <td>{{ $item->kegiatan }}</td>
                                                    <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('jadwal_hikari_kidz.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>                                                     
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Ubah Data Jadwal Hikari Kidz Daycare</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('jadwal_hikari_kidz.update', $item->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="form-group">
                                                                            <label>Tipe Daycare</label>
                                                                            <select name="tipe_daycare" class="form-control" required>
                                                                            <option value="Half Day" {{ $item->tipe_daycare == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                                                                            <option value="Full Day" {{ $item->tipe_daycare == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                                                                            <option value="Full Day Long Hours" {{ $item->tipe_daycare == 'Full Day Long Hours' ? 'selected' : '' }}>Full Day Long Hours</option>
                                                                            </select>
                                                                        <div class="form-group">
                                                                            <label>Waktu Mulai</label>
                                                                            <input type="time" name="waktu_mulai" class="form-control" value="{{ $item->waktu_mulai }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Waktu Selesai</label>
                                                                            <input type="time" name="waktu_selesai" class="form-control" value="{{ $item->waktu_selesai }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Kegiatan</label>
                                                                            <input type="text" name="kegiatan" class="form-control" value="{{ $item->kegiatan }}" required>
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
                                        <div class="modal fade" id="addJadwalHikariKidzModal" tabindex="-1" aria-labelledby="addJadwalHikariKidzModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addJadwalHikariKidzModalLabel">Tambah Data Jadwal Hikari Kidz Daycare</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('jadwal_hikari_kidz.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                        <div class="form-group">
                                                    <label>Tipe Daycare</label>
                                                    <select name="tipe_daycare" class="form-control" required>
                                                        <option value="">-- Pilih Tipe --</option>
                                                        <option value="Half Day">Half Day</option>
                                                        <option value="Full Day">Full Day</option>
                                                        <option value="Full Day Long Hours">Full Day Long Hours</option>
                                                    </select>
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Waktu Mulai</label>
                                                    <input type="time" name="waktu_mulai" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Waktu Selesai</label>
                                                    <input type="time" name="waktu_selesai" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Kegiatan</label>
                                                    <input type="text" name="kegiatan" class="form-control" required>
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
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Jadwal dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('jadwal_hikari_kidz.upload') }}" method="POST" enctype="multipart/form-data">
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