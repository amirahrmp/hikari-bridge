@extends('layouts.master')

@section('jadwal_makan_daycare_select','active')
@section('title', 'Jadwal Makan Daycare')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Jadwal Makan Daycare</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Jadwal Makan Daycare</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <section class="content">
        <div class="container-fluid">
            <div class="card p-3">
    <button class="btn btn-primary mb-3 px-4 py-1" style="font-size: 0.875rem;" data-toggle="modal" data-target="#addModal">
    <i class="fa fa-plus me-1"></i> Tambah Jadwal
</button

                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Pekan</th>
                                <th>Hari</th>
                                <th>Snack Pagi</th>
                                <th>Makan Siang</th>
                                <th>Snack Sore</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalMakan as $item)
                                <tr>
                                    <td>{{ $item->bulan }}</td>
                                    <td>Pekan {{ $item->pekan }}</td>
                                    <td>{{ $item->hari }}</td>
                                    <td>{{ $item->snack_pagi }}</td>
                                    <td>{{ $item->makan_siang }}</td>
                                    <td>{{ $item->snack_sore }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('jadwal_makan_daycare.update', $item->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Jadwal Makan - {{ $item->hari }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="is_libur" value="0">
                                                    <div class="form-group">
                                                        <label>Bulan</label>
                                                        <select name="bulan" class="form-control" required>
                                                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                                                                <option value="{{ $bulan }}" {{ $item->bulan == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Pekan</label>
                                                        <select name="pekan" class="form-control" required>
                                                            @for($i = 1; $i <= 4; $i++)
                                                                <option value="{{ $i }}" {{ $item->pekan == $i ? 'selected' : '' }}>Pekan {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Hari</label>
                                                        <select name="hari" class="form-control" required>
                                                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
                                                                <option value="{{ $hari }}" {{ $item->hari == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Snack Pagi</label>
                                                        <input type="text" name="snack_pagi" value="{{ $item->snack_pagi }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Makan Siang</label>
                                                        <input type="text" name="makan_siang" value="{{ $item->makan_siang }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Snack Sore</label>
                                                        <input type="text" name="snack_sore" value="{{ $item->snack_sore }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MODAL TAMBAH --}}
                <div class="modal fade" id="addModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('jadwal_makan_daycare.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Jadwal Makan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="is_libur" value="0">
                                    <div class="form-group">
                                        <label>Bulan</label>
                                        <select name="bulan" class="form-control" required>
                                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                                                <option value="{{ $bulan }}">{{ $bulan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pekan</label>
                                        <select name="pekan" class="form-control" required>
                                            @for($i = 1; $i <= 4; $i++)
                                                <option value="{{ $i }}">Pekan {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Hari</label>
                                        <select name="hari" class="form-control" required>
                                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
                                                <option value="{{ $hari }}">{{ $hari }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Snack Pagi</label>
                                        <input type="text" name="snack_pagi" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Makan Siang</label>
                                        <input type="text" name="makan_siang" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Snack Sore</label>
                                        <input type="text" name="snack_sore" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- END MODAL --}}
            </div>
        </div>
    </section>
</div>
@endsection
