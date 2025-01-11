    @extends('main')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @section('title', 'Grafik Penjualan')

    @section('content')
    <!--Content right-->
    <div class="col-sm-9 col-xs-12 content pt-3 pl-0">
        <h5 class="mb-0" ><strong>Grafik Penjualan</strong></h5>
        <span class="text-secondary">Dashboard <i class="fa fa-angle-right"></i> Grafik Penjualan</span>
        
        <div class="row mt-3">
            <div class="col-sm-12">
                <!--Datatable-->
                <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                    <h6 class="mb-2">Grafik Penjualan</h6>
                    
                    <div style="width: 100%;">
                        <canvas id="myChart"></canvas>
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
        
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar', // Mengubah jenis grafik menjadi bar
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total Penjualan',
                    data: {!! json_encode($data) !!},
                    backgroundColor: 'Blue',
                    borderColor: 'black',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    @endsection