@extends('layouts2.master')

@section('jadwal_hkc_user_select','active')
@section('title', 'Jadwal Kegiatan HKC')

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
                      <h5>Daftar Jadwal Kegiatan Hikari Kidz Club</h5>
                    </div>
                    <div class="card-body p-4">
                      @if($jadwal_hkc_user->isEmpty())
                        <div class="alert alert-warning text-center" style="background-color: #FFA726; color: white;">
                          Anda belum memiliki Jadwal Kegiatan Hikari Kidz Club. Ayo daftar sekarang!
                        </div>
                      @else
                        <div class="table-responsive">
                          <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th><b>Kelas</b></th>
                                <th><b>Hari</b></th>
                                <th><b>Waktu Mulai</b></th>
                                <th><b>Waktu Selesai</b></th>
                                <th><b>Tema</b></th>
                                <th><b>Kegiatan</b></th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($jadwal_hkc_user as $p)
                                <tr>
                                  <td>{{ $p->kelas }}</td>
                                  <td>{{ $p->hari }}</td>
                                  <td>{{ $p->waktu_mulai }}</td>
                                  <td>{{ $p->waktu_selesai }}</td>
                                  <td>{{ $p->tema?->tema ?? '-' }}</td> 
                                  <td>{{ $p->kegiatan }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      @endif
                    </div> <!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col -->
              </div> <!-- row -->
            </div> <!-- container-fluid -->
          </div> <!-- content -->
        </div> <!-- button-container -->
      </div> <!-- col -->
    </div> <!-- row -->
  </div> <!-- container-fluid -->
</section>
@endsection
