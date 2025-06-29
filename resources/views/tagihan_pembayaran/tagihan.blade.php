@extends('layouts2.master')

@section('tagihan_select','active')
@section('title', 'Tagihan Pembayaran')

@section('content')
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
                    <div class="card-header bg-success text-white">
                      <h5>Daftar Tagihan Pembayaran</h5>
                    </div>
                    <div class="card-body p-0">
                      <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">No</th>
                              <th class="text-center">Tanggal Pendaftaran</th>
                              <th class="text-center">Nama Lengkap</th>
                              <th class="text-center">Program</th>
                              <th class="text-center">Tipe Kelas / Paket</th>
                              <th class="text-center">Total Biaya</th>
                              <th class="text-center">Status Pembayaran</th> {{-- <<< KOLOM BARU --}}
                              <th class="text-center">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($registrations as $index => $registration)
                            
                            {{-- Blok PHP untuk menyiapkan semua variabel yang dibutuhkan per baris --}}
                            @php
                                // --- 1. LOGIKA MENGHITUNG TOTAL TAGIHAN ---
                                $totalTagihan = 0;
                                $programName = ''; // Untuk nama program
                                $paketName = '-'; // Untuk nama paket/kelas

                                if ($registration instanceof \App\Models\RegistrationHikariKidzClub) {
                                    $programName = 'Hikari Kidz Club';
                                    $paketName = $registration->member ?? '-';
                                    $paket = \App\Models\PaketHkc::whereRaw('LOWER(TRIM(member)) = ?', [strtolower(trim($registration->member))])
                                                                 ->whereRaw('LOWER(TRIM(kelas)) = ?', [strtolower(trim($registration->kelas))])
                                                                 ->first();
                                    if ($paket) {
                                        $totalTagihan = ($paket->u_pendaftaran ?? 0) + ($paket->u_perlengkapan ?? 0) + ($paket->u_sarana ?? 0) + ($paket->u_spp ?? 0);
                                    }
                                } elseif ($registration instanceof \App\Models\RegistrationHikariKidzDaycare && $registration->paket) {
                                    $programName = 'Hikari Kidz Daycare';
                                    $paketName = optional($registration->paket)->nama_paket ?? '-';
                                    $paket = $registration->paket;
                                    $totalTagihan = ($paket->u_pendaftaran ?? 0) + ($paket->u_pangkal ?? 0) + ($paket->u_kegiatan ?? 0) + ($paket->u_spp ?? 0) + ($paket->u_makan ?? 0);
                                } elseif ($registration instanceof \App\Models\RegistrationHikariQuran && $registration->pakethq) {
                                    $programName = 'Hikari Quran';
                                    $paketName = $registration->kelas ?? '-';
                                    $paket = $registration->pakethq;
                                    $totalTagihan = ($paket->u_pendaftaran ?? 0) + ($paket->u_modul ?? 0) + ($paket->u_spp ?? 0);
                                }

                                // --- 2. LOGIKA MENGECEK JUMLAH YANG SUDAH DIBAYAR ---
                                // Membuat kunci yang sama persis dengan yang di controller
                                $paymentKey = $programName . '-' . $registration->id;
                                // Mengambil total yang sudah dibayar dari variabel $paidAmounts. Jika tidak ada, maka 0.
                                $paidAmount = $paidAmounts[$paymentKey] ?? 0;
                                
                                // --- 3. MENENTUKAN STATUS AKHIR ---
                                // Status lunas jika total tagihan ada ( > 0) dan yang dibayar >= total tagihan
                                $isFullyPaid = ($totalTagihan > 0 && $paidAmount >= $totalTagihan);
                            @endphp

                            <tr>
                              <td class="text-center">{{ $index + 1 }}</td>
                              <td class="text-center">{{ optional($registration->created_at)->format('d-m-Y') }}</td>
                              <td>{{ $registration->full_name }}</td>
                              <td>{{ $programName }}</td>
                              <td>{{ $paketName }}</td>

                              {{-- Total Biaya --}}
                              <td class="text-center">
                                  @if($totalTagihan > 0)
                                    Rp{{ number_format($totalTagihan, 0, ',', '.') }}
                                  @else
                                    -
                                  @endif
                              </td>

                              {{-- Status Pembayaran (KOLOM BARU) --}}
                              <td class="text-center align-middle">
                                @if($isFullyPaid)
                                    {{-- Jika sudah lunas --}}
                                    <span class="badge bg-success px-2 py-1">
                                        <i class="fas fa-check-circle me-1"></i> Sudah Lunas
                                    </span>
                                @elseif($paidAmount > 0)
                                    {{-- Jika sudah bayar sebagian (ada cicilan) --}}
                                    <span class="badge bg-warning text-dark px-2 py-1">
                                        Kurang: Rp{{ number_format($totalTagihan - $paidAmount, 0, ',', '.') }}
                                    </span>
                                @else
                                    {{-- Jika belum bayar sama sekali --}}
                                    <span class="badge bg-danger px-2 py-1">
                                        <i class="fas fa-times-circle me-1"></i> Belum Bayar
                                    </span>
                                @endif
                              </td>

                              {{-- Aksi (Tombol ditampilkan kondisional) --}}
                              <td class="text-center">
                                @if(!$isFullyPaid)
                                   {{-- Tombol hanya muncul jika belum lunas --}}
                                   <a href="{{ route('payment.create', ['registration_id' => $registration->id, 'registration_type' => $programName]) }}" class="btn btn-success btn-sm">
                                     Bayar Sekarang
                                   </a>
                                @else
                                    {{-- Tampilkan strip jika sudah lunas --}}
                                    -
                                @endif
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div> </div> </div> </div> </div> </div> </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection