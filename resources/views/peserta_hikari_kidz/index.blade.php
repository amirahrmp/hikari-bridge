@extends('layouts.master')

@section('peserta_hikari_kidz_select','active')
@section('title', 'Peserta Hikari Kidz')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Peserta Hikari Kidz</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Riwayat Peserta Hikari Kidz</a></li>
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
                                                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                                                    <i class="fa fa-upload"></i> Impor Excel
                                                </button>

                                                <div class="table-responsive">
                                                    <table id="datatable" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID Anak</th>
                                                                <th>Status</th>
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
                                                            @if($peserta_hikari_kidz->isEmpty())
                                                                <tr>
                                                                    <td colspan="11" class="text-center">Tidak ada data anak untuk daycare/hkc ini.</td>
                                                                </tr>
                                                            @else
                                                                @foreach($peserta_hikari_kidz as $p)
                                                                    <tr>
                                                                        <td>{{ $p->id_anak }}</td>
                                                                        <td class="text-center">
                                                                            @if($p->status == 'Terverifikasi')
                                                                                <span class="badge badge-success">Terverifikasi</span>
                                                                            @else($p->status == 'Menunggu')
                                                                                <span class="badge badge-secondary">Menunggu</span>
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
                                                                        <td>
                                                                            <a href="{{ route('peserta_hikari_kidz.ubahstatus', $p->id_anak) }}"
                                                                                class="btn-sm {{ $p->status == 'Terverifikasi' ? 'btn-success' : 'btn-warning' }}"
                                                                                title="Klik untuk ubah status peserta">
                                                                                @if ($p->status == 'Terverifikasi')
                                                                                    <i class="fas fa-check-circle"> Verifikasi</i> 
                                                                                @else
                                                                                    <i class="fas fa-hourglass-half"> Menunggu</i>
                                                                                @endif
                                                                            </a> <br><br>
                                                                            <a href="{{ route('peserta.vcard', $p->id_anak) }}" class="btn-sm btn-primary" title="Simpan Kontak Peserta ini">
                                                                                <i class="fas fa-user-plus"> Simpan Kontak</i>
                                                                            </a><br><br>
                                                                            <a href="{{ route('peserta_hikari_kidz.kirimWa', $p->id_anak) }}"
                                                                                target="_blank" 
                                                                                class="btn-sm btn-success" 
                                                                                title="Kirim WhatsApp ke Peserta ini">
                                                                                <i class="fab fa-whatsapp"> Kirim WA</i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Modal Upload Excel -->
                                                <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Peserta dari Excel</h5>
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
                                            </div> <!-- /.card-body -->
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
