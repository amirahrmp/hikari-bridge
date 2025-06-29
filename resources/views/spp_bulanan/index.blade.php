    @extends('layouts2.master')
    @section('title', 'Tagihan SPP Bulanan')

    @section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">Daftar Tagihan SPP Anda</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th>Nama Anak</th>
                                            <th>Program</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tagihanSpp as $tagihan)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::create()->month($tagihan->bulan)->format('F') }} {{ $tagihan->tahun }}</td>
                                            <td>{{ $tagihan->nama_lengkap }}</td>
                                            <td>{{ $tagihan->program }}</td>
                                            <td>Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                                            <td>
                                                @if($tagihan->status == 'lunas')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($tagihan->status == 'menunggu_verifikasi')
                                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Bayar</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($tagihan->status == 'belum_bayar')
                                                    <a href="{{ route('spp.bulanan.bayar', $tagihan->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada tagihan SPP untuk Anda.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
    