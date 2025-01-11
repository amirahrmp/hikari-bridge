@extends('main')

@section('title', 'Customer')

@section('content')
<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Customer</strong></h5>
    <span class="text-secondary">Master Data <i class="fa fa-angle-right"></i> Customer</span>
    
    <div class="row mt-3">
        <div class="col-sm-12">
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
            <h5 class="card-title fw-semibold mb-4">Data Customer</h5>

                <!-- Display Error jika ada error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Akhir Display Error -->

                <!-- Awal Dari Input Form -->
                <form action="{{ route('customer.store') }}" method="post">
                    @csrf
                    <fieldset disabled>
                        <div class="mb-3"><label for="nomorcustomerlabel">Nomor Customer</label>
                        <input class="form-control form-control-solid" id="nomor_customer_tampil" name="nomor_customer_tampil" type="text" placeholder="Contoh: CS-001" value="{{$nomor_customer}}" readonly></div>
                    </fieldset>
                    <input type="hidden" id="nomor_customer" name="nomor_customer" value="{{$nomor_customer}}">

                    <div class="mb-3"><label for="namacustomerlabel">Nama Customer</label>
                    <input class="form-control form-control-solid" id="nama_customer" name="nama_customer" type="text" placeholder="Contoh: Raka ardiansyah" value="{{old('nama_customer')}}">
                    </div>
                    
        
                    <div class="mb-0"><label for="nomortelplabel">Nomor Telp</label>
                        <textarea class="form-control form-control-solid" id="nomor_telp" name="nomor_telp" rows="1" placeholder="Cth: 088876548910">{{old('nomor_telpon')}}</textarea>
                    </div>
                    <br>
                    <!-- untuk tombol simpan -->
                    
                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/customer') }}" role="button">Batal</a>
                    
                </form>
                <!-- Akhir Dari Input Form -->
            
          </div>
        </div>
      </div>
		
		
		
        
@endsection