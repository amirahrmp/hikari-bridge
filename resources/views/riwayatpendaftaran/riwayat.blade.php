@extends('layouts2.master')

@section('riwayat_select','active')
@section('title', 'Riwayat Pendaftaran')

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
                          <h5>Daftar Riwayat Pendaftaran</h5>
                        </div>
                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                  <th class="text-center"><b>No</b></th>
                                  <th><b>Nama Lengkap</b></th>
                                  <th><b>Program</b></th>
                                  <th><b>Tipe Kelas / Paket</b></th>
                                  <th><b>Tanggal Pendaftaran</b></th>
                                  {{-- Kolom Status dihapus sesuai permintaan --}}
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($registrations as $index => $registration)
                                {{-- ======================================================= --}}
                                {{-- AWAL BLOK LOGIKA BARU UNTUK MENDAPATKAN NAMA PAKET --}}
                                {{-- ======================================================= --}}
                                @php
                                    $programName = '-';
                                    $paketName = '-';

                                    if ($registration instanceof \App\Models\RegistrationHikariKidzClub) {
                                        $programName = 'Hikari Kidz Club';
                                        // Untuk Kidz Club, nama paket adalah gabungan member dan kelas
                                        $paketName = $registration->member . ' - ' . $registration->kelas;
                                    } elseif ($registration instanceof \App\Models\RegistrationHikariKidzDaycare) {
                                        $programName = 'Hikari Kidz Daycare';
                                        // Ambil dari relasi 'paket'
                                        $paketName = optional($registration->paket)->nama_paket ?? '-';
                                    //} elseif ($registration instanceof \App\Models\RegistrationHikariQuran) {
                                        //$programName = 'Hikari Quran';
                                        // Ambil dari relasi 'pakethq'
                                        //$paketName = $registration->kelas ?? '-'; // atau optional($registration->pakethq)->nama_paket jika ada
                                    //} elseif ($registration instanceof \App\Models\RegistrationProgramHkcw) {
                                        //$programName = 'Program Lain HKC Weekend';
                                        // Untuk HKCW, nama kegiatannya adalah nama paketnya  
                                        //$paketName = $registration->nama_kegiatan;
                                    }
                                @endphp
                                {{-- ======================================================= --}}
                                {{-- AKHIR BLOK LOGIKA BARU                                --}}
                                {{-- ======================================================= --}}

                                <tr>
                                  <td class="text-center">{{ $index + 1 }}</td>
                                  <td>{{ $registration->full_name }}</td>
                                  <td>{{ $programName }}</td>
                                  <td>{{ $paketName }}</td>
                                  <td>
                                    @if($registration->created_at)
                                      {{ $registration->created_at->format('d-m-Y') }}
                                    @else
                                      Tidak ada tanggal
                                    @endif
                                  </td>
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
@endsection