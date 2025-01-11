@extends('main')

@section('title', 'Potongan Gaji')

@section('content')
<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Potongan Gaji</strong></h5>
    <span class="text-secondary">Dashboard <i class="fa fa-angle-right"></i> Potongan Gaji</span>
    
    <div class="row mt-3">
        <div class="col-sm-12">
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                
                
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="col-12 d-flex justify-content-between">
                                    <h6 class="mb-2">Potongan Gaji</h6>
            
                                <a href="{{ route('potongan-gaji.index') }}" class="btn btn-light"> <i class="fa fa-arrow-left"></i> </a>
                            </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
            
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body p-3">
            
                                        <form action="{{ route('potongan-gaji.update', $potongan_gaji->id) }}" method="POST">
                                            @csrf 
                                            @method('put')
                                            <div style="gap: .5rem;flex-wrap: wrap;" class="form-group justify-content-between d-flex align-items-center mb-5">
                                                <label class="m-0" for="jenis_potongan">Jenis Potongan</label>
                                                <input class="form-control" style="width: 80%;" type="text" name="jenis_potongan" value="{{ old('jenis_potongan', $potongan_gaji->jenis_potongan) }}">
                                            </div>
            
                                            <div style="gap: .5rem;flex-wrap: wrap;" class="form-group justify-content-between d-flex align-items-center mb-5">
                                                <label class="m-0" for="jumlah_potongan">Jumlah Potongan</label>
                                                <input class="form-control" style="width: 80%;" type="number" name="jumlah_potongan" value="{{ old('jumlah_potongan', $potongan_gaji->jumlah_potongan) }}">
                                            </div>
                                            
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </form>
            
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
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