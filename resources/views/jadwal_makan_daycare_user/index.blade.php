@extends('layouts2.master')

@section('jadwal_makan_daycare_user_select','active')
@section('title', 'Jadwal Makan Daycare')

@php
  $bulanMap = [
      1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
      5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
      9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
  ];
@endphp

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-sm-12">
        <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Jadwal Makan Daycare</h5>
                  </div>
                  <div class="card-body p-4">

                    {{-- ALERT BELUM DAFTAR --}}
                    @if(isset($belumDaftar) && $belumDaftar)
                      <div class="alert text-white text-center" style="background-color: #66BB6A;">
                        Anda belum daftar daycare. Silakan daftar terlebih dahulu untuk melihat jadwal makan.
                      </div>

                    {{-- ALERT JADWAL KOSONG --}}
                    @elseif($jadwalGrouped->isEmpty())
                      <div class="alert text-white text-center" style="background-color: #A5D6A7;">
                        Anda belum memiliki jadwal makan daycare. Ayo daftar sekarang!
                      </div>

                    {{-- TAMPILKAN TABEL --}}
                    @else
                      @foreach($jadwalGrouped as $bulan => $pekanGroup)
                        @foreach($pekanGroup as $pekan => $rows)
                          <div class="mb-4">
                            <div class="card border">
                              <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><b>{{ $bulanMap[$bulan] ?? '-' }} - Pekan {{ $pekan }}</b></h6>
                              </div>
                              <div class="card-body p-3">
                                <div class="table-responsive">
                                  <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                      <tr>
                                        <th>Hari</th>
                                        <th>Snack Pagi</th>
                                        <th>Makan Siang</th>
                                        <th>Snack Sore</th>
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
                              </div>
                            </div>
                          </div>
                        @endforeach
                      @endforeach
                    @endif

                  </div> <!-- card-body -->
                </div> <!-- card -->
              </div> <!-- col -->
            </div> <!-- row -->
          </div> <!-- container-fluid -->
        </div> <!-- button-container -->
      </div> <!-- col -->
    </div> <!-- row -->
  </div> <!-- container-fluid -->
</section>
@endsection
