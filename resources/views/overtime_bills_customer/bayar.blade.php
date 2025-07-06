@extends('layouts2.master') {{-- Sesuaikan dengan layout customer Anda --}}

@section('title', 'Formulir Pembayaran Denda Overtime')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white py-3">
            <h2 class="mb-0 text-center">Formulir Pembayaran Denda Overtime</h2>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Terjadi Kesalahan!</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Data Peserta & Tagihan Overtime --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tagihan Denda Overtime</h5>
                </div>
                <div class="card-body bg-white text-dark">
                    <hr class="my-2 border-dark-50">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama Peserta:</strong> <span class="fw-normal">{{ $peserta->full_name ?? ($peserta->name ?? '-') }}</span></p>
                            <p class="mb-0">
                                <strong>Program:</strong>
                                <span class="badge bg-success">
                                    @if($peserta instanceof \App\Models\RegistrationHikariKidzDaycare && $peserta->paket)
                                        {{ $peserta->paket->nama_paket }}
                                    @elseif($peserta instanceof \App\Models\RegistrationHikariKidzClub && $peserta->getPaketHkc())
                                        {{ $peserta->getPaketHkc()->member }} ({{ $peserta->getPaketHkc()->kelas }})
                                    @else
                                        {{ $tagihan->program }}
                                    @endif
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0"><strong>Periode Denda:</strong> <span class="fw-normal">{{ \Carbon\Carbon::create()->month($tagihan->bulan)->format('F') }} {{ $tagihan->tahun }}</span></p>
                            <p class="mb-0"><strong>Nominal Denda:</strong> <span class="fw-normal">Rp{{ number_format($tagihan->total_denda, 0, ',', '.') }}</span></p>
                            <p class="mb-0"><strong>Catatan:</strong> <span class="fw-normal">{{ $tagihan->notes }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('overtime.customer.proses_pembayaran', $tagihan->id) }}" enctype="multipart/form-data">
                @csrf
                {{-- Hidden fields to pass necessary IDs and types to the controller --}}
                <input type="hidden" name="registration_id" value="{{ $tagihan->registration_id }}">
                <input type="hidden" name="registration_type" value="{{ $tagihan->registration_type }}">
                <input type="hidden" name="total_bayar" id="total_bayar_hidden"> {{-- Hidden field for total amount --}}


                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3"><small>Berikut adalah komponen yang akan Anda bayarkan.</small></p>
                        <div class="row mb-3">
                            <div class="col-12">
                                <ul class="list-group list-group-flush">
                                    {{-- Denda Overtime is the only component here --}}
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 border-0">
                                        <span>
                                            <input class="form-check-input me-2" type="checkbox" checked disabled>
                                            <label class="form-check-label text-dark fw-bold">
                                                Denda Overtime Bulan {{ \Carbon\Carbon::create()->month($tagihan->bulan)->format('F') }} {{ $tagihan->tahun }}
                                                @if($tagihan->package_name)
                                                    ({{ $tagihan->package_name }})
                                                @endif
                                            </label>
                                        </span>
                                        <span class="text-dark fw-bold">
                                            Rp{{ number_format($tagihan->total_denda, 0, ',', '.') }}
                                        </span>
                                        {{-- We still need a hidden input for the component to be caught by the controller if needed --}}
                                        <input type="hidden" name="komponen[]" value="Denda Overtime Bulan {{ \Carbon\Carbon::create()->month($tagihan->bulan)->format('F') }} {{ $tagihan->tahun }} @if($tagihan->package_name) ({{ $tagihan->package_name }}) @endif">
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group mb-0 text-end">
                            <label for="jumlah" class="fw-bold fs-5 text-dark">Total Jumlah:</label>
                            <input type="text" id="jumlah" class="form-control d-inline-block text-end fw-bold text-dark border-0" style="width: auto; background-color: transparent; font-size: 1.5rem;" readonly>
                        </div>
                    </div>
                </div>

                {{-- Sisa form (Instruksi Pembayaran, Upload, Tombol Simpan) tetap sama --}}
                <div class="card mt-4 mb-4 shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Instruksi Pembayaran</h5>
                    </div>
                    <div class="card-body">
                           <p class="lead text-center mb-4">Silakan lakukan pembayaran sesuai Total Jumlah di atas melalui salah satu metode berikut:</p>
                         <div class="row">
                             <div class="col-md-6 mb-4">
                                 <div class="card h-100 border-success shadow-sm">
                                     <div class="card-body text-center">
                                         <h6 class="mt-0 mb-3 text-success"><i class="fas fa-bank me-2"></i>1. Transfer Bank (Bank Muamalat)</h6>
                                         <div class="d-flex flex-column align-items-center mb-3">
                                             <img src="{{ asset('img/muamalat_logo.png') }}" alt="Logo Bank Muamalat" style="height: 80px; margin-bottom: 15px;">
                                             <div class="text-start">
                                                 <p class="mb-1"><strong>Bank Muamalat Indonesia</strong></p>
                                                 <p class="mb-1">Nomor Rekening: <strong class="text-dark fs-5">1010202020</strong></p>
                                                 <p class="mb-0">Atas Nama: PT Hikari Bridge Indonesia</p>
                                             </div>
                                         </div>
                                         <p class="text-danger small mt-3">Mohon pastikan nama akun yang Anda tuju sudah benar untuk menghindari kesalahan.</p>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-6 mb-4">
                                 <div class="card h-100 border-success shadow-sm">
                                     <div class="card-body text-center">
                                         <h6 class="mt-0 mb-3 text-success"><i class="fas fa-qrcode me-2"></i>2. QRIS (Scan untuk Pembayaran Instan)</h6>
                                         <div class="text-center mb-3">
                                             {{-- Assuming you have this image in public/img/qris_image.png --}}
                                             <img src="{{ asset('img/qris_image.png') }}" alt="QRIS Pembayaran" class="img-fluid" style="max-width: 250px; border: 2px solid #28a745; padding: 10px; border-radius: 8px;">
                                         </div>
                                         <p class="mt-2 small text-muted">Scan QRIS ini dengan aplikasi pembayaran favorit Anda (Gopay, OVO, Dana, LinkAja, dll).</p>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Setelah melakukan pembayaran, mohon unggah bukti transfer/pembayaran Anda di sini. Format yang diizinkan: JPG, JPEG, PNG (maks. 2MB).</p>
                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label text-dark fw-bold">Pilih File Bukti Pembayaran:</label>
                            <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer" accept=".jpg,.jpeg,.png" required>
                            @error('bukti_transfer')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5"><i class="fas fa-check-circle me-2"></i>Simpan Pembayaran</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Custom CSS for select (tetap sama) --}}
<style>
    .custom-select-green.form-select {
        border-radius: 0.75rem; border-color: #28a745;
        box-shadow: 0 0.25rem 0.5rem rgba(40, 167, 69, 0.15);
        transition: all 0.2s ease-in-out;
        padding: 0.75rem 1.75rem 0.75rem 1rem;
        height: auto; background-position: right 0.75rem center; font-size: 1rem;
    }
    .custom-select-green.form-select:focus {
        border-color: #218838; box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.4); outline: 0;
    }
    .custom-select-green.form-select option { padding: 0.5rem 1rem; }
</style>

{{-- ======================================================= --}}
{{--         BAGIAN JAVASCRIPT YANG DIPERBAIKI             --}}
{{-- ======================================================= --}}
<script>
    const jumlahField = document.getElementById('jumlah');
    const totalBayarHidden = document.getElementById('total_bayar_hidden');
    const tagihanNominal = {{ $tagihan->total_denda }}; // Get the nominal directly from the passed $tagihan object

    function formatRupiah(angka) {
        let roundedAngka = Math.round(angka);
        let integerPart = roundedAngka.toString();
        let reverse = integerPart.split('').reverse().join('');
        let ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return 'Rp' + ribuan;
    }

    function hitungJumlah() {
        // For Overtime Bill, the total is simply the tagihan nominal
        let total = tagihanNominal;
        jumlahField.value = formatRupiah(total);
        totalBayarHidden.value = total; // Set the hidden field value
    }

    // Call hitungJumlah immediately when the page loads
    hitungJumlah();
</script>

@endsection