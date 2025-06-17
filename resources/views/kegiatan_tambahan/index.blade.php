@extends('layouts.master')

@section('kegiatan_tambahan_select','active')
@section('title', 'Kegiatan Tambahan')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kegiatan Tambahan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Kegiatan Tambahan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-12">
                    <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                        <div class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="d-flex justify-content-between align-items-center mb-3 p-3">
                                                    <div>
                                                        <button class="btn btn-primary" data-toggle="modal" data-target="#addKegiatanTambahanModal">
                                                            <i class="fa fa-plus"></i> Tambah Data
                                                        </button>
                                                        <button class="btn btn-success ml-2" data-toggle="modal" data-target="#uploadExcelModal">
                                                            <i class="fa fa-upload"></i> Impor Excel
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="table-responsive p-3">
                                                    <table id="datatable" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Nama Anak</th>
                                                                <th>Nama Kegiatan</th>
                                                                <th>Biaya</th>
                                                                <th>Deskripsi</th>
                                                                <th>Status Pembayaran</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($kegiatan_tambahan as $kegiatan)
                                                            <tr>
                                                                <td>{{ $kegiatan->id }}</td>
                                                                <td>
                                                                    @if($kegiatan->anak)
                                                                        {{ $kegiatan->anak->full_name }} ({{ $kegiatan->anak->id_anak }})
                                                                    @else
                                                                        ID Anak Tidak Ditemukan ({{ $kegiatan->id_anak }})
                                                                    @endif
                                                                </td>
                                                                <td>{{ $kegiatan->nama_kegiatan }}</td>
                                                                <td>{{ rupiah(nominal: $kegiatan->biaya) }}</td>
                                                                <td>{{ $kegiatan->deskripsi ?? '-' }}</td>
                                                                <td>
                                                                    {{-- Tampilan Kolom Status Pembayaran --}}
                                                                    @if (strtolower($kegiatan->status_pembayaran) == 'belum')
                                                                        <span class="badge badge-warning">Belum Lunas</span>
                                                                    @else
                                                                        <span class="badge badge-success">Lunas</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{-- Tombol Toggle Status Pembayaran --}}
                                                                    <form action="{{ route('kegiatan_tambahan.ubahstatus', $kegiatan->id) }}" method="POST" style="display:inline-block; margin-right: 5px;">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit"
                                                                                class="btn btn-sm {{ strtolower($kegiatan->status_pembayaran) == 'lunas' ? 'btn-success' : 'btn-warning' }}"
                                                                                title="Klik untuk mengubah status"
                                                                                onclick="return confirm('Yakin ingin mengubah status pembayaran kegiatan {{ $kegiatan->nama_kegiatan }} untuk {{ $kegiatan->anak->full_name ?? 'ID Anak: ' . $kegiatan->id_anak }}?')">
                                                                            {{-- Teks tombol dan icon mencerminkan STATUS SAAT INI --}}
                                                                            @if (strtolower($kegiatan->status_pembayaran) == 'lunas')
                                                                                <i class="fas fa-check-circle"></i> Lunas
                                                                            @else
                                                                                <i class="fas fa-hourglass-half"></i> Belum Lunas
                                                                            @endif
                                                                        </button>
                                                                    </form>
                                                                     <button class="btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editModal{{ $kegiatan->id }}">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                    {{-- Tombol Hapus --}}
                                                                    <form action="{{ route('kegiatan_tambahan.destroy', $kegiatan->id) }}" method="POST" style="display:inline-block;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-danger" id="delete-btn-{{ $kegiatan->id }}" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                            <i class="fa fa-trash"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>

                                                            {{-- Modal Edit (tetap di dalam loop @forelse) --}}
                                                            <div class="modal fade" id="editModal{{ $kegiatan->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $kegiatan->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-default">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="editModalLabel{{ $kegiatan->id }}">Ubah Data Kegiatan Tambahan</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="{{ route('kegiatan_tambahan.update', $kegiatan->id) }}" method="post">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="modal-body">
                                                                                {{-- DROPDOWN UNTUK ID ANAK DI MODAL EDIT --}}
                                                                                <div class="mb-3">
                                                                                    <label for="edit_id_anak_{{ $kegiatan->id }}" class="form-label">Nama Anak:</label>
                                                                                    <select class="form-control @error('id_anak') is-invalid @enderror" id="edit_id_anak_{{ $kegiatan->id }}" name="id_anak" required>
                                                                                        <option value="">-- Pilih Anak --</option>
                                                                                        @foreach($anak as $a)
                                                                                            <option value="{{ $a->id_anak }}" {{ $kegiatan->id_anak == $a->id_anak ? 'selected' : '' }}>
                                                                                                {{ $a->full_name }} ({{ $a->id_anak }})
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    @error('id_anak')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                                {{-- Input fields lainnya... --}}
                                                                                <div class="mb-3">
                                                                                    <label for="edit_nama_kegiatan_{{ $kegiatan->id }}" class="form-label">Nama Kegiatan:</label>
                                                                                    <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="edit_nama_kegiatan_{{ $kegiatan->id }}" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" maxlength="255" required>
                                                                                    @error('nama_kegiatan')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="biaya" class="form-label">Biaya:</label>
                                                                                    <input type="number" class="form-control @error('biaya') is-invalid @enderror" id="biaya" name="biaya" value="{{ old('biaya', $kegiatan->biaya) }}" maxlength="50" required>
                                                                                    @error('biaya')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="edit_deskripsi_{{ $kegiatan->id }}" class="form-label">Deskripsi:</label>
                                                                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="edit_deskripsi_{{ $kegiatan->id }}" name="deskripsi" rows="3">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                                                                                    @error('deskripsi')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
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
                                                            @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center">Tidak ada data kegiatan tambahan.</td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>

                                                {{-- Modal Tambah Data (tetap sama seperti sebelumnya) --}}
                                                <div class="modal fade" id="addKegiatanTambahanModal" tabindex="-1" aria-labelledby="addKegiatanTambahanModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-default">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addKegiatanTambahanModalLabel">Tambah Data Kegiatan Tambahan</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('kegiatan_tambahan.store') }}" method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="id_anak" class="form-label">Nama Anak:</label>
                                                                        <select class="form-control @error('id_anak') is-invalid @enderror" id="id_anak" name="id_anak" required>
                                                                            <option value="">-- Pilih Anak --</option>
                                                                            @foreach($anak as $a)
                                                                                <option value="{{ $a->id_anak }}" {{ old('id_anak') == $a->id_anak ? 'selected' : '' }}>
                                                                                    {{ $a->full_name }} ({{ $a->id_anak }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('id_anak')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    {{-- Input fields lainnya... --}}
                                                                    <div class="mb-3">
                                                                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan:</label>
                                                                        <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan" name="nama_kegiatan" maxlength="255" required>
                                                                        @error('nama_kegiatan')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="biaya" class="form-label">Biaya:</label>
                                                                        <input type="number" class="form-control @error('biaya') is-invalid @enderror" id="biaya" name="biaya" required>
                                                                        @error('biaya')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="deskripsi" class="form-label">Deskripsi:</label>
                                                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"></textarea>
                                                                        @error('deskripsi')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
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

                                                {{-- Modal Impor Excel (tetap sama seperti sebelumnya) --}}
                                                <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Kegiatan Tambahan dari Excel</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('kegiatan_tambahan.upload') }}" method="POST" enctype="multipart/form-data">
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
                                            <div class="card-footer clearfix">
                                                {{-- Pagination di sini jika Anda menggunakan pagination --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
