@extends('layouts2.master')

@section('jadwal_kursus_select','active')
@section('title', 'Jadwal Kursus')

@section('content')
<div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-success">
              <h4 class="card-title ">Jadwal Kursus</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                @if(isset($message))
                    <div class="mt-5 alert alert-warning text-center mx-auto" style="max-width: 600px;">
                        <h4>{{ $message }}</h4>
                    </div>
                @elseif($jadwalKursus->isEmpty())
                    <p>Belum ada jadwal kursus untuk anda.</p>
                @else
                <!-- Input Pencarian -->
                <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search...">
                <table class="table" id="jadwalTable">
                    <thead class="text-primary">
                        <tr>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Nama Kursus</th>
                            <th>Tipe Kursus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalKursus as $jadwal)
                            <tr>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ $jadwal->waktu }}</td>
                                <td>{{ $jadwal->kursus->nama_kursus }}</td>
                                <td>{{ $jadwal->tipe_kursus }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<script>
    // Fungsi untuk melakukan pencarian di tabel
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#jadwalTable tbody tr');

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