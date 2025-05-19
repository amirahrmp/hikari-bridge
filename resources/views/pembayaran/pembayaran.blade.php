@extends('layouts2.master')

@section('pembayaran_select','active')
@section('title', 'Pembayaran')

@section('content')
<!-- Main Content -->
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
                              <th><b>No</b></th>
                              <th><b>Tanggal Pendaftaran</b></th>
                              <th><b>Nama Lengkap</b></th>
                              <th><b>Program</b></th>
                              <th><b>Tipe Kelas</b></th>
                              <th><b>Nama Paket</b></th>
                              <th><b>Total Biaya</b></th>
                              <th><b>Aksi</b></th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($registrations as $index => $registration)
                            <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ optional($registration->created_at)->format('d-m-Y') }}</td>
                              <td>{{ $registration->full_name }}</td>
                              <td>
                                @if($registration instanceof \App\Models\RegistrationHikariKidzClub)
                                  Hikari Kidz Club
                                @elseif($registration instanceof \App\Models\RegistrationHikariKidzDaycare)
                                  Hikari Kidz Daycare
                                @elseif($registration instanceof \App\Models\RegistrationHikariQuran)
                                  Hikari Quran
                                @endif
                              </td>
                              <td>
                                @if($registration instanceof \App\Models\RegistrationHikariKidzClub)
                                  {{ $registration->kelas }}
                                @elseif($registration instanceof \App\Models\RegistrationHikariKidzDaycare)
                                  {{ $registration->age_group }}
                                @elseif($registration instanceof \App\Models\RegistrationHikariQuran)
                                  {{ $registration->kelas }}
                                @endif
                              </td>

                              {{-- Nama Paket --}}
                              <td>
                                @php
                                  $namaPaket = '-';

                                  if ($registration instanceof \App\Models\RegistrationHikariKidzDaycare && $registration->paket) {
                                      $namaPaket = $registration->paket->nama_paket ?? '-';
                                  } elseif ($registration instanceof \App\Models\RegistrationHikariKidzClub) {
                                      $paketHKC = \App\Models\PaketHkc::where('member', $registration->member)
                                                                     ->where('kelas', $registration->kelas)
                                                                     ->first();
                                      $namaPaket = $paketHKC->member ?? '-';
                                  } elseif ($registration instanceof \App\Models\RegistrationHikariQuran && $registration->pakethq) {
                                      $namaPaket = '-';
                                  }
                                @endphp
                                {{ $namaPaket }}
                              </td>

                              {{-- Total Biaya --}}
                              <td>
                                @php
                                  $total = 0;

                                  if ($registration instanceof \App\Models\RegistrationHikariKidzDaycare) {
                                      $paket = $registration->paket;
                                      if ($paket) {
                                          $total = ($paket->u_pendaftaran ?? 0) +
                                                   ($paket->u_pangkal ?? 0) +
                                                   ($paket->u_kegiatan ?? 0) +
                                                   ($paket->u_spp ?? 0) +
                                                   ($paket->u_makan ?? 0);
                                      }
                                  } elseif ($registration instanceof \App\Models\RegistrationHikariKidzClub) {
                                      $paketHKC = \App\Models\PaketHkc::where('member', $registration->member)
                                                                     ->where('kelas', $registration->kelas)
                                                                     ->first();
                                      if ($paketHKC) {
                                          $total = ($paketHKC->u_pendaftaran ?? 0) +
                                                   ($paketHKC->u_perlengkapan ?? 0) +
                                                   ($paketHKC->u_sarana ?? 0) +
                                                   ($paketHKC->u_spp ?? 0);
                                      }
                                  }  elseif ($registration instanceof \App\Models\RegistrationHikariQuran && $registration->pakethq) {
                                      $pakethq = $registration->pakethq;

                                      if ($pakethq) {
                                          $total = ($pakethq->u_pendaftaran ?? 0) +
                                                  ($pakethq->u_modul ?? 0) +
                                                  ($pakethq->u_spp ?? 0);
                                      }
                                  }
                                  
                                @endphp

                                @if($total > 0)
                                  Rp{{ number_format($total, 0, ',', '.') }}
                                @else
                                  -
                                @endif
                              </td>
                              <td><a href="{{ route('pembayaran.create') }}" class="btn btn-primary mt-3">Bayar Sekarang</a></td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
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
