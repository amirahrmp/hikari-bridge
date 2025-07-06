{{-- resources/views/laporan/bukubesar.blade.php --}}
@extends('layouts.master')
@section('bukubesar_select','active')
@section('title', 'Buku Besar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Buku Besar</h1>
          </div><div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Laporan</a></li>
              <li class="breadcrumb-item active"><a href="#">Buku Besar</li>
            </ol>
          </div></div></div></div>
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">

                    <div class="card">

                        <div class="card">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-3">Pilih Periode</div>
                                        <div class="col-sm-9">
                                            {{-- Input type month untuk memilih periode --}}
                                            <input type="month" class="form-control" name="periode" id="periode" onchange="prosesBukuBesar()">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-3">Pilih Akun</div>
                                        <div class="col-sm-9">
                                            {{-- Dropdown akun diisi langsung dari data yang dilewatkan controller --}}
                                            <select name="id_akun" id="id_akun" class="form-control" onchange="prosesBukuBesar()" required>
                                                <option value="" disabled selected>- - - Pilih Akun - - -</option>
                                                {{-- Loop melalui variabel $akun yang dikirim dari controller --}}
                                                @foreach ($akun as $ak)
                                                    {{-- Gunakan kode_akun sebagai value dan nama_akun sebagai data-nama --}}
                                                    <option value="{{ $ak->kode_akun }}" data-nama="{{ $ak->nama_akun }}">{{ $ak->nama_akun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12" style="background-color:white;" align="center">
                                            <b>Hikari Bridge</b>
                                        </div>
                                        <div class="col-sm-12" style="background-color:white;" align="center">
                                            {{-- Judul Buku Besar (misal: Buku Besar Kas) --}}
                                            <div id="xbukubesar"></div>
                                        </div>
                                        <div class="col-sm-12" style="background-color:white;" align="center">
                                            {{-- Periode yang dipilih (misal: Periode Juli 2025) --}}
                                            <div id="xperiode"></div>
                                        </div>
                                    </div>

                                    <div class="responsive-table-plugin">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive" data-pattern="priority-columns">
                                                <table id="report" class="table table-bordered nowrap">
                                                    <thead class="thead-dark">
                                                        <tr bgcolor="#dbd7d7">
                                                            <th rowspan="2">Tanggal</th>
                                                            <th rowspan="2">Nama Akun</th>
                                                            <th rowspan="2" class="text-center">Debet</th>
                                                            <th rowspan="2" class="text-center">Kredit</th>
                                                            <th colspan="2" class="text-center">Saldo </th>
                                                        </tr>
                                                        <tr bgcolor="#dbd7d7">
                                                            <th class="text-center">Debet</th>
                                                            <th class="text-center">Kredit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- Konten tabel akan dimuat via AJAX --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </div> {{-- End Card for Datatable --}}

                </div> {{-- End Button Container --}}

            </div> {{-- End Col-sm-12 --}}
        </div> {{-- End Row mt-3 --}}
    </div> {{-- End Container-fluid --}}
</section> {{-- End Section Content --}}
</div> {{-- End Content-wrapper --}}

<script>
    // Fungsi untuk memformat angka menjadi format mata uang
    function number_format(number, decimals, decPoint, thousandsSep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number;
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
        var s = '';

        var toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k).toFixed(prec);
        };

        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }

        return s.join(dec);
    }

    // Fungsi untuk mengubah format YYYY-MM menjadi Bulan Tahun (e.g., Juli 2025)
    function rubah(periode) {
        var tahun = periode.substring(0, 4);
        var bulan = periode.substring(5);
        let bln;
        switch (bulan) {
            case '01': bln = "Januari"; break;
            case '02': bln = "Februari"; break;
            case '03': bln = "Maret"; break;
            case '04': bln = "April"; break;
            case '05': bln = "Mei"; break;
            case '06': bln = "Juni"; break;
            case '07': bln = "Juli"; break;
            case '08': bln = "Agustus"; break;
            case '09': bln = "September"; break;
            case '10': bln = "Oktober"; break;
            case '11': bln = "November"; break;
            case '12': bln = "Desember"; break;
            default: bln = "Invalid Bulan";
        }
        return bln.concat(" ", tahun);
    }

    /**
     * Fungsi utama untuk memproses dan menampilkan data Buku Besar.
     * Dipanggil setiap kali periode atau akun berubah.
     */
    function prosesBukuBesar() {
        var periode = document.getElementById("periode").value;
        var akunSelect = document.getElementById("id_akun");
        var selectedOption = akunSelect.selectedIndex !== -1 ? akunSelect.options[akunSelect.selectedIndex] : null;

        // Ambil kodeAkun dari value option yang dipilih dan namaAkun dari data-nama
        var kodeAkun = selectedOption ? selectedOption.value : null;
        var namaAkun = selectedOption ? selectedOption.dataset.nama : null;

        // Kosongkan tampilan tabel dan header jika input belum lengkap
        document.getElementById("xbukubesar").innerHTML = "";
        document.getElementById("xperiode").innerHTML = "";
        $('tbody').html("");

        // Hentikan proses jika periode atau akun belum dipilih
        if (!periode || !kodeAkun) {
            return;
        }

        var periode_tampil = rubah(periode);
        var url = "{{ url('jurnal/viewdatabukubesar/') }}";
        var urlFinal = url.concat("/", periode, "/", kodeAkun);

        $.ajax({
            type: "GET",
            url: urlFinal,
            success: function(response) {
                // Periksa status dan posisi saldo normal yang diterima dari server
                if (response.status !== 200 || response.posisi === null) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: response.message || 'Data buku besar tidak ditemukan atau terjadi kesalahan. Pastikan akun memiliki posisi saldo normal yang terdefinisi di COA.',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                    $('tbody').html(""); // Kosongkan tabel jika ada error
                    return;
                }

                // Update header laporan
                var tebal = "<b>";
                var akhirtebal = "</b>";
                document.getElementById("xperiode").innerHTML = tebal.concat("Periode ", periode_tampil, akhirtebal);
                document.getElementById("xbukubesar").innerHTML = tebal.concat("Buku Besar ", namaAkun, akhirtebal);

                // Inisialisasi saldo berjalan dengan saldo awal yang diterima dari backend
                let saldo_berjalan = response.saldoawal;
                let posisi_normal = response.posisi; // 'd' atau 'c'

                // Tampilkan baris Saldo Awal jika saldo awal tidak nol
                if (saldo_berjalan !== 0) {
                    let saldoAwalDebetDisplay = '-';
                    let saldoAwalKreditDisplay = '-';

                    // Menentukan di kolom mana saldo awal akan ditampilkan
                    if (saldo_berjalan >= 0) { // Saldo positif atau nol
                        if (posisi_normal === 'd') {
                            saldoAwalDebetDisplay = 'Rp ' + number_format(saldo_berjalan);
                        } else {
                            saldoAwalKreditDisplay = 'Rp ' + number_format(saldo_berjalan);
                        }
                    } else { // Saldo negatif (berlawanan dengan posisi normalnya)
                        if (posisi_normal === 'd') { // Normalnya debet, tapi saldonya negatif (jadi tampil di kredit)
                            saldoAwalKreditDisplay = 'Rp ' + number_format(Math.abs(saldo_berjalan));
                            saldoAwalDebetDisplay = '-';
                        } else { // Normalnya kredit, tapi saldonya negatif (jadi tampil di debet)
                            saldoAwalDebetDisplay = 'Rp ' + number_format(Math.abs(saldo_berjalan));
                            saldoAwalKreditDisplay = '-';
                        }
                    }

                    $('tbody').append('<tr>\
                        <td>-</td>\
                        <td><b>Saldo Awal</b></td>\
                        <td>-</td>\
                        <td>-</td>\
                        <td style="text-align:right;">' + saldoAwalDebetDisplay + '</td>\
                        <td style="text-align:right;">' + saldoAwalKreditDisplay + '</td>\
                    </tr>');
                }

                let totalDebetBulanIni = 0;
                let totalKreditBulanIni = 0;

                // Iterasi dan tampilkan transaksi bulan berjalan
                if (response.bukubesar.length > 0) {
                    $.each(response.bukubesar, function(key, item) {
                        var tgljurnal = item.tgl_jurnal.substring(0, 10);
                        let debetAmount = 0;
                        let kreditAmount = 0;

                        if (item.posisi_d_c === 'd') {
                            debetAmount = item.nominal;
                            totalDebetBulanIni += item.nominal;
                            if (posisi_normal === 'd') {
                                saldo_berjalan += item.nominal;
                            } else {
                                saldo_berjalan -= item.nominal;
                            }
                        } else { // posisi_d_c === 'c'
                            kreditAmount = item.nominal;
                            totalKreditBulanIni += item.nominal;
                            if (posisi_normal === 'd') {
                                saldo_berjalan -= item.nominal;
                            } else {
                                saldo_berjalan += item.nominal;
                            }
                        }

                        let saldoDebetDisplay = '-';
                        let saldoKreditDisplay = '-';

                        // Menentukan tampilan saldo berjalan per baris transaksi
                        if (saldo_berjalan >= 0) { // Saldo positif atau nol
                            if (posisi_normal === 'd') {
                                saldoDebetDisplay = 'Rp ' + number_format(saldo_berjalan);
                            } else {
                                saldoKreditDisplay = 'Rp ' + number_format(saldo_berjalan);
                            }
                        } else { // Saldo negatif
                            if (posisi_normal === 'd') { // Normalnya debet, tapi jadi negatif (kredit)
                                saldoKreditDisplay = 'Rp ' + number_format(Math.abs(saldo_berjalan));
                                saldoDebetDisplay = '-';
                            } else { // Normalnya kredit, tapi jadi negatif (debet)
                                saldoDebetDisplay = 'Rp ' + number_format(Math.abs(saldo_berjalan));
                                saldoKreditDisplay = '-';
                            }
                        }

                        $('tbody').append('<tr>\
                            <td class="text-center">' + tgljurnal + '</td>\
                            <td>' + item.nama_akun + '</td>\
                            <td style="text-align:right;">' + (debetAmount > 0 ? 'Rp ' + number_format(debetAmount) : '-') + '</td>\
                            <td style="text-align:right;">' + (kreditAmount > 0 ? 'Rp ' + number_format(kreditAmount) : '-') + '</td>\
                            <td style="text-align:right;">' + saldoDebetDisplay + '</td>\
                            <td style="text-align:right;">' + saldoKreditDisplay + '</td>\
                        </tr>');
                    });
                } else {
                     // Pesan jika tidak ada transaksi di bulan berjalan
                     $('tbody').append('<tr>\
                        <td colspan="6" class="text-center">Tidak ada transaksi untuk akun ini pada periode yang dipilih.</td>\
                    </tr>');
                }

                // Tampilkan baris Total Transaksi Bulan Ini
                $('tbody').append('<tr bgcolor="#dbd7d7">\
                    <td>-</td>\
                    <td><b>Total Transaksi Bulan Ini</b></td>\
                    <td style="text-align:right;">Rp ' + number_format(totalDebetBulanIni) + '</td>\
                    <td style="text-align:right;">Rp ' + number_format(totalKreditBulanIni) + '</td>\
                    <td colspan="2"></td>\
                </tr>');

                // Tampilkan baris Saldo Akhir jika saldo akhir tidak nol
                if (saldo_berjalan !== 0) {
                    let saldoAkhirDebetDisplay = '-';
                    let saldoAkhirKreditDisplay = '-';

                     if (saldo_berjalan >= 0) { // Saldo akhir positif atau nol
                        if (posisi_normal === 'd') {
                            saldoAkhirDebetDisplay = 'Rp ' + number_format(saldo_berjalan);
                        } else {
                            saldoAkhirKreditDisplay = 'Rp ' + number_format(saldo_berjalan);
                        }
                    } else { // Saldo akhir negatif
                        if (posisi_normal === 'd') { // Normalnya debet, tapi saldo akhir negatif
                            saldoAkhirKreditDisplay = 'Rp ' + number_format(Math.abs(saldo_berjalan));
                            saldoAkhirDebetDisplay = '-';
                        } else { // Normalnya kredit, tapi saldo akhir negatif
                            saldoAkhirDebetDisplay = 'Rp ' + number_format(Math.abs(saldo_berjalan));
                            saldoAkhirKreditDisplay = '-';
                        }
                    }

                    $('tbody').append('<tr bgcolor="#dbd7d7">\
                        <td>-</td>\
                        <td><b>Saldo Akhir</b></td>\
                        <td>-</td>\
                        <td>-</td>\
                        <td style="text-align:right;">' + saldoAkhirDebetDisplay + '</td>\
                        <td style="text-align:right;">' + saldoAkhirKreditDisplay + '</td>\
                    </tr>');
                } else {
                    // Jika saldo_berjalan adalah 0, tampilkan saldo akhir sebagai '-'
                    $('tbody').append('<tr bgcolor="#dbd7d7">\
                        <td>-</td>\
                        <td><b>Saldo Akhir</b></td>\
                        <td>-</td>\
                        <td>-</td>\
                        <td style="text-align:right;">-</td>\
                        <td style="text-align:right;">-</td>\
                    </tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching ledger data: ", error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memuat data buku besar. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                $('tbody').html("");
            }
        });
    }
</script>
@endsection