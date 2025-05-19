@extends('layouts2.master')

@section('riwayat_select','active')
@section('title', 'Riwayat Pendaftaran')

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
                          <h5>Daftar Riwayat Pendaftaran</h5>
                        </div>
                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                  <th><b>No</b></th>
                                  <!-- <th><b>ID Pendaftaran</b></th> -->
                                  <th><b>Nama Lengkap</b></th>
                                  <th><b>Program</b></th>
                                  <th><b>Tipe Kelas</b></th>
                                  <th><b>Tanggal Pendaftaran</b></th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($registrations as $index => $registration)
                                <tr>
                                  <td>{{ $index + 1 }}</td>
                                  <!-- <td>{{ $registration->id }}</td> -->
                                  <td>{{ $registration->full_name }}</td>
                                  <td>
                                    @if($registration instanceof \App\Models\RegistrationHikariKidzClub)
                                      Hikari Kidz Club
                                    @elseif($registration instanceof \App\Models\RegistrationHikariKidzDaycare)
                                      Hikari Kidz Daycare
                                    @elseif($registration instanceof \App\Models\RegistrationHikariQuran)
                                      Hikari Quran
                                    @elseif($registration instanceof \App\Models\RegistrationProgramHkcw)
                                      Program Lain HKC Weekend
                                    @endif
                                  </td>
                                  <td>
                                    @if($registration instanceof \App\Models\RegistrationHikariKidzClub)
                                      {{ $registration->kelas }}
                                    @elseif($registration instanceof \App\Models\RegistrationHikariKidzDaycare)
                                      {{ $registration->age_group }}
                                    @elseif($registration instanceof \App\Models\RegistrationHikariQuran)
                                      {{ $registration->kelas }}
                                    @elseif($registration instanceof \App\Models\RegistrationProgramHkcw)
                                      {{ $registration->nama_kegiatan }}
                                    @endif
                                  </td>
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
</div>
@endsection