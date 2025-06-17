@extends('layouts2.master')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white py-3">
            <h2 class="mb-0 text-center">Formulir Pembayaran Pendaftaran</h2>
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

            {{-- Data Peserta & Paket - HEADER HIJAU, BODY PUTIH/HITAM --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pendaftaran</h5>
                </div>
                <div class="card-body bg-white text-dark">
                    <hr class="my-2 border-dark-50">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama Peserta:</strong> <span class="fw-normal">{{ $peserta->full_name ?? ($peserta->name ?? '-') }}</span></p>
                            <p class="mb-0"><strong>Jenis Pendaftaran:</strong> <span class="badge bg-success">{{ $registration_type }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0"><strong>Nama Paket:</strong> <span class="fw-normal">{{ $paket->nama_paket ?? $paket->member ?? $paket->kelas ?? '-' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $komponenList = [];
                // Pastikan variabel $paket tersedia dan bukan null
                if ($registration_type === 'Hikari Kidz Daycare') {
                    $komponenList = [
                        'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                        'Uang Pangkal' => $paket->u_pangkal ?? 0,
                        'SPP Bulanan' => $paket->u_spp ?? 0,
                        'Uang Makan' => $paket->u_makan ?? 0,
                        "Uang Kegiatan" => $paket->u_kegiatan ?? 0,
                    ];
                } elseif ($registration_type === 'Hikari Kidz Club') {
                    $komponenList = [
                        'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                        'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                        'Uang Sarana' => $paket->u_sarana ?? 0,
                        'SPP Bulanan' => $paket->u_spp ?? 0,
                    ];
                } elseif ($registration_type === 'Hikari Quran') {
                    $komponenList = [
                        'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                        'Uang Modul' => $paket->u_modul ?? 0,
                        'SPP Bulanan' => $paket->u_spp ?? 0,
                    ];
                }

                // Tentukan komponen WAJIB berdasarkan registration_type
                $mandatoryComponents = [];
                if ($registration_type === 'Hikari Kidz Daycare') {
                    $mandatoryComponents = ['Uang Pendaftaran', 'SPP Bulanan', 'Uang Makan', 'Uang Kegiatan'];
                } elseif ($registration_type === 'Hikari Kidz Club') {
                    $mandatoryComponents = ['Uang Pendaftaran', 'Uang Perlengkapan', 'Uang Sarana', 'SPP Bulanan'];
                } else { // Untuk Hikari Quran atau tipe lain
                    $mandatoryComponents = ['Uang Pendaftaran', 'Uang Modul', 'SPP Bulanan'];
                }
            @endphp

            <form method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $registration_id }}">
                <input type="hidden" name="registration_type" value="{{ $registration_type }}">

                {{-- Input hidden untuk menyimpan nominal Uang Pangkal total (bukan per cicilan)
                     Ini dibutuhkan di controller untuk menghitung jumlah per cicilan saat menyimpan komponen. --}}
                @if(isset($komponenList['Uang Pangkal']) && $komponenList['Uang Pangkal'] > 0)
                    <input type="hidden" name="uang_pangkal_nominal_full" value="{{ $komponenList['Uang Pangkal'] }}">
                @endif


                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Pilih Komponen Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3"><small>Pembayaran berikut akan otomatis ditambahkan:</small></p>
                        <div class="row mb-3">
                            <div class="col-12">
                                <ul class="list-group list-group-flush">
                                    @foreach($mandatoryComponents as $comp)
                                        {{-- MODIFIKASI DIMULAI DI SINI --}}
                                        {{-- HANYA TAMPILKAN komponen wajib jika nominalnya > 0 --}}
                                        @if(isset($komponenList[$comp]) && $komponenList[$comp] > 0)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 border-0">
                                            <span>
                                                <input class="form-check-input me-2" type="checkbox" checked disabled>
                                                <label class="form-check-label text-dark fw-bold">
                                                    {{ $comp }}
                                                </label>
                                            </span>
                                            <span class="text-dark fw-bold">
                                                Rp{{ number_format($komponenList[$comp], 0, ',', '.') }}
                                            </span>
                                            {{-- Tambahkan input hidden HANYA untuk komponen yang ditampilkan (nominal > 0) --}}
                                            <input type="hidden" name="komponen[]" value="{{ $comp }}">
                                        </li>
                                        @endif
                                        {{-- MODIFIKASI BERAKHIR DI SINI --}}
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Bagian terpisah untuk Uang Pangkal dan Cicilan --}}
                        {{-- HANYA ditampilkan jika programnya 'Hikari Kidz Daycare' DAN uang pangkalnya ada dan > 0 --}}
                         @if($registration_type === 'Hikari Kidz Daycare' && isset($komponenList['Uang Pangkal']) && $komponenList['Uang Pangkal'] > 0)
                            <hr class="my-4">
                            <h6 class="text-dark mb-3"><i class="fas fa-coins me-2"></i>Pembayaran Uang Pangkal (Cicilan)</h6>
                            <div class="mb-3">
                                <label for="cicilan_uang_pangkal" class="form-label text-dark">Pilih Opsi Pembayaran Uang Pangkal (Total Nominal: Rp{{ number_format($komponenList['Uang Pangkal'], 0, ',', '.') }})</label>
                                <select class="form-select custom-select-green shadow-sm" id="cicilan_uang_pangkal" name="cicilan_uang_pangkal">
                                    <option value="0">Tidak Membayar Uang Pangkal Saat Ini</option>
                                    <option value="1">1x Pembayaran (Langsung Lunas)</option>
                                    <option value="2">2x Pembayaran (Rp{{ number_format(ceil($komponenList['Uang Pangkal'] / 2), 0, ',', '.') }} per cicilan)</option>
                                    <option value="3">3x Pembayaran (Rp{{ number_format(ceil($komponenList['Uang Pangkal'] / 3), 0, ',', '.') }} per cicilan)</option>
                                </select>
                                <div id="cicilanHelp" class="form-text">Jumlah yang akan dibayarkan untuk uang pangkal pada pembayaran ini akan ditambahkan ke total.</div>
                            </div>
                        @endif
                        <hr>
                        <div class="form-group mb-0 text-end">
                            <label for="jumlah" class="fw-bold fs-5 text-dark">Total Jumlah:</label>
                            <input type="text" id="jumlah" class="form-control d-inline-block text-end fw-bold text-dark border-0" style="width: auto; background-color: transparent; font-size: 1.5rem;" readonly>
                        </div>
                    </div>
                </div>

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

{{-- Custom CSS untuk mempercantik select --}}
<style>
    /* Mengatur ulang gaya default Bootstrap form-select untuk lebih bulat dan halus */
    .custom-select-green.form-select {
        border-radius: 0.75rem; /* Sudut lebih bulat */
        border-color: #28a745; /* Border hijau */
        box-shadow: 0 0.25rem 0.5rem rgba(40, 167, 69, 0.15); /* Shadow lebih lembut dan sedikit lebih besar */
        transition: all 0.2s ease-in-out;
        padding: 0.75rem 1.75rem 0.75rem 1rem; /* Padding disesuaikan */
        height: auto; /* Biarkan tinggi menyesuaikan konten */
        background-position: right 0.75rem center; /* Sesuaikan posisi arrow */
        font-size: 1rem; /* Ukuran font standar */
    }
    .custom-select-green.form-select:focus {
        border-color: #218838; /* Border lebih gelap saat fokus */
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.4); /* Glow hijau lebih menonjol saat fokus */
        outline: 0; /* Hapus outline default browser */
    }
    /* Untuk memastikan opsi dropdown mengikuti gaya yang sama jika browser mendukung */
    .custom-select-green.form-select option {
        padding: 0.5rem 1rem; /* Padding untuk setiap opsi */
    }
</style>

<script>
    const jumlahField = document.getElementById('jumlah');
    const nominalKomponen = @json($komponenList);
    const registrationType = "{{ $registration_type }}";

    const mandatoryComponentsJs = @json($mandatoryComponents);

    // Dapatkan elemen select cicilan uang pangkal jika ada
    const cicilanUangPangkalSelect = document.getElementById('cicilan_uang_pangkal');

    function formatRupiah(angka) {
        // Pembulatan ke bilangan bulat terdekat sebelum diformat
        let roundedAngka = Math.round(angka); 
        
        // Pastikan angka adalah string sebelum dioperasikan
        let integerPart = roundedAngka.toString();

        let reverse = integerPart.split('').reverse().join('');
        let ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        
        return 'Rp' + ribuan;
    }

    function hitungJumlah() {
        let total = 0;

        // 1. Tambahkan komponen WAJIB yang nominalnya > 0
        mandatoryComponentsJs.forEach(komponen => {
            // MODIFIKASI: Hanya tambahkan ke total jika nominalnya > 0
            if (nominalKomponen[komponen] && nominalKomponen[komponen] > 0) {
                total += parseFloat(String(nominalKomponen[komponen]).replace(/[^\d]/g, '')) || 0;
            }
        });

        // 2. Handle cicilan uang pangkal HANYA jika itu Hikari Kidz Daycare dan elemennya ada
        if (registrationType === 'Hikari Kidz Daycare' && cicilanUangPangkalSelect) {
            const cicilan = parseInt(cicilanUangPangkalSelect.value);
            // Hanya tambahkan uang pangkal jika opsi selain "Tidak Membayar Uang Pangkal Saat Ini" dipilih
            if (cicilan > 0 && nominalKomponen['Uang Pangkal']) {
                let uangPangkalNominal = parseFloat(String(nominalKomponen['Uang Pangkal']).replace(/[^\d]/g, '')) || 0;
                total += Math.round(uangPangkalNominal / cicilan); // Bulatkan hasil cicilan
            }
        }

        jumlahField.value = formatRupiah(total);
    }

    // Tambahkan event listener untuk cicilan uang pangkal HANYA jika elemennya ada
    if (cicilanUangPangkalSelect) {
        cicilanUangPangkalSelect.addEventListener('change', hitungJumlah);
    }

    // Selalu panggil hitungJumlah saat DOMContentLoaded untuk inisialisasi total
    document.addEventListener('DOMContentLoaded', hitungJumlah);
    
</script>

{{-- Pastikan Anda sudah menyertakan Font Awesome untuk ikon. Jika belum, tambahkan di layout master Anda: --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endsection