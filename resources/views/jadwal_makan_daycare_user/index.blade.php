@extends('layouts2.master')

@section('jadwal_makan_daycare_user_select','active')
@section('title', 'Jadwal Makan Daycare')

@php
  // Konversi bulan angka → teks Indonesia
  $bulanMap = [
      1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
      5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
      9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
  ];
@endphp

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- ========== CEK DATA ========== --}}
    @if($jadwalGrouped->isEmpty())
      <div class="alert alert-warning text-center" style="background:#FFA726;color:#fff;">
        Anda belum memiliki jadwal makan daycare. Ayo daftar sekarang!
      </div>
    @else

      {{-- ========== LOOP BULAN & PEKAN ========== --}}
      @foreach($jadwalGrouped as $bulan => $pekanGroup)
        @foreach($pekanGroup as $pekan => $rows)

        <div class="row mt-3">
          <div class="col-sm-12">
            <div class="p-3 bg-white border shadow-sm">

              <div class="card">
                {{--  Header tabel --}}
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                  <h5 class="mb-0"><b>Jadwal Makan Daycare</b></h5>
                  <span class="font-weight-bold" style="font-size:1.1rem;">
                    {{ $bulanMap[$bulan] ?? '-' }} - Pekan {{ $pekan }}
                  </span>
                </div>

                {{--  Body tabel --}}
                <div class="card-body p-4">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="bg-light">
                        <tr>
                          <th><b>Hari</b></th>
                          <th><b>Snack Pagi</b></th>
                          <th><b>Makan Siang</b></th>
                          <th><b>Snack Sore</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $r)
                        <tr>
                          <td>{{ $r->hari }}</td>
                          <td>{{ $r->snack_pagi }}</td>
                          <td>{{ $r->makan_siang }}</td>
                          <td>{{ $r->snack_sore }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>{{-- card-body --}}
              </div>{{-- card --}}

            </div>{{-- p-3 --}}
          </div>
        </div>

        @endforeach
      @endforeach

    @endif

  </div>
</section>
@endsection