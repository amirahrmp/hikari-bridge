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
                              <th class="text-center">Tipe Kelas</th>
                              <th class="text-center">Nama Paket</th>
                              <th class="text-center">Total Biaya</th>
                              <th class="text-center">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($registrations as $index => $registration)
                            <tr>
                              <td class="text-center">{{ $index + 1 }}</td>
                              <td class="text-center">{{ optional($registration->created_at)->format('d-m-Y') }}</td>
                              <td>{{ $registration->full_name }}</td>

                              {{-- Program --}}
                              <td>
                                @if($registration instanceof \App\Models\RegistrationHikariKidzClub)
                                  Hikari Kidz Club
                                @elseif($registration instanceof \App\Models\RegistrationHikariKidzDaycare)
                                  Hikari Kidz Daycare
                                @elseif($registration instanceof \App\Models\RegistrationHikariQuran)
                                  Hikari Quran
                                @endif
                              </td>

                              {{-- Tipe Kelas --}}
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
  @if($registration instanceof \App\Models\RegistrationHikariKidzClub)
    {{ $registration->member ?? '-' }}
  @elseif($registration instanceof \App\Models\RegistrationHikariKidzDaycare)
    {{ optional($registration->paket)->nama_paket ?? '-' }}
  @elseif($registration instanceof \App\Models\RegistrationHikariQuran)
    {{ $registration->kelas ?? '-' }}
  @else
    -
  @endif
</td>

@php
    $total = 0;
    if ($registration instanceof \App\Models\RegistrationHikariKidzClub) {
        $paket = \App\Models\PaketHkc::whereRaw('LOWER(TRIM(member)) = ?', [strtolower(trim($registration->member))])
                                     ->whereRaw('LOWER(TRIM(kelas)) = ?', [strtolower(trim($registration->kelas))])
                                     ->first();
        if ($paket) {
            $total = ($paket->u_pendaftaran ?? 0) +
                     ($paket->u_perlengkapan ?? 0) +
                     ($paket->u_sarana ?? 0) +
                     ($paket->u_spp ?? 0);
        }
    } elseif ($registration instanceof \App\Models\RegistrationHikariKidzDaycare && $registration->paket) {
        $paket = $registration->paket;
        $total = ($paket->u_pendaftaran ?? 0) +
                 ($paket->u_pangkal ?? 0) +
                 ($paket->u_kegiatan ?? 0) +
                 ($paket->u_spp ?? 0) +
                 ($paket->u_makan ?? 0);
    } elseif ($registration instanceof \App\Models\RegistrationHikariQuran && $registration->pakethq) {
        $paket = $registration->pakethq;
        $total = ($paket->u_pendaftaran ?? 0) +
                 ($paket->u_modul ?? 0) +
                 ($paket->u_spp ?? 0);
    }
@endphp
<td class="text-center">
    @if($total > 0)
      Rp{{ number_format($total, 0, ',', '.') }}
    @else
      -
    @endif
</td>

                              {{-- Aksi --}}
                              <td class="text-center">
                               <a
  href="{{ route('payment.create', [
      'registration_id' => $registration->id,
      'registration_type' =>
          $registration instanceof \App\Models\RegistrationHikariKidzClub ? 'Hikari Kidz Club' :
          ($registration instanceof \App\Models\RegistrationHikariQuran ? 'Hikari Quran' :
          ($registration instanceof \App\Models\RegistrationHikariKidzDaycare ? 'Hikari Kidz Daycare' : '')
      )
  ]) }}"
  class="btn btn-success"
>
  Bayar Sekarang
</a>

                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div> <!-- /.table-responsive -->
                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
              </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
