@extends('layouts.master')

@section('staf_select','active')
@section('title', 'Staf')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Staf</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Master Data</a></li>
              <li class="breadcrumb-item"><a href="#">Staf</a></li>
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
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addStafModal">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                            <i class="fa fa-upload"></i> Impor Excel
                                        </button>
            
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Card</th>
                                                        <th>Status</th>
                                                        <th style="width: 500px;">Nama</th>
                                                        <th>Jabatan</th>
                                                        <th>Departemen</th>
                                                        <th>Waktu Kerja</th>
                                                        <th>Tipe Staf</th>
                                                        <th>Telp</th>
                                                        <th>Tgl Masuk Kerja</th>
                                                        <th>NIK</th>
                                                        <th>NPWP</th>
                                                        <th>PTKP</th>                                
                                                        <th>JK</th>
                                                        <th>Email</th>
                                                        <th>Tmp Lahir</th>
                                                        <th>Tgl Lahir</th>
                                                        <th>Alamat</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($staf as $p)
                                                    <tr>
                                                        <td>{{ $p->id_card }}</td>
                                                        <td>
                                                            <span class="badge {{ $p->status == 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                                                                {{ $p->status }}
                                                            </span>
                                                        </td>
                                                        <td style="width: 500px;">{{ $p->nama_staf }}</td>
                                                        <td>{{ $p->jabatan }}</td>
                                                        <td>{{ $p->departemen }}</td>
                                                        <td>{{ $p->waktu_kerja }}</td>
                                                        <td>{{ $p->tipe_staf }}</td>
                                                        <td>{{ $p->telp }}</td>
                                                        <td>{{ $p->tgl_masuk_kerja }}</td>
                                                        <td>{{ $p->nik }}</td>
                                                        <td>{{ $p->npwp }}</td>
                                                        <td>{{ $p->ptkp }}</td>
                                                        <td>{{ $p->jk }}</td>
                                                        <td>{{ $p->email }}</td>
                                                        <td>{{ $p->tmp_lahir }}</td>
                                                        <td>{{ $p->tgl_lahir }}</td>
                                                        <td>{{ $p->alamat }}</td>
                                                        <td>
                                                            <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button href="{{ URL::to('delete-staf/'.$p->id) }}" class="btn-sm btn-danger d-inline-block" id="delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>                                                      
                                                        </td>
                                                    </tr>
                                                    <!-- Modal untuk Ubah Data -->
                                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Ubah Data Staf</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('staf.update', $p->id) }}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="id_card" class="form-label">ID Card:</label>
                                                                            <input type="text" class="form-control @error('id_card') is-invalid @enderror" id="id_card" name="id_card" value="{{ old('id_card', $p->id_card) }}" maxlength="10" required pattern="\d{1,10}">
                                                                            @error('id_card')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="status" class="form-label">Status:</label>
                                                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                                                                <option value="Aktif" {{ old('status', $p->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                                                <option value="Nonaktif" {{ old('status', $p->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                                            </select>
                                                                            @error('status')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="nik" class="form-label">NIK:</label>
                                                                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $p->nik) }}" maxlength="16">
                                                                            @error('nik')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    
                                                                        <div class="mb-3">
                                                                            <label for="nama_staf" class="form-label">Nama:</label>
                                                                            <input type="text" class="form-control @error('nama_staf') is-invalid @enderror" id="nama_staf" name="nama_staf" value="{{ old('nama_staf', $p->nama_staf) }}" maxlength="50" required>
                                                                            @error('nama_staf')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="jabatan" class="form-label">Jabatan:</label>
                                                                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $p->jabatan) }}" maxlength="50" required>
                                                                            @error('jabatan')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="departemen" class="form-label">Departemen:</label>
                                                                            <input type="text" class="form-control @error('departemen') is-invalid @enderror" id="departemen" name="departemen" value="{{ old('departemen', $p->departemen) }}" maxlength="50" required>
                                                                            @error('departemen')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="waktu_kerja" class="form-label">Waktu Kerja:</label>
                                                                            <input type="text" class="form-control @error('waktu_kerja') is-invalid @enderror" id="waktu_kerja" name="waktu_kerja" value="{{ old('waktu_kerja', $p->waktu_kerja) }}" maxlength="50" required>
                                                                            @error('waktu_kerja')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="tipe_staf" class="form-label">Tipe Staf:</label>
                                                                            <select class="form-control @error('tipe_staf') is-invalid @enderror" id="tipe_staf" name="tipe_staf" required>
                                                                                <option value="Staf Tetap" {{ old('tipe_staf', $p->tipe_staf) == 1 ? 'selected' : '' }}>Staf Tetap</option>
                                                                                <option value="Staf Non Tetap" {{ old('tipe_staf', $p->tipe_staf) == 0 ? 'selected' : '' }}>Staf Non Tetap</option>
                                                                                <option value="Staf Daycare" {{ old('tipe_staf', $p->tipe_staf) == 0 ? 'selected' : '' }}>Staf Daycare</option>
                                                                            </select>
                                                                            @error('tipe_staf')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="ptkp" class="form-label">PTKP:</label>
                                                                            <select class="form-control @error('ptkp') is-invalid @enderror" id="ptkp" name="ptkp">
                                                                                <option value="" disabled selected>Pilih PTKP</option>
                                                                                <option value="TK/0" {{ old('ptkp', $p->ptkp) == 'TK/0' ? 'selected' : '' }}>TK/0 (Tidak Kawin, Tanpa Tanggungan)</option>
                                                                                <option value="K/0" {{ old('ptkp', $p->ptkp) == 'K/0' ? 'selected' : '' }}>K/0 (Kawin, Tanpa Tanggungan)</option>
                                                                                <option value="K/1" {{ old('ptkp', $p->ptkp) == 'K/1' ? 'selected' : '' }}>K/1 (Kawin, 1 Tanggungan)</option>
                                                                                <option value="K/2" {{ old('ptkp', $p->ptkp) == 'K/2' ? 'selected' : '' }}>K/2 (Kawin, 2 Tanggungan)</option>
                                                                                <option value="K/3" {{ old('ptkp', $p->ptkp) == 'K/3' ? 'selected' : '' }}>K/3 (Kawin, 3 Tanggungan)</option>
                                                                            </select>
                                                                            @error('ptkp')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>                                                                        

                                                                        <div class="mb-3">
                                                                            <label for="npwp" class="form-label">NPWP:</label>
                                                                            <input type="number" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" value="{{ old('npwp', $p->npwp) }}" maxlength="50">
                                                                            @error('npwp')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="tgl_masuk_kerja" class="form-label">Tgl Masuk Kerja:</label>
                                                                            <input type="date" class="form-control @error('tgl_masuk_kerja') is-invalid @enderror" id="tgl_masuk_kerja" name="tgl_masuk_kerja" value="{{ old('tgl_masuk_kerja', $p->tgl_masuk_kerja) }}">
                                                                            @error('tgl_masuk_kerja')
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
                                                                            <label for="tmp_lahir" class="form-label">Tempat Lahir:</label>
                                                                            <input type="text" class="form-control @error('tmp_lahir') is-invalid @enderror" id="tmp_lahir" name="tmp_lahir" value="{{ old('tmp_lahir', $p->tmp_lahir) }}" maxlength="50" required>
                                                                            @error('tmp_lahir')
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
                                        <div class="modal fade" id="addStafModal" tabindex="-1" aria-labelledby="addStafModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-default">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addStafModalLabel">Tambah Data Staf</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('staf.store') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="id_card" class="form-label">ID Card:</label>
                                                                <input type="text" class="form-control @error('id_card') is-invalid @enderror" id="id_card" name="id_card" maxlength="10" required pattern="\d{1,10}">
                                                                @error('id_card')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="status" class="form-label">Status:</label>
                                                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                                                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                                    <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                                </select>
                                                                @error('status')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="nik" class="form-label">NIK:</label>
                                                                <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" maxlength="16">
                                                                @error('nik')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                    
                                                            <div class="mb-3">
                                                                <label for="nama_staf" class="form-label">Nama:</label>
                                                                <input type="text" class="form-control @error('nama_staf') is-invalid @enderror" id="nama_staf" name="nama_staf" maxlength="50" required>
                                                                @error('nama_staf')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="jabatan" class="form-label">Jabatan:</label>
                                                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" maxlength="50" required>
                                                                @error('jabatan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="departemen" class="form-label">Departemen:</label>
                                                                <input type="text" class="form-control @error('departemen') is-invalid @enderror" id="departemen" name="departemen" maxlength="50" required>
                                                                @error('departemen')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="waktu_kerja" class="form-label">Waktu Kerja:</label>
                                                                <input type="text" class="form-control @error('waktu_kerja') is-invalid @enderror" id="waktu_kerja" name="waktu_kerja" maxlength="50" required>
                                                                @error('waktu_kerja')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tipe_staf" class="form-label">Tipe Staf:</label>
                                                                <select class="form-control @error('tipe_staf') is-invalid @enderror" id="tipe_staf" name="tipe_staf" required>
                                                                    <option value="Staf Tetap">Staf Tetap</option>
                                                                    <option value="Staf Non Tetap">Staf Non Tetap</option>
                                                                    <option value="Staf Daycare">Staf Daycare</option>
                                                                </select>
                                                                @error('tipe_staf')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="ptkp" class="form-label">PTKP:</label>
                                                                <select class="form-control @error('ptkp') is-invalid @enderror" id="ptkp" name="ptkp" required>
                                                                    <option value="" disabled selected>Pilih PTKP</option>
                                                                    <option value="TK/0">TK/0 (Tidak Kawin, Tanpa Tanggungan)</option>
                                                                    <option value="K/0">K/0 (Kawin, Tanpa Tanggungan)</option>
                                                                    <option value="K/1">K/1 (Kawin, 1 Tanggungan)</option>
                                                                    <option value="K/2">K/2 (Kawin, 2 Tanggungan)</option>
                                                                    <option value="K/3">K/3 (Kawin, 3 Tanggungan)</option>
                                                                </select>
                                                                @error('ptkp')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="npwp" class="form-label">NPWP:</label>
                                                                <input type="number" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" maxlength="50" required>
                                                                @error('npwp')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="tgl_masuk_kerja" class="form-label">Tgl Masuk Kerja:</label>
                                                                <input type="date" class="form-control @error('tgl_masuk_kerja') is-invalid @enderror" id="tgl_masuk_kerja" name="tgl_masuk_kerja" required>
                                                                @error('tgl_masuk_kerja')
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
                                                                <label for="tmp_lahir" class="form-label">Tempat Lahir:</label>
                                                                <input type="text" class="form-control @error('tmp_lahir') is-invalid @enderror" id="tmp_lahir" name="tmp_lahir" maxlength="50" required>
                                                                @error('tmp_lahir')
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
                                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Staf dari Excel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('staf.upload') }}" method="POST" enctype="multipart/form-data">
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