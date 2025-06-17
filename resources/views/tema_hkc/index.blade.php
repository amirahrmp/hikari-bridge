@extends('layouts.master')

@section('tema_hkc_select','active')
@section('title', 'Tema Bulanan Hikari Kidz Club')

@section('content')
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tema Bulanan HKC</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Tema HKC</li>
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
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">           
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="p-3">
                                            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addTemaModal">
                                                <i class="fa fa-plus"></i> Tambah Data
                                            </button>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Tema</th>
                                                        <th>Bulan</th>
                                                        <th>Tahun Ajaran</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; 
                                                    @endphp
                                                    @forelse($temaHkc as $tema)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $tema->tema }}</td>
                                                        <td>{{ $tema->bulan }}</td>
                                                        <td>{{ $tema->tahun_ajaran }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning d-inline-block" data-toggle="modal" data-target="#editTemaModal{{ $tema->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <a href="{{ route('tema_hkc.delete', $tema->id) }}" class="btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus tema ini?')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="editTemaModal{{ $tema->id }}" tabindex="-1" aria-labelledby="editTemaModalLabel{{ $tema->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('tema_hkc.update', $tema->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Tema</h5>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label>Nama Tema</label>
                                                                            <input type="text" name="tema" class="form-control" value="{{ $tema->tema }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Bulan</label>
                                                                            <select name="bulan" class="form-control" required>
                                                                                @php
                                                                                    $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                                                                @endphp
                                                                                @foreach ($bulanList as $bln)
                                                                                    <option value="{{ $bln }}" {{ (isset($tema) && $tema->bulan == $bln) ? 'selected' : '' }}>
                                                                                        {{ $bln }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Tahun Ajaran</label>
                                                                            <input type="text" name="tahun_ajaran" class="form-control" value="{{ $tema->tahun_ajaran }}" required>
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
                                                        <td colspan="5" class="text-center">Belum ada data</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Modal Tambah Tema -->
                                        <div class="modal fade" id="addTemaModal" tabindex="-1" aria-labelledby="addTemaModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('tema_hkc.store') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tambah Tema</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nama Tema</label>
                                                                <input type="text" name="tema" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Bulan</label>
                                                                <select name="bulan" class="form-control" required>
                                                                    @php
                                                                        $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                                                    @endphp
                                                                    @foreach ($bulanList as $bln)
                                                                        <option value="{{ $bln }}" {{ (isset($tema) && $tema->bulan == $bln) ? 'selected' : '' }}>
                                                                            {{ $bln }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tahun Ajaran</label>
                                                                <input type="text" name="tahun_ajaran" class="form-control" required>
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
                                    </div>
                                </div>

                                <div class="card-footer clearfix">

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
