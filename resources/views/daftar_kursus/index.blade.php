@extends('layouts2.master')

@section('title', 'Pendaftaran Program Hikari Kidz')

@section('content')
<div class="container my-5" style="max-width: 1320px;">
    <h1 class="text-center fw-bold text-primary mb-5">Pendaftaran Program Hikari Kidz</h1>

    {{-- Galeri Daycare --}}
    <h3 class="fw-bold text-success mb-3">Galeri Daycare</h3>
    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <a href="{{ asset('img/daycare1.jpg') }}" target="_blank">
                <img src="{{ asset('img/daycare1.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="daycare1">
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ asset('img/daycare2.jpg') }}" target="_blank">
                <img src="{{ asset('img/daycare2.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="daycare2">
            </a>
        </div>
    </div>

    {{-- Biaya Daycare --}}
    <h3 class="fw-bold text-success text-center mb-4">Informasi Biaya Daycare</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        @foreach($daycarePackages as $package)
        <div class="col">
            <div class="p-4 border border-success-subtle bg-white shadow-sm rounded-4 h-100">
                <h5 class="text-success fw-bold text-center mb-3">{{ $package->nama_paket }}</h5>
                <p class="text-muted small text-center">
                    @if($package->durasi_jam)Durasi: <span class="fw-bold">{{ $package->durasi_jam }}</span><br>@endif
                    @if($package->tipe)Tipe: <span class="fw-bold">{{ $package->tipe }}</span>@endif
                </p>
                <hr>
                <ul class="list-unstyled fs-6 mb-4">
                    <li class="d-flex justify-content-between"><span>Uang Pendaftaran</span><span class="fw-bold">Rp {{ number_format($package->u_pendaftaran ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Uang Pangkal</span><span class="fw-bold">Rp {{ number_format($package->u_pangkal ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Uang Kegiatan</span><span class="fw-bold">Rp {{ number_format($package->u_kegiatan ?? 0, 0, ',', '.') }}</span></li>
                    {{-- Tambahkan Uang SPP di sini --}}
                    <li class="d-flex justify-content-between"><span>Uang SPP</span><span class="fw-bold">Rp {{ number_format($package->u_spp ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Uang Makan</span><span class="fw-bold">Rp {{ number_format($package->u_makan ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Biaya Penitipan</span><span class="fw-bold">Rp {{ number_format($package->biaya_penitipan ?? 0, 0, ',', '.') }}</span></li>
                </ul>
                <div class="border-top pt-3 d-flex justify-content-between fw-bold fs-5">
                    <span>Total:</span>
                    <span class="text-success fs-4">Rp {{ number_format(($package->u_pendaftaran ?? 0)+($package->u_pangkal ?? 0)+($package->u_kegiatan ?? 0)+($package->u_spp ?? 0)+($package->u_makan ?? 0)+($package->biaya_penitipan ?? 0), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Galeri HKC --}}
    <h3 class="fw-bold text-success mb-3">Galeri Hikari Kidz Club</h3>
    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <a href="{{ asset('img/hkc1.jpg') }}" target="_blank">
                <img src="{{ asset('img/hkc1.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="hkc1">
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ asset('img/hkc2.jpg') }}" target="_blank">
                <img src="{{ asset('img/hkc2.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="hkc2">
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ asset('img/hkcw1.jpg') }}" target="_blank">
                <img src="{{ asset('img/hkcw1.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="hkcw1">
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ asset('img/hkcw2.jpg') }}" target="_blank">
                <img src="{{ asset('img/hkcw2.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="hkcw2">
            </a>
        </div>
    </div>

    {{-- Biaya HKC --}}
    <h3 class="fw-bold text-success text-center mb-4">Informasi Biaya HKC</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        @foreach($hkcPackages as $package)
        <div class="col">
            <div class="p-4 border border-success-subtle bg-white shadow-sm rounded-4 h-100">
                <h5 class="text-success fw-bold text-center mb-3">{{ $package->member }} ({{ $package->kelas }})</h5>
                <p class="text-muted small text-center">
                    @if($package->tipe)Tipe: <span class="fw-bold">{{ $package->tipe }}</span>@endif
                </p>
                <hr>
                <ul class="list-unstyled fs-6 mb-4">
                    <li class="d-flex justify-content-between"><span>Uang Pendaftaran</span><span class="fw-bold">Rp {{ number_format($package->u_pendaftaran ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Uang Perlengkapan</span><span class="fw-bold">Rp {{ number_format($package->u_perlengkapan ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Uang Sarana & Prasarana</span><span class="fw-bold">Rp {{ number_format($package->u_sarana ?? 0, 0, ',', '.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Uang SPP</span><span class="fw-bold">Rp {{ number_format($package->u_spp ?? 0, 0, ',', '.') }}</span></li>
                </ul>
                <div class="border-top pt-3 d-flex justify-content-between fw-bold fs-5">
                    <span>Total:</span>
                    <span class="text-success fs-4">Rp {{ number_format(($package->u_pendaftaran ?? 0)+($package->u_perlengkapan ?? 0)+($package->u_sarana ?? 0)+($package->u_spp ?? 0), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FORMULIR PENDAFTARAN --}}
    <section class="mt-5">
        <h3 class="fw-bold text-primary text-center mb-4">Formulir Pendaftaran Program</h3>
        <div class="row justify-content-center g-4">
            <div class="col-md-6 col-lg-5">
                <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                    <img src="{{ asset('img/daycare.jpg') }}" class="card-img-top" alt="Daycare" style="max-height: 250px; object-fit: cover;">
                    <div class="card-body p-4 text-center d-flex flex-column">
                        <h4 class="card-title mb-3 fw-bold text-dark">Daycare</h4>
                        <p class="text-muted small mb-2">Kategori: Penitipan Balita</p>
                        <p class="card-text text-secondary mb-4">Penyediaan penitipan untuk anak di bawah 5 tahun dengan fasilitas lengkap.</p>
                        <a href="{{ route('registerkidzdaycare.create') }}" class="btn btn-primary btn-lg mt-auto w-100">Daftar Daycare</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-5">
                <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                    <img src="{{ asset('img/hikarikidzclub.jpg') }}" class="card-img-top" alt="Hikari Kidz Club" style="max-height: 250px; object-fit: cover;">
                    <div class="card-body p-4 text-center d-flex flex-column">
                        <h4 class="card-title mb-3 fw-bold text-dark">Hikari Kidz Club</h4>
                        <p class="text-muted small mb-2">Kategori: Edukasi & Pengembangan</p>
                        <p class="card-text text-secondary mb-4">Kursus menyenangkan untuk mengembangkan keterampilan dan potensi anak.</p>
                        <a href="{{ route('registerkidzclub.create') }}" class="btn btn-primary btn-lg mt-auto w-100">Daftar HKC</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection