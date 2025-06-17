@extends('layouts.master')

@section('peserta_hikari_kidz_terverifikasi_select', 'active')
@section('title', 'Data Terverifikasi Hikari Kidz')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Peserta Terverifikasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item"><a href="#">Terverifikasi</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
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
                                                <h5 class="m-3">Peserta dengan Status <span class="badge badge-success">Terverifikasi</span></h5>
                                                <div class="table-responsive">
                                                    <table id="datatable" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID Anak</th>
                                                                <th>Status Keaktifan</th>
                                                                <th>Nama Lengkap</th>
                                                                <th>Nama Panggilan</th>
                                                                <th>Tanggal Lahir</th>
                                                                <th>Nama Orang Tua</th>
                                                                <th>Alamat</th>
                                                                <th>No WhatsApp</th>
                                                                <th>Tipe</th>
                                                                <th>Foto Anak</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($peserta_hikari_kidz as $p)
                                                                <tr>
                                                                    <td>{{ $p->id_anak }}</td>
                                                                     <td class="text-center">
                                                                        @if($p->status_keaktifan == 'Aktif')
                                                                            <span class="badge badge-success">AKTIF</span>
                                                                        @elseif($p->status_keaktifan == 'Cuti')
                                                                            <span class="badge badge-warning text-white">CUTI</span>
                                                                        @else
                                                                            <span class="badge badge-danger">TIDAK AKTIF</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $p->full_name }}</td>
                                                                    <td>{{ $p->nickname }}</td>
                                                                    <td>{{ $p->birth_date }}</td>
                                                                    <td>{{ $p->parent_name }}</td>
                                                                    <td>{{ $p->address }}</td>
                                                                    <td>{{ $p->whatsapp_number }}</td>
                                                                    <td>{{ $p->tipe }}</td>
                                                                    <td>
                                                                        @php
                                                                            $folder = '';
                                                                            if ($p->tipe === 'HKC') {
                                                                                $folder = 'kidzclub'; // $folder akan menjadi 'kidzclub'
                                                                            } elseif ($p->tipe === 'HKD') {
                                                                                $folder = 'kidzdaycare'; // $folder akan menjadi 'kidzdaycare'
                                                                            }

                                                                            $fotoPath = null;
                                                                            if ($p->file_upload) {
                                                                                $fotoPath = asset('uploads/' . $folder . '/' . $p->file_upload);
                                                                            }
                                                                        @endphp
                                                                        @if ($fotoPath) {{-- Jika $fotoPath berhasil dibuat (berarti file_upload ada) --}}
                                                                                <img src="{{ $fotoPath }}" alt="Foto Anak" width="100" class="img-thumbnail">
                                                                            @else
                                                                                <span class="text-muted">Tidak ada</span>
                                                                            @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <form action="{{ route('peserta_hikari_kidz.ubahstatus_keaktifan', $p->id_anak) }}" method="POST" class="d-flex justify-content-center">
                                                                            @csrf
                                                                            <input type="hidden" name="redirect" value="terverifikasi">

                                                                            <select name="status_keaktifan" class="form-control form-control-sm mr-2" style="width: 120px;">
                                                                                <option value="Aktif" {{ $p->status_keaktifan == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                                                <option value="Cuti" {{ $p->status_keaktifan == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                                                                <option value="Tidak Aktif" {{ $p->status_keaktifan == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                                            </select>

                                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                                <i class="fas fa-edit"></i> Ubah
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="11" class="text-center">Tidak ada peserta yang terverifikasi.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="card-footer clearfix"></div>
                                        </div>
                                    </div>
                                </div> <!-- /.row -->
                            </div><!-- /.container-fluid -->
                        </div><!-- /.content -->
                    </div><!-- /.button-container -->
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
