@extends('layouts2.master')

@section('register_kidz_club_select','active')
@section('title', 'Data Pendaftaran HKC Saya')

@section('content')

<br><br><br>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Daftar Pendaftaran Hikari Kidz Club</h2>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($registrasi->isEmpty())
                <p class="text-center">Belum ada pendaftaran yang dilakukan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Anak</th>
                                <th>Nama Panggilan</th>
                                <th>Tanggal Lahir</th>
                                <th>Nama Orang Tua</th>
                                <th>Tipe Member</th>
                                <th>Kelas</th>
                                <th>Total Bayar</th>
                                <th>Promotor</th>
                                <th>File Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrasi as $index => $r)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $r->full_name }}</td>
                                    <td>{{ $r->nickname }}</td>
                                    <td>{{ \Carbon\Carbon::parse($r->birth_date)->format('d-m-Y') }}</td>
                                    <td>{{ $r->parent_name }}</td>
                                    <td>{{ $r->member }}</td>
                                    <td>{{ $r->kelas }}</td>
                                    <td>Rp {{ number_format($r->total_bayar ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $r->promotor }}</td>
                                    <td>
                                        @if($r->file_upload)
                                            <a href="{{ asset('uploads/kidzclub/' . $r->file_upload) }}" target="_blank">Lihat</a>
                                        @else
                                            Tidak ada
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('registerkidzclub.create') }}" class="btn btn-primary">+ Daftar Baru</a>
            </div>
        </div>
    </div>
</div>

@endsection
