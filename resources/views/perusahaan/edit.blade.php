@extends('main')

@section('title', 'Settings')

@section('content')

<!-- Sweet Alert -->
@if(isset($status_hapus))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Hapus Data Berhasil',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        </script>
@endif

<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Outlet</strong></h5>
    <span class="text-secondary">Settings <i class="fa fa-angle-right"></i> Outlet</span>
    
    <div class="row mt-3">
        <div class="col-sm-12">    
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">                            
              <h5 class="card-title fw-semibold mb-4">Data Perusahaan</h5>

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
                <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <fieldset disabled>
                        <div class="mb-3"><label for="kodeperusahaanlabel">Kode Perusahaan</label>
                        <input class="form-control form-control-solid" id="kode_perusahaan_tampil" name="kode_perusahaan_tampil" type="text" placeholder="Contoh: PR-001" value="{{$perusahaan->kode_perusahaan}}" readonly></div>
                    </fieldset>
                    <input type="hidden" id="kode_perusahaan" name="kode_perusahaan" value="{{$perusahaan->kode_perusahaan}}">

                    <div class="mb-3"><label for="namaperusahaanlabel">Nama Perusahaan</label>
                    <input class="form-control form-control-solid" id="nama_perusahaan" name="nama_perusahaan" type="text" placeholder="Contoh: Toko Mukena Sejuk Menenangkan" value="{{$perusahaan->nama_perusahaan}}">
                    </div>
                    
                    <div class="mb-0"><label for="alamatperusahaanlabel">Alamat Perusahaan</label>
                        <textarea class="form-control form-control-solid" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" placeholder="Cth: Jl Pelajar Pejuan 45">{{$perusahaan->alamat_perusahaan}}</textarea>
                    </div>
                    <div class="mb-0"><label for="alamatperusahaanlabel">Nomor Telepon</label>
                        <textarea class="form-control form-control-solid" id="nomor_telepon" name="nomor_telepon" rows="3" placeholder="Cth: 082988340001">{{$perusahaan->nomor_telepon}}</textarea>
                    </div>
                    <br>
                    <!-- untuk tombol simpan -->
                    
                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Ubah">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/perusahaan') }}" role="button">Batal</a>
                    
                </form>
                <!-- Akhir Dari Input Form -->
            
          </div>
        </div>
      </div>
		
		
		
        
@endsection