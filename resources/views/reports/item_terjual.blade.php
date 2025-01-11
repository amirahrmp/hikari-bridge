@extends('main')

@section('title', 'Laporan Penjualan Per Produk')

@section('content')
<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Laporan Penjualan Per Produk</strong></h5>
    <span class="text-secondary">Dashboard <i class="fa fa-angle-right"></i> Laporan <i class="fa fa-angle-right"></i> Laporan Penjualan Per Produk</span>
    
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom">
                            <h2>Laporan Penjualan Per Produk</h2>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('reports.item_terjual') }}" method="GET">
                                <div class="form-row align-items-center">
                                    <div class="col-auto">
                                        <label for="start_date" class="mr-2">Start Date:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-auto">
                                        <label for="end_date" class="mr-2">End Date:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control">
                                    </div>
                                    <div class="col-auto">
                                        <label></label><br>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <form action="{{ route('print_pdf') }}" method="GET">
                                <div class="form-row align-items-center">
                                    <div class="col-auto">
                                        <label for="start_date" class="mr-2">Start Date:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-auto">
                                        <label for="end_date" class="mr-2">End Date:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control">
                                    </div>
                                    <div class="col-auto">
                                        <label></label><br>
                                        <button type="submit" class="btn btn-danger">Cetak PDF</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <p>Periode : {{ $startDate }} - {{ $endDate }}</p>
                            <br>                            
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Terjual</th>
                                        <th>Total Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesReports as $report)
                                        <tr>
                                            <td>{{ $report['item']->nama_item }}</td>
                                            <td>{{ $report['total_sales'] }}</td>
                                            <td>{{ rupiah($report['total_revenue']) }}</td>
                                        </tr>
                                    @endforeach

                                    @if ($salesReports)
                                        <tr>
                                            <td colspan="2" class="text-left"><strong>Grand Total</strong></td>
                                            <td><strong>Rp. {{ number_format($grandTotalRevenue,2) }}</strong></td>
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
			format: 'yyyy-mm'
		});
    </script>
@endpush