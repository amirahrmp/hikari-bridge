@extends('layouts.master')

@section('jadwal_hikari_kidz_select','active')
@section('title', 'Jadwal Hikari Kidz')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Jadwal Hikari Kidz</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Tenaga Pengasuh</a></li>
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
                                <div class="card-body p-0">
                                    <div>
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Hari</th>
                                                        <th>Nama Hikari Kidz</th>
                                                        <th>Nama Pengasuh</th>
                                                        <th>Tipe Hikari Kidz</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($jadwalHikariKidz as $p)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $p->hari }}</td>
                                                        <td>{{ $p->hikari_kidz->nama_hikari_kidz }}</td>
                                                        <td>{{ optional($p->pengasuh)->nama_pengasuh }}</td>
                                                        <td>{{ $p->tipe_hikari_kidz }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-info" title="Lihat Peserta" onclick="window.location.href='{{ route('jadwal_hikari_kidz.show', $p->id) }}'">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn-sm btn-warning d-inline-block" title="Ubah Jadwal" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-jadwal_hikari_kidz/'.$p->id) }}" title="Hapus Jadwal" class="btn-sm btn-danger" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>        
                                                        </td>
                                                        
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Jadwal Hikari Kidz/h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('jadwal_hikari_kidz.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="hari">Hari</label>
                                                                            <select name="hari" id="hari" class="form-control" required>
                                                                                <option value="Senin" {{ $p->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                                                                <option value="Selasa" {{ $p->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                                                                <option value="Rabu" {{ $p->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                                                                <option value="Kamis" {{ $p->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                                                                <option value="Jumat" {{ $p->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                                                                <option value="Sabtu" {{ $p->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                                                                <option value="Minggu" {{ $p->hari == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="waktu">Waktu</label>
                                                                            <input type="text" name="waktu" id="waktu" class="form-control" value="{{ $p->waktu }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="id_hikari_kidz">Nama Hikari Kidz</label>
                                                                            <select name="id_hikari_kidz" id="id_hikari_kidz" class="form-control" required>
                                                                                @foreach($hikarikidzs as $hikarikidzItem)
                                                                                    <option value="{{ $hikarikidzItem->id }}" {{ $p->id_hikari_kidz == $hikarikidzItem->id ? 'selected' : '' }}>{{ $hikarikidzItem->nama_hikari_kidz }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="id_pengasuh">Nama Pengasuh</label>
                                                                            <select name="id_pengasuh" id="id_pengasuh" class="form-control" required>
                                                                                @foreach($pengasuhs as $pengasuh)
                                                                                    <option value="{{ $pengasuh->id }}" {{ $p->id_pengasuh == $pengasuh->id ? 'selected' : '' }}>{{ $pengasuh->nama_pengasuh }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="tipe_hikari_kidz">Tipe Hikari Kidz</label>
                                                                            <select name="tipe_hikari_kidz" id="tipe_hikari_kidz" class="form-control" required>
                                                                                <option value="online" {{ $p->tipe_hikari_kidz == 'online' ? 'selected' : '' }}>Online</option>
                                                                                <option value="offline" {{ $p->tipe_hikari_kidz == 'offline' ? 'selected' : '' }}>Offline</option>
                                                                            </select>
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
                                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addModalLabel">Tambah Data Jadwal Hikari Kidz</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('jadwal_hikari_kidz.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="hari">Hari</label>
                                                                <select name="hari" id="hari" class="form-control" required>
                                                                    <option value="">-- Pilih Hari --</option>
                                                                    <option value="Senin">Senin</option>
                                                                    <option value="Selasa">Selasa</option>
                                                                    <option value="Rabu">Rabu</option>
                                                                    <option value="Kamis">Kamis</option>
                                                                    <option value="Jumat">Jumat</option>
                                                                    <option value="Sabtu">Sabtu</option>
                                                                    <option value="Minggu">Minggu</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="waktu">Waktu</label>
                                                                <input type="text" name="waktu" id="waktu" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="id_hikari_kidz">Nama Hikari Kidz</label>
                                                                <select name="id_hikari_kidz" id="id_hikari_kidz" class="form-control" required>
                                                                    <option value="">-- Pilih Nama Hikari Kidz --</option>
                                                                    @foreach($hikarikidzs as $hikarikidz)
                                                                        <option value="{{ $hikarikidz->id }}">{{ $hikarikidz->nama_hikari_kidz }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="id_pengasuh">Nama Pengajar</label>
                                                                <select name="id_pengasuh" id="id_pengasuh" class="form-control" required>
                                                                    <option value="">-- Pilih Nama Pengajar --</option>
                                                                    @foreach($pengasuhs as $pengasuh)
                                                                        <option value="{{ $pengasuh->id }}">{{ $pengasuh->nama_pengasuh }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tipe_hikari_kidz">Tipe Hikari Kidz</label>
                                                                <select name="tipe_hikari_kidz" id="tipe_hikari_kidz" class="form-control" required>
                                                                    <option value="">-- Pilih Tipe Hikari Kidz --</option>
                                                                    <option value="online">Online</option>
                                                                    <option value="offline">Offline</option>
                                                                </select>
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