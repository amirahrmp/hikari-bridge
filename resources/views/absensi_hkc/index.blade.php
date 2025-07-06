@extends('layouts.master')

@section('Absensi_hkc_select','active')
@section('title', 'Absensi Hikari Kidz Club')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Absensi Hikari Kidz Club</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card p-3">
                {{-- Kondisional: Tampilkan form edit jika ada objek $absensi (untuk edit) --}}
                @if(isset($absensi) && $absensi->exists)
                    <h2>Edit Absensi Hikari Kidz Club</h2>
                    <form method="POST" action="{{ route('absensi_hkc.update', $absensi->id) }}">
                        @csrf
                        @method('PUT') {{-- Gunakan method PUT untuk update --}}

                        <div class="form-group">
                            <label for="nama_anak">Nama Anak:</label>
                            <input type="text" id="nama_anak" class="form-control" value="{{ $absensi->nama_anak }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal Absensi:</label>
                            <input type="date" id="tanggal" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($absensi->created_at)->format('Y-m-d') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keterangan" id="hadir" value="Hadir" {{ $absensi->keterangan == 'Hadir' ? 'checked' : '' }}>
                                <label class="form-check-label" for="hadir">Hadir</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keterangan" id="izin" value="Izin" {{ $absensi->keterangan == 'Izin' ? 'checked' : '' }}>
                                <label class="form-check-label" for="izin">Izin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keterangan" id="alfa" value="Alfa" {{ $absensi->keterangan == 'Alfa' ? 'checked' : '' }}>
                                <label class="form-check-label" for="alfa">Alfa</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Simpan Perubahan</button>
                        <a href="{{ route('absensi_hkc.riwayat', ['history_date' => \Carbon\Carbon::parse($absensi->created_at)->format('Y-m-d')]) }}" class="btn btn-secondary mt-3 ml-2">Batal</a>
                    </form>
                @else
                    {{-- Tampilkan form input absensi harian jika tidak dalam mode edit --}}
                    {{-- Form Filter Tanggal untuk Absensi Harian --}}
                    <form method="GET" action="{{ route('absensi_hkc.index') }}" class="mb-3 d-flex align-items-center">
                        <label for="date" class="mr-2">Tanggal Absensi:</label>
                        <input type="date" id="date" name="date" value="{{ $date }}" class="form-control w-auto mr-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                       
                    </form>

                    <div class="mb-3">
                        <strong>Total Hadir:</strong> {{ $totalHadir }} |
                        <strong>Total Izin:</strong> {{ $totalIzin }} |
                        <strong>Total Alfa:</strong> {{ $totalAlfa }}
                    </div>

                    {{-- Form Input Absensi Harian --}}
                    <form method="POST" action="{{ route('absensi_hkc.store') }}" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">

                        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari nama anak...">

                        <table id="absensiTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Anak</th>
                                    <!-- <th>Program</th> -->
                                    <th>Tipe</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $hkc)
                                    @php
                                        $absen = $absensiHarian[$hkc->id_anak] ?? null; // Gunakan absensiHarian
                                    @endphp
                                    <tr>
                                        <td>{{ $hkc->full_name }}</td>
                                        <!-- <td>{{ $hkc->program }}</td> -->
                                        <td>{{ $hkc->nama_paket }}</td>
                                        <td>
                                            <label>
                                                <input type="radio" name="peserta[{{ $hkc->id_anak }}][keterangan]" value="Hadir"
                                                    {{ $absen && $absen->keterangan == 'Hadir' ? 'checked' : '' }} {{ $absen ? 'disabled' : '' }}> Hadir
                                            </label>
                                            <label>
                                                <input type="radio" name="peserta[{{ $hkc->id_anak }}][keterangan]" value="Izin"
                                                    {{ $absen && $absen->keterangan == 'Izin' ? 'checked' : '' }} {{ $absen ? 'disabled' : '' }}> Izin
                                            </label>
                                            <label>
                                                <input type="radio" name="peserta[{{ $hkc->id_anak }}][keterangan]" value="Alfa"
                                                    {{ $absen && $absen->keterangan == 'Alfa' ? 'checked' : '' }} {{ $absen ? 'disabled' : '' }}> Alfa
                                            </label>

                                            <input type="hidden" name="peserta[{{ $hkc->id_anak }}][nama_anak]" value="{{ $hkc->full_name }}">
                                            <input type="hidden" name="peserta[{{ $hkc->id_anak }}][program]" value="{{ $hkc->program }}">
                                            <input type="hidden" name="peserta[{{ $hkc->id_anak }}][nama_paket]" value="{{ $hkc->nama_paket }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-success mt-3" id="saveAttendanceBtn" {{ count($absensiHarian) > 0 ? 'disabled' : '' }}>Simpan Absensi</button>
                        @if(count($absensiHarian) > 0)
                            <button type="button" class="btn btn-info mt-3 ml-2" id="editAttendanceBtn">
                                <i class="fas fa-edit"></i> Ubah Absen
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>

<script>
    // Pastikan script ini hanya berjalan jika tidak dalam mode edit
    @if(!isset($absensi) || !$absensi->exists)
        // Filter pencarian berdasarkan isi baris pada tabel absensi harian
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let term = this.value.toLowerCase();
            let rows = document.querySelectorAll('#absensiTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const editAttendanceBtn = document.getElementById('editAttendanceBtn');
            const saveAttendanceBtn = document.getElementById('saveAttendanceBtn');
            const attendanceRadioButtons = document.querySelectorAll('#absensiTable input[type="radio"]');

            // Mengatur status awal: nonaktifkan radio button jika absensi sudah tersimpan
            @if(count($absensiHarian) > 0) // Gunakan absensiHarian
                attendanceRadioButtons.forEach(radio => {
                    radio.disabled = true;
                });
                saveAttendanceBtn.disabled = true;
            @else
                saveAttendanceBtn.disabled = false;
            @endif

            // Event listener untuk tombol "Ubah Absen"
            if (editAttendanceBtn) {
                editAttendanceBtn.addEventListener('click', function() {
                    attendanceRadioButtons.forEach(radio => {
                        radio.disabled = false; // Aktifkan radio button
                    });
                    saveAttendanceBtn.disabled = false; // Aktifkan tombol simpan
                    this.style.display = 'none'; // Sembunyikan tombol edit
                });
            }
        });
    @endif
</script>
@endsection
