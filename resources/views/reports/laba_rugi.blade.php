@extends('main')

@section('title', 'Laporan Laba Rugi')

@section('content')
<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Laporan Laba Rugi</strong></h5>
    <span class="text-secondary">Dashboard <i class="fa fa-angle-right"></i> Laporan Laba Rugi</span>
    
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom">
                            <h2>Laporan Laba Rugi</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reports.laba_rugi') }}" method="get" class="mb-5">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group mb-2">
                                            <input type="text" class="form-control datepicker" readonly="" value="{{ !empty(request()->input('start')) ? request()->input('start') : '' }}" name="start" placeholder="From">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group mx-sm-3 mb-2">
                                            <input type="text" class="form-control datepicker" readonly="" value="{{ !empty(request()->input('end')) ? request()->input('end') : '' }}" name="end" placeholder="To">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group mx-sm-3 mb-2">
                                            <select name="export" class="form-control">
                                                <option value="pdf">PDF</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group mx-sm-3 mb-2">
                                            <button type="submit" class="btn btn-primary btn-default">Cetak</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Penjualan</th>
                                        <th>Pembelian Bahan Baku</th>
                                        <th>Pengeluaran</th>
                                        <th>Laba/Rugi</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($reports as $report)
                                            <tr>    
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $report['date'] }}</td>
                                                <td>{{ rupiah($report['revenue']) }}</td>
                                                <td>{{ rupiah($report['purchase']) }}</td>
                                                <td>{{ rupiah($report['pengeluaran']) }}</td>
                                                <td>{{ rupiah($report['laba_rugi']) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">No records found</td>
                                            </tr>
                                        @endforelse

                                        @if ($reports)
                                            <tr>
                                                <td colspan="5" class="text-left"><strong>Total Laba/Rugi Bersih</strong></td>
                                                <td><strong>Rp. {{ number_format($total_laba_rugi,2) }}</strong></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Datatable-->

        </div>
    </div>

    <!--Footer-->
    <div class="row mt-5 mb-4 footer">
        <div class="col-sm-8">
            <span>&copy; All rights reserved 2019 designed by <a class="text-info" href="#">A-Fusion</a></span><br>
            <span>&copy; 2024 Modified by <a class="text-info" href="#">Kamal Sa'danah</a></span>
        </div>
        <div class="col-sm-4 text-right">
            <a href="#" class="ml-2">Contact Us</a>
            <a href="#" class="ml-2">Support</a>
        </div>
    </div>
    <!--Footer-->

</div>
    
@endsection

@push('script-alt')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.datepicker').datepicker({
			format: 'yyyy-mm-dd'
		});
    </script>
@endpush