@extends('layouts2.master')

@section('title', 'Pendaftaran Program Hikari Kidz')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4">Pendaftaran Program Hikari Kidz</h1>
    <div class="row">
        <!-- Daycare -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('img/daycare.jpg') }}" class="card-img-top" alt="Daycare">
                <div class="card-body text-center">
                    <h5 class="card-title">Daycare</h5>
                    <p class="text-muted">Kategori: Anak-anak</p>
                    <p>Penyediaan penitipan untuk anak di bawah 2 tahun.</p>
                    <a href="{{ route('registerkidzdaycare.create') }}" class="btn btn-primary mt-3">Daftar</a>
                </div>
            </div>
        </div>

        <!-- Hikari Kidz Club -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('img/daycare.jpg') }}" class="card-img-top" alt="Hikari Kidz Club">
                <div class="card-body text-center">
                    <h5 class="card-title">Hikari Kidz Club</h5>
                    <p class="text-muted">Kategori: Edukasi</p>
                    <p>Kursus menyenangkan untuk mengembangkan keterampilan anak.</p>
                    <a href="{{ route('registerkidzclub.create') }}" class="btn btn-primary mt-3">Daftar</a>
                </div>
            </div>
        </div>

        <!-- HKC Weekend -->
        <!-- <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('img/daycare.jpg') }}" class="card-img-top" alt="Program HKCW">
                <div class="card-body text-center">
                    <h5 class="card-title">Program Lain HKC Weekend</h5>
                    <p class="text-muted">Kategori: Henriz, Kunjungan Gigi, Dll</p>
                    <p>Kursus membaca dan memahami Al-Quran untuk anak-anak.</p>
                    <a href="{{ route('registerprogramhkcw.create') }}" class="btn btn-primary mt-3">Daftar</a>
                </div>
            </div>
        </div> -->

        <!-- Jika ingin tambahkan Hikari Quran, buka blok ini -->
        {{--
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('img/daycare.jpg') }}" class="card-img-top" alt="Hikari Quran">
                <div class="card-body text-center">
                    <h5 class="card-title">Hikari Quran</h5>
                    <p class="text-muted">Kategori: Religi</p>
                    <p>Kursus membaca dan memahami Al-Quran untuk anak-anak.</p>
                    <a href="{{ route('registerquran.create') }}" class="btn btn-primary mt-3">Daftar</a>
                </div>
            </div>
        </div>
        --}}
    </div>
</div>
@endsection
