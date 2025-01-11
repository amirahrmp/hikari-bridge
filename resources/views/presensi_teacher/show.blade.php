@extends('layouts2.master')

@section('riwayat_presensi_select','active')
@section('title', 'Riwayat Presensi')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title ">Riwayat Presensi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if(isset($message))
                                <div class="mt-5 alert alert-warning text-center mx-auto" style="max-width: 600px;">
                                    <h4>{{ $message }}</h4>
                                </div>
                            @elseif($presensi->isEmpty())
                                <p>Belum ada riwayat presensi untuk ditampilkan.</p>
                            @else
                                <!-- Filter Tanggal Sejajar -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="mr-3">
                                        <label for="startDate">Tanggal Mulai:</label>
                                        <input type="date" id="startDate" class="form-control" style="width: auto;" value="{{ request('startDate') }}">
                                    </div>
                                    <div class="mr-3">
                                        <label for="endDate">Tanggal Selesai:</label>
                                        <input type="date" id="endDate" class="form-control" style="width: auto;" value="{{ request('endDate') }}">
                                    </div>
                                    <button class="btn btn-info" id="filterDateBtn">Filter</button>
                                </div>

                                <!-- Total Kehadiran, Izin, Alfa -->
                                <div class="d-flex justify-content-between mb-3">
                                    <p>Total Hadir: <span id="kehadiranCount">{{ $kehadiran }}</span></p>
                                    <p>Total Izin: <span id="izinCount">{{ $izin }}</span></p>
                                    <p>Total Alfa: <span id="alfaCount">{{ $alfa }}</span></p>
                                </div>

                                <!-- Input Pencarian -->
                                <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search...">
                                <table class="table" id="jadwalTable">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Waktu Masuk</th>
                                            <th>Waktu Keluar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($presensi as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_presensi)->format('d-m-Y') }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') }}</td>
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
    // Fungsi filter berdasarkan tanggal
    document.getElementById('filterDateBtn').addEventListener('click', function() {
        let startDate = document.getElementById('startDate').value;
        let endDate = document.getElementById('endDate').value;
        let url = new URL(window.location.href);
        
        // Menambahkan parameter tanggal ke URL
        if (startDate) {
            url.searchParams.set('startDate', startDate);
        } else {
            url.searchParams.delete('startDate');
        }

        if (endDate) {
            url.searchParams.set('endDate', endDate);
        } else {
            url.searchParams.delete('endDate');
        }

        // Memuat ulang halaman dengan filter tanggal
        window.location.href = url;
    });

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
