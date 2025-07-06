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
                              <th class="text-center">Status Pembayaran</th>
                              <th class="text-center">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($formattedRegistrations as $index => $data)
                            <tr>
                              <td class="text-center">{{ $index + 1 }}</td>
                              <td class="text-center">{{ optional($data->created_at)->format('d-m-Y') }}</td>
                              <td>{{ $data->full_name }}</td>
                              <td>{{ $data->program_name_display }}</td>
                              <td>{{ $data->paket_name }}</td>

                              {{-- Total Biaya --}}
                              <td class="text-center">
                                  @if($data->total_tagihan > 0)
                                    Rp{{ number_format($data->total_tagihan, 0, ',', '.') }}
                                  @else
                                    -
                                  @endif
                              </td>

                              {{-- Status Pembayaran --}}
                              <td class="text-center align-middle">
                                @if($data->is_fully_paid)
                                    {{-- Jika sudah lunas --}}
                                    <span class="badge bg-success px-2 py-1">
                                        <i class="fas fa-check-circle me-1"></i> Sudah Lunas
                                    </span>
                                @elseif($data->paid_amount > 0)
                                    {{-- Jika sudah bayar sebagian (ada cicilan) --}}
                                    <span class="badge bg-warning text-dark px-2 py-1">
                                        Kurang: Rp{{ number_format($data->total_tagihan - $data->paid_amount, 0, ',', '.') }}
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
                                @if(!$data->is_fully_paid)
                                   {{-- Tombol hanya muncul jika belum lunas --}}
                                   <a href="{{ route('payment.create', ['registration_id' => $data->id, 'registration_type' => $data->program_name_display]) }}" class="btn btn-success btn-sm">
                                     Bayar Sekarang
                                   </a>
                                @else
                                    {{-- Tampilkan strip jika sudah lunas --}}
                                    -
                                @endif
                              </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pendaftaran.</td>
                            </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div> </div> </div> </div> </div> </div> </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection