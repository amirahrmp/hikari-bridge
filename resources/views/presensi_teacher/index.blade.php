@extends('layouts.master')

@section('presensi_teacher_select','active')
@section('title', 'Presensi Teacher')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Presensi Teacher</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Pencatatan</a></li>
              <li class="breadcrumb-item"><a href="#">Presensi Teacher</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
    
    <div class="row mt-3">
        <div class="col-sm-12">
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">           
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body p-0">
                                    <div>                                        
                                        <form method="GET" action="{{ route('presensi_teacher.index') }}">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="date" class="mr-2">Tanggal:</label>
                                                <input type="date" id="date" name="date" value="{{ $date }}" class="form-control w-auto">
                                                <button type="submit" class="btn btn-primary ml-2">Filter</button>
                                                <a href="{{ route('presensi_teacher.exportPdf', ['date' => $date]) }}" class="btn btn-danger ml-2">Cetak PDF</a>
                                            </div>
                                        </form>                                        
                                    
                                        <div class="mb-3 d-flex">
                                            <p class="mr-4"><strong>Total Hadir:</strong> {{ $totalHadir }}</p>
                                            <p class="mr-4"><strong>Total Izin:</strong> {{ $totalIzin }}</p>
                                            <p><strong>Total Alfa:</strong> {{ $totalAlfa }}</p>
                                        </div>                                        
                                    
                                        <form method="POST" action="{{ route('presensi_teacher.store') }}">
                                            @csrf
                                            <input type="hidden" name="date" value="{{ $date }}">
                                            <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search...">
                                            <table id="datatable2" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID Card</th>
                                                        <th>Nama Teacher</th>
                                                        <th>Tipe Pengajar</th>
                                                        <th>Keterangan</th>
                                                        <th>Waktu Masuk</th>
                                                        <th>Waktu Keluar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($teachers as $teacher)
                                                    <tr>
                                                        <td>{{ $teacher->id_card }}</td>
                                                        <td>{{ $teacher->nama_teacher }}</td>
                                                        <td>{{ $teacher->tipe_pengajar }}</td>
                                                        <td>
                                                            <!-- Radio button untuk memilih keterangan -->
                                                            <label>
                                                                <input type="radio" name="teachers[{{ $teacher->id_card }}][keterangan]" value="Hadir" 
                                                                    {{ isset($presensi[$teacher->id_card]) && ($presensi[$teacher->id_card]->keterangan == 'Hadir' || is_null($presensi[$teacher->id_card]->waktu_masuk)) ? 'checked' : '' }}>
                                                                Hadir
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="teachers[{{ $teacher->id_card }}][keterangan]" value="Izin" 
                                                                    {{ isset($presensi[$teacher->id_card]) && $presensi[$teacher->id_card]->keterangan == 'Izin' ? 'checked' : '' }}>
                                                                Izin
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="teachers[{{ $teacher->id_card }}][keterangan]" value="Alfa" 
                                                                    {{ isset($presensi[$teacher->id_card]) && $presensi[$teacher->id_card]->keterangan == 'Alfa' ? 'checked' : '' }}>
                                                                Alfa
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <input type="time" name="teachers[{{ $teacher->id_card }}][waktu_masuk]" class="form-control" 
                                                                value="{{ isset($presensi[$teacher->id_card]) && !is_null($presensi[$teacher->id_card]->waktu_masuk) ? \Carbon\Carbon::parse($presensi[$teacher->id_card]->waktu_masuk)->format('H:i') : '' }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" name="teachers[{{ $teacher->id_card }}][waktu_keluar]" class="form-control" 
                                                                value="{{ isset($presensi[$teacher->id_card]) && !is_null($presensi[$teacher->id_card]->waktu_keluar) ? \Carbon\Carbon::parse($presensi[$teacher->id_card]->waktu_keluar)->format('H:i') : '' }}">
                                                        </td>
                                                        <input type="hidden" name="teachers[{{ $teacher->id_card }}][nama_teacher]" value="{{ $teacher->nama_teacher }}">
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                            <button type="submit" class="btn btn-success mt-2">Update Data</button>
                                        </form>                                        
                                    </div>
                                    
                                    <div class="card-footer clearfix">
                                        
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
                
            </div>
            <!--/Datatable-->

        </div>
    </div>
</div>
</div>
<script>
    // Fungsi untuk melakukan pencarian di tabel
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#datatable2 tbody tr');

        tableRows.forEach(function(row) {
            let cells = row.getElementsByTagName('td');
            let matchFound = false;

            // Iterasi untuk mengecek jika ada teks yang cocok di salah satu kolom
            for (let i = 0; i < cells.length; i++) {
                let cellText = cells[i].textContent.toLowerCase();
                if (cellText.indexOf(searchTerm) !== -1) {
                    matchFound = true;
                    break;  // Jika ditemukan, berhenti memeriksa kolom lainnya
                }
            }

            // Tampilkan atau sembunyikan baris berdasarkan hasil pencarian
            if (matchFound) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection