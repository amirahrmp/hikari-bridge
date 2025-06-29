@extends('layouts2.master')

@section('title', 'Formulir Pembayaran Pendaftaran')

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

            {{-- Data Peserta & Paket --}}
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
                // --- PERSIAPAN VARIABEL ---
                $komponenList = [];
                if ($registration_type === 'Hikari Kidz Daycare') {
                    $komponenList = [
                        'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0, 'Uang Pangkal' => $paket->u_pangkal ?? 0,
                        'SPP Bulanan' => $paket->u_spp ?? 0, 'Uang Makan' => $paket->u_makan ?? 0,
                        "Uang Kegiatan" => $paket->u_kegiatan ?? 0,
                    ];
                } elseif ($registration_type === 'Hikari Kidz Club') {
                    $komponenList = [
                        'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0, 'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                        'Uang Sarana' => $paket->u_sarana ?? 0, 'SPP Bulanan' => $paket->u_spp ?? 0,
                    ];
                } elseif ($registration_type === 'Hikari Quran') {
                    $komponenList = [
                        'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0, 'Uang Modul' => $paket->u_modul ?? 0,
                        'SPP Bulanan' => $paket->u_spp ?? 0,
                    ];
                }

                $mandatoryComponents = [];
                if ($registration_type === 'Hikari Kidz Daycare') {
                    $mandatoryComponents = ['Uang Pendaftaran', 'SPP Bulanan', 'Uang Makan', 'Uang Kegiatan'];
                } elseif ($registration_type === 'Hikari Kidz Club') {
                    $mandatoryComponents = ['Uang Pendaftaran', 'Uang Perlengkapan', 'Uang Sarana', 'SPP Bulanan'];
                } else {
                    $mandatoryComponents = ['Uang Pendaftaran', 'Uang Modul', 'SPP Bulanan'];
                }
                
                $totalUangPangkal = $komponenList['Uang Pangkal'] ?? 0;
                $uangPangkalRemaining = $totalUangPangkal - $totalUangPangkalPaid;
            @endphp

            <form method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $registration_id }}">
                <input type="hidden" name="registration_type" value="{{ $registration_type }}">

                @if($originalCicilanPlan == 0 && $uangPangkalRemaining > 0)
                    <input type="hidden" name="uang_pangkal_nominal_full" value="{{ $uangPangkalRemaining }}">
                @endif

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Pilih Komponen Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3"><small>Komponen yang sudah lunas tidak akan ditampilkan di sini.</small></p>
                        <div class="row mb-3">
                            <div class="col-12">
                                <ul class="list-group list-group-flush">
                                    @foreach($mandatoryComponents as $comp)
                                        @if(isset($komponenList[$comp]) && $komponenList[$comp] > 0 && !in_array($comp, $paidComponents))
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
                                            <input type="hidden" name="komponen[]" value="{{ $comp }}">
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @if($registration_type === 'Hikari Kidz Daycare' && $totalUangPangkal > 0)
                            @if($uangPangkalRemaining > 0.01)
                                <hr class="my-4">
                                <h6 class="text-dark mb-3"><i class="fas fa-coins me-2"></i>Pembayaran Uang Pangkal</h6>
                                
                                <div class="alert alert-warning">
                                    Total Uang Pangkal Awal: Rp{{ number_format($totalUangPangkal, 0, ',', '.') }}<br>
                                    Sudah Dibayar: Rp{{ number_format($totalUangPangkalPaid, 0, ',', '.') }}<br>
                                    <strong class="fs-6">Sisa Tagihan: Rp{{ number_format($uangPangkalRemaining, 0, ',', '.') }}</strong>
                                </div>

                                @if($originalCicilanPlan == 0)
                                    <div class="mb-3">
                                        <label for="cicilan_uang_pangkal" class="form-label text-dark">Pilih Opsi Pembayaran Uang Pangkal</label>
                                        <select class="form-select custom-select-green shadow-sm" id="cicilan_uang_pangkal" name="cicilan_uang_pangkal">
                                            <option value="0">Tidak Membayar Uang Pangkal Saat Ini</option>
                                            <option value="1">1x Pembayaran (Langsung Lunas)</option>
                                            <option value="2">2x Pembayaran (Rp{{ number_format(ceil($uangPangkalRemaining / 2), 0, ',', '.') }} per cicilan)</option>
                                            <option value="3">3x Pembayaran (Rp{{ number_format(ceil($uangPangkalRemaining / 3), 0, ',', '.') }} per cicilan)</option>
                                        </select>
                                        <div class="form-text">Pilihan Anda di sini akan menentukan skema cicilan ke depannya.</div>
                                    </div>
                                @else
                                    @php
                                        $cicilanBerikutnya = $installmentsPaidCount + 1;
                                        $nominalPerCicilan = ceil($totalUangPangkal / $originalCicilanPlan);
                                    @endphp
                                    <div class="card border-info shadow-sm">
                                        <div class="card-header">
                                            <i class="fas fa-file-invoice-dollar me-2"></i>
                                            <strong>Tagihan Cicilan Lanjutan</strong>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text text-dark">
                                                Anda sedang dalam skema pembayaran <strong>{{ $originalCicilanPlan }}x cicilan</strong>. Berikut adalah tagihan untuk cicilan Anda berikutnya.
                                            </p>
                                            <div class="list-group">
                                                <label for="payNextInstallment" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="pay_next_installment" value="1" id="payNextInstallment" checked>
                                                        <span class="ms-2 fw-bold text-dark">
                                                            Bayar Cicilan ke-{{ $cicilanBerikutnya }} dari {{ $originalCicilanPlan }}
                                                        </span>
                                                    </div>
                                                    <span class="fs-5 fw-bold text-success">
                                                        Rp{{ number_format($nominalPerCicilan, 0, ',', '.') }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="next_installment_amount_val" value="{{ $nominalPerCicilan }}">
                                    <input type="hidden" name="next_installment_amount" value="{{ $nominalPerCicilan }}">
                                    <input type="hidden" name="cicilan_info_string" value="Uang Pangkal (Cicilan {{ $originalCicilanPlan }}x)">
                                @endif

                            @else
                                <hr class="my-4">
                                <div class="alert alert-success text-center">
                                    <i class="fas fa-check-circle me-2"></i> <strong>Pembayaran Uang Pangkal sudah lunas.</strong>
                                </div>
                            @endif
                        @endif
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

{{-- Custom CSS untuk select (tetap sama) --}}
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
{{--         BAGIAN JAVASCRIPT YANG DIPERBAIKI               --}}
{{-- ======================================================= --}}
<script>
    const jumlahField = document.getElementById('jumlah');
    const cicilanUangPangkalSelect = document.getElementById('cicilan_uang_pangkal');
    const payNextInstallmentCheckbox = document.getElementById('payNextInstallment');
    const nextInstallmentAmountField = document.getElementById('next_installment_amount_val');

    const nominalKomponen = @json($komponenList);
    const registrationType = "{{ $registration_type }}";
    const mandatoryComponentsJs = @json($mandatoryComponents);
    const paidComponentsJs = @json($paidComponents);
    const uangPangkalRemainingJs = {{ $uangPangkalRemaining ?? 0 }};
    const originalCicilanPlanJs = {{ $originalCicilanPlan ?? 0 }};

    function formatRupiah(angka) {
        let roundedAngka = Math.round(angka); 
        let integerPart = roundedAngka.toString();
        let reverse = integerPart.split('').reverse().join('');
        let ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return 'Rp' + ribuan;
    }

    function hitungJumlah() {
        let total = 0;
        mandatoryComponentsJs.forEach(komponen => {
            if (!paidComponentsJs.includes(komponen) && nominalKomponen[komponen] && nominalKomponen[komponen] > 0) {
                total += parseFloat(String(nominalKomponen[komponen]).replace(/[^\d]/g, '')) || 0;
            }
        });
        if (registrationType === 'Hikari Kidz Daycare') {
            if (originalCicilanPlanJs === 0 && cicilanUangPangkalSelect) {
                const cicilan = parseInt(cicilanUangPangkalSelect.value);
                if (cicilan > 0 && uangPangkalRemainingJs > 0) {
                    total += Math.round(uangPangkalRemainingJs / cicilan);
                }
            } 
            else if (originalCicilanPlanJs > 0 && payNextInstallmentCheckbox) {
                if (payNextInstallmentCheckbox.checked) {
                    total += parseFloat(nextInstallmentAmountField.value) || 0;
                }
            }
        }
        jumlahField.value = formatRupiah(total);
    }

    if (cicilanUangPangkalSelect) {
        cicilanUangPangkalSelect.addEventListener('change', hitungJumlah);
    }
    if (payNextInstallmentCheckbox) {
        payNextInstallmentCheckbox.addEventListener('change', hitungJumlah);
    }

    // --- PERUBAHAN DI SINI ---
    // Hapus event listener DOMContentLoaded dan panggil fungsinya langsung.
    // Ini memastikan total dihitung setelah semua elemen siap, tanpa perlu menunggu event.
    hitungJumlah();

</script>

@endsection