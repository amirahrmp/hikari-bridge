@extends('layouts.master')

@section('jadwal_kursus_select','active')
@section('title', 'Detail Jadwal Kursus')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Jadwal Kursus</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
                <li class="breadcrumb-item">Jadwal Kursus</a></li>
              <li class="breadcrumb-item"><a href="#">Detail Jadwal Kursus</a></li>
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
                                            <i class="fa fa-plus"></i> Tambah Peserta
                                        </button>

                                        <h4>Kursus: {{ $jadwalKursus->kursus->nama_kursus }}</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><strong>Pengajar:</strong> {{ $jadwalKursus->teacher->nama_teacher }}</h6>
                                            </div>
                                            <div class="col-md-6 text-md-right">
                                                <h6><strong>Hari:</strong> {{ $jadwalKursus->hari }}</h5>
                                            </div>
                                        </div>
                              
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><strong>Waktu:</strong> {{ $jadwalKursus->waktu }}</h6>
                                            </div>
                                            <div class="col-md-6 text-md-right">
                                                <h6><strong>Tipe Kursus:</strong> {{ $jadwalKursus->tipe_kursus }}</h6>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            @if($jadwalKursus->peserta->isEmpty())
                                                <p>Belum ada peserta terdaftar.</p>
                                            @else
                                                <table id="datatable" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Peserta</th>
                                                            <th>Telp</th>
                                                            <th>Email</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($jadwalKursus->peserta as $index => $peserta)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $peserta->nama_peserta }}</td>
                                                                <td>{{ $peserta->telp }}</td>
                                                                <td>{{ $peserta->email }}</td>
                                                                <td class="text-center">
                                                                    <!-- Tombol untuk memicu modal konfirmasi hapus -->
                                                                    <button class="btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteForm('{{ route('jadwal_kursus.removePeserta', [$jadwalKursus->id, $peserta->id]) }}')">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>

                                        <a href="{{ route('jadwal_kursus.index') }}" class="btn btn-secondary">Kembali</a>
                                        
                                        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Peserta</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus peserta ini dari jadwal kursus?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <!-- Tombol konfirmasi hapus -->
                                                        <form id="deletePesertaForm" action="" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Tambah Data -->
                                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addModalLabel">Tambah Peserta Jadwal Kursus</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('jadwal_kursus.addPeserta', $jadwalKursus->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="id_peserta">Peserta</label>
                                                                <select name="id_peserta" id="id_peserta" class="form-control" required>
                                                                    @foreach($pesertaList as $peserta)
                                                                        <option value="{{ $peserta->id }}">{{ $peserta->nama_peserta }}</option>
                                                                    @endforeach
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
<script>
    function setDeleteForm(actionUrl) {
        // Set action form delete sesuai dengan URL yang diterima
        document.getElementById('deletePesertaForm').action = actionUrl;
    }
</script>

@endsection