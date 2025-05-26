@extends('layouts2.master')

@section('jadwal_makan_daycare_user_select','active')
@section('title', 'Jadwal Makan Daycare')

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
                          <h5>Daftar Jadwal Makan Daycare</h5>
                        </div>
                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                    <th><b>Hari</b></th>
                                    <th><b>Snack Pagi</b></th>
                                    <th><b>Makan Siang</b></th>
                                    <th><b>Snack Sore</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($jadwal_makan_daycare_user as $p)
                                <tr>
                                    <td>{{ $p->hari }}</td>
                                    <td>{{ $p->snack_pagi }}</td>
                                    <td>{{ $p->makan_siang }}</td>
                                    <td>{{ $p->snack_sore }}</td>
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