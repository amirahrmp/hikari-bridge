@extends('layouts.master')

@section('gaji_staf_select','active')
@section('title', 'Tambah Gaji')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Gaji</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Pencatatan</a></li>
                <li class="breadcrumb-item">Gaji</a></li>
              <li class="breadcrumb-item"><a href="#">Tambah</a></li>
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
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body p-0">
                                    <!-- Card 1: Bulan dan Tahun, Tanggal Gaji, Keterangan -->
                                    <div class="card mb-4">
                                        <div class="card-header" style="background-color: #28a745; color: white;">
                                            <h5 class="card-title">Pencatatan Gaji</h5>
                                        </div>                                        
                                        <div class="card-body">
                                            <form action="{{ route('gaji_staf.store') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="bulan_tahun">Periode</label>
                                                            <input type="month" name="bulan_tahun" id="bulan_tahun" class="form-control w-auto" value="{{ $bulan_tahun ?? date('Y-m') }}" required>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Kolom untuk Tanggal Pencatatan Gaji di Pojok Kanan -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tgl_gaji">Tanggal Pencatatan Gaji</label>
                                                            <input type="date" class="form-control w-auto" name="tgl_gaji" value="{{ $tgl_gaji }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <input type="text" name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran Gaji Staf Tetap">
                                                </div>
                                         
                                        </div>
                                    </div>
                                
                                    <!-- Card 2: Filter Tipe Staf dan Kolom Search -->
                                    <div class="card mb-4">
                                        <div class="card-header" style="background-color: #28a745; color: white;">
                                            <h5 class="card-title">Data Gaji Staf</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-4">
                                                <!-- Filter Tipe Staf -->
                                                <div class="w-50 d-flex align-items-center">
                                                    <label for="tipe_staf" class="mr-3 mb-0">Pilih</label>
                                                    <select id="tipe_staf" class="form-control">
                                                        <option value="">Pilih Tipe Staf</option>
                                                        <option value="Staf Tetap">Staf Tetap</option>
                                                        <option value="Staf Non Tetap">Staf Non Tetap</option>
                                                        <option value="Staf Daycare">Staf Daycare</option>
                                                    </select>
                                                </div>
                                                
                                                <!-- Kolom Search dengan margin kiri -->
                                                <div class="w-50 ml-3">
                                                    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                                                </div>
                                            </div>
                                                                                                                                
                                        
                                            <!-- Tabel Data Staf dengan Modal Input Gaji -->
                                            <div class="table-responsive">
                                                <table id="datatable2" class="table table-bordered table-striped">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Nama Staf</th>
                                                            <th>Departemen</th>
                                                            <th>Gaji Pokok</th>
                                                            <th>PPH 21</th>
                                                            <th>Total Gaji</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="stafTableBody">
                                                        @foreach($stafs as $staf)
                                                            <tr class="staf-row" data-tipe-staf="{{ $staf->tipe_staf }}">
                                                                <td>{{ $staf->nama_staf }}</td>
                                                                <td>{{ $staf->departemen }}</td>
                                                                <td>
                                                                    <span id="gaji_pokok_display{{ $staf->id }}">{{ rupiah($staf->gaji_pokok) ?? '0' }}</span>
                                                                </td>
                                                                <td>
                                                                    <span id="pph_21_display{{ $staf->id }}">{{ rupiah($staf->pph_21) ?? '0' }}</span>
                                                                </td>
                                                                <td>
                                                                    <span id="total_gaji_display{{ $staf->id }}">{{ rupiah($staf->total_gaji) ?? '0' }}</span>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn-sm btn-success d-inline-block" data-toggle="modal" data-target="#modalGaji{{ $staf->id }}">
                                                                        Input Gaji
                                                                    </button>
                                                                </td>
                                                            </tr>
                                        
                                                            <!-- Modal Input Gaji -->
                                                            <div class="modal fade" id="modalGaji{{ $staf->id }}" tabindex="-1" role="dialog" aria-labelledby="modalGajiLabel{{ $staf->id }}" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="modalGajiLabel{{ $staf->id }}">Input Gaji untuk {{ $staf->nama_staf }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <!-- Informasi Staf -->
                                                                            <p><strong>Nama:</strong> {{ $staf->nama_staf }}</p>
                                                                            <p><strong>Jabatan:</strong> {{ $staf->jabatan }}</p>
                                                                            <p><strong>Departemen:</strong> {{ $staf->departemen }}</p>
                                                                            <p><strong>Jenis staf:</strong> {{ $staf->staf }}</p>
                                                                            <p><strong>PTKP:</strong> {{ $staf->ptkp }}</p>
                                                                            <p><strong>Total Kehadiran:</strong> <span id="total_kehadiran_modal{{ $staf->id }}">{{ $staf->total_kehadiran ?? 0 }}</span></p>
                                        
                                                                            <!-- Form Input Gaji -->
                                                                            <input type="hidden" name="staf[{{ $staf->id }}][id]" value="{{ $staf->id }}">
                                                                            <div class="form-group">
                                                                                <label for="gaji_pokok{{ $staf->id }}">Gaji Pokok</label>
                                                                                <input type="number" name="staf[{{ $staf->id }}][gaji_pokok]" id="gaji_pokok{{ $staf->id }}" class="form-control" value="{{ $staf->gaji_pokok }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="uang_makan{{ $staf->id }}">Uang Makan</label>
                                                                                <input type="number" name="staf[{{ $staf->id }}][uang_makan]" id="uang_makan{{ $staf->id }}" class="form-control" value="{{ $staf->uang_makan }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="tunjangan{{ $staf->id }}">Tunjangan</label>
                                                                                <input type="number" name="staf[{{ $staf->id }}][tunjangan]" id="tunjangan{{ $staf->id }}" class="form-control" value="{{ $staf->tunjangan }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="bonus{{ $staf->id }}">Bonus</label>
                                                                                <input type="number" name="staf[{{ $staf->id }}][bonus]" id="bonus{{ $staf->id }}" class="form-control" value="{{ $staf->bonus }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="potongan_pph21{{ $staf->id }}">Potongan PPH 21</label>
                                                                                <input type="number" name="staf[{{ $staf->id }}][potongan_pph21]" id="potongan_pph21{{ $staf->id }}" class="form-control" value="{{ $staf->potongan_pph21 }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="potongan{{ $staf->id }}">Potongan</label>
                                                                                <input type="number" name="staf[{{ $staf->id }}][potongan]" id="potongan{{ $staf->id }}" class="form-control" value="{{ $staf->potongan }}">
                                                                            </div>
                                                                            <input type="hidden" name="staf[{{ $staf->id }}][total_gaji]" id="total_gaji_modal{{ $staf->id }}" value="{{ $staf->total_gaji ?? 0 }}" />
                                        
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="button" class="btn btn-success" onclick="hitungTotalGaji({{ $staf->id }})">Hitung Total Gaji</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        
                                            <!-- Total Gaji yang Dibayarkan -->
                                            <div class="form-group">
                                                <label for="total_gaji_dibayarkan">Total Gaji yang Dibayarkan</label>
                                                <input type="text" id="total_gaji_dibayarkan" name="total_gaji_dibayarkan" class="form-control" readonly>
                                            </div>
                                        
                                            <button type="submit" class="btn btn-primary d-inline-block">Simpan</button>
                                            <button type="button" class="btn btn-secondary d-inline-block" onclick="window.location.href='{{ route('gaji_staf.index') }}';">Batal</button>
                                        </form>
                                    </div>
                                    <!-- /.card-body -->
            
                                    <div class="card-footer clearfix">
                                        
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                <!-- /.content -->
                
            </div>
            <!--/Datatable-->

        </div>
    </div>
</div>
</div>
</div>

<script>
    document.getElementById('tipe_staf').addEventListener('change', function() {
        var selectedTipe = this.value.toLowerCase();
        var rows = document.querySelectorAll('.staf-row');

        rows.forEach(function(row) {
            var tipeStaf = row.getAttribute('data-tipe-staf').toLowerCase();
            if (selectedTipe === '' || tipeStaf.includes(selectedTipe)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<script>
    function formatRupiah(angka, prefix = 'Rp ') {
        // Pastikan angka yang diterima adalah angka valid
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        // Jika ada bagian desimal, tambahkan dua angka desimal, jika tidak, jangan
        if (split[1] !== undefined) {
            rupiah = rupiah + ',' + split[1].substr(0, 2);
        }

        // Hilangkan desimal jika angka adalah bilangan bulat
        return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    function hitungTotalGaji(stafId) {
        var gajiPokok = parseFloat(document.getElementById('gaji_pokok' + stafId).value) || 0;
        var uangMakan = parseFloat(document.getElementById('uang_makan' + stafId).value) || 0;
        var tunjangan = parseFloat(document.getElementById('tunjangan' + stafId).value) || 0;
        var bonus = parseFloat(document.getElementById('bonus' + stafId).value) || 0;
        var potonganPph21 = parseFloat(document.getElementById('potongan_pph21' + stafId).value) || 0;
        var potongan = parseFloat(document.getElementById('potongan' + stafId).value) || 0;

        var totalGaji = gajiPokok + uangMakan + tunjangan + bonus - potonganPph21 - potongan;

        // Update total gaji di modal dan di tabel
        document.getElementById('gaji_pokok_display' + stafId).textContent = formatRupiah(gajiPokok.toString());
        document.getElementById('pph_21_display' + stafId).textContent = formatRupiah(potonganPph21.toString());
        document.getElementById('total_gaji_display' + stafId).textContent = formatRupiah(totalGaji.toString());
        document.getElementById('total_gaji_modal' + stafId).value = totalGaji.toFixed(0); // Gunakan `.toFixed(0)` untuk menghilangkan desimal

        // Set total gaji pada input hidden form
        document.querySelector('[name="staf[' + stafId + '][total_gaji]"]').value = totalGaji;

        // Update total gaji yang dibayarkan
        updateTotalGaji();

        $('#modalGaji' + stafId).modal('hide');
    }

    window.addEventListener('DOMContentLoaded', (event) => {
        @foreach($stafs as $staf)
            // Format nilai di tabel saat halaman dimuat
            document.getElementById('gaji_pokok_display{{ $staf->id }}').textContent = formatRupiah('{{ $staf->gaji_pokok }}');
            document.getElementById('pph_21_display{{ $staf->id }}').textContent = formatRupiah('{{ $staf->pph_21 }}');
            document.getElementById('total_gaji_display{{ $staf->id }}').textContent = formatRupiah('{{ $staf->total_gaji }}');
        @endforeach
    });



    function updateTotalGaji() {
        let totalGajiDibayarkan = 0;

        // Ambil semua total gaji yang ada di form dan hitung totalnya
        document.querySelectorAll('[name*="[total_gaji]"]').forEach(function(input) {
            totalGajiDibayarkan += parseFloat(input.value) || 0;
        });

        // Format total gaji yang dibayarkan sebagai Rupiah
        let totalGajiFormatted = formatRupiah(totalGajiDibayarkan.toString());

        // Update total gaji dibayarkan di form dengan format Rupiah
        document.getElementById('total_gaji_dibayarkan').value = totalGajiFormatted;
    }
</script>

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