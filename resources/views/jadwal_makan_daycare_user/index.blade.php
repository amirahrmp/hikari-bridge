@extends('layouts.master')

@section('jadwal_makan_daycare_user_select','active')
@section('title', 'Jadwal Makan Daycare')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Jadwal Makan Daycare</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Informasi</li>
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
                {{-- TIDAK ADA TOMBOL TAMBAH --}}

                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead class="bg-success text-white text-center">
                            <tr>
                                <th>Bulan</th>
                                <th>Pekan</th>
                                <th>Hari</th>
                                <th>Snack Pagi</th>
                                <th>Makan Siang</th>
                                <th>Snack Sore</th>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
