@extends('layouts.master')

@section('laporan_kegiatan_daycare_select','active')
@section('title', 'Laporan Harian Kegiatan Daycare (HKD)')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Harian Kegiatan Daycare (HKD)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Pencatatan</li>
                        <li class="breadcrumb-item active">Laporan Daycare (HKD)</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-12">
                    <div class="p-3 bg-white border shadow-sm">
                        {{-- Notifikasi Sukses/Error/Validasi --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Tombol Tambah Laporan --}}
                        <div class="d-flex justify-content-start mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalLaporanKegiatan" onclick="openAddModalLaporan()">
                                <i class="fa fa-plus"></i> Tambah Laporan
                            </button>
                        </div>

                        {{-- Tabel Data Laporan --}}
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Anak</th>
                                        <th>Kegiatan Utama</th>
                                        <th>Kegiatan Tambahan</th>
                                        <th>Catatan</th>
                                        <th>Foto</th>
                                        <!-- <th>Aksi</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laporan_kegiatan as $laporan)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $laporan->nama_anak }}</td>
                                            <td>
                                                {{-- Menampilkan kegiatan utama dari array kegiatan --}}
                                                <ul>
                                                    @if(is_array($laporan->kegiatan) && in_array('Snack Pagi', $laporan->kegiatan)) <li>Snack Pagi</li> @endif
                                                    @if(is_array($laporan->kegiatan) && in_array('Makan Siang', $laporan->kegiatan)) <li>Makan Siang</li> @endif
                                                    @if(is_array($laporan->kegiatan) && in_array('Snack Sore', $laporan->kegiatan)) <li>Snack Sore</li> @endif
                                                    @if(is_array($laporan->kegiatan) && in_array('Tidur Siang', $laporan->kegiatan)) <li>Tidur Siang</li> @endif
                                                </ul>
                                            </td>
                                            <td>
                                                {{-- Memisahkan kegiatan tambahan dari kegiatan utama --}}
                                                @php
                                                    $kegiatan_utama_names = ['Snack Pagi', 'Makan Siang', 'Snack Sore', 'Tidur Siang'];
                                                    $kegiatan_tambahan = array_diff($laporan->kegiatan ?? [], $kegiatan_utama_names);
                                                @endphp
                                                {{ implode(', ', $kegiatan_tambahan) ?: '-' }}
                                            </td>
                                            <td>{{ $laporan->catatan ?? '-' }}</td>
                                            <td>
                                                @if(is_array($laporan->foto) && count($laporan->foto) > 0)
                                                    <div class="d-flex flex-wrap" style="max-width: 150px;">
                                                    @foreach($laporan->foto as $foto_name)
                                                        <img src="{{ asset('uploads/laporankegiatanhkd/' . $foto_name) }}" alt="foto" width="50" class="m-1 border">
                                                    @endforeach
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <!-- <td>
                                                <div class="d-flex">
                                                    {{-- Tombol Edit: Memanggil modal dan mengisi data via Ajax --}}
                                                    <button class="btn btn-sm btn-info me-1" data-toggle="modal" data-target="#modalLaporanKegiatan"
                                                            onclick="openEditModalLaporan({{ $laporan->id }})">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    {{-- Form Hapus --}}
                                                    <form action="{{ route('laporan_kegiatan.daycare.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td> -->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data laporan HKD.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Modal Tambah/Edit Laporan Kegiatan (Satu Modal untuk Keduanya) --}}
                        <div class="modal fade" id="modalLaporanKegiatan" tabindex="-1" aria-labelledby="modalLaporanKegiatanLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLaporanKegiatanLabel">Tambah Laporan Kegiatan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {{-- Form: action dan method akan diatur oleh JavaScript --}}
                                    <form id="formLaporanKegiatan" action="{{ route('laporan_kegiatan.daycare.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        {{-- Hidden input untuk menyimpan ID laporan saat edit --}}
                                        <input type="hidden" id="laporan_id_hidden" name="laporan_id">
                                        {{-- Hidden input untuk spoofing method PUT/PATCH saat edit --}}
                                        <input type="hidden" name="_method" id="form_method_spoofing_laporan" value="POST">

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3"> {{-- Gunakan mb-3 untuk konsistensi form-group --}}
                                                        <label for="peserta_id_form" class="form-label">Nama Anak:</label>
                                                        <select class="form-control @error('peserta_id') is-invalid @enderror" id="peserta_id_form" name="peserta_id" required>
                                                            <option value="">-- Pilih Anak --</option>
                                                            @foreach($peserta as $p)
                                                                <option value="{{ $p->id_anak }}">{{ $p->full_name }} ({{ $p->id_anak }})</option>
                                                            @endforeach
                                                        </select>
                                                        @error('peserta_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="tanggal_form" class="form-label">Tanggal:</label>
                                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal_form" name="tanggal" value="{{ date('Y-m-d') }}" required>
                                                        @error('tanggal')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Kegiatan Utama:</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="snack_pagi_form" name="snack_pagi" value="Snack Pagi">
                                                            <label class="form-check-label" for="snack_pagi_form">Snack Pagi</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="makan_siang_form" name="makan_siang" value="Makan Siang">
                                                            <label class="form-check-label" for="makan_siang_form">Makan Siang</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="snack_sore_form" name="snack_sore" value="Snack Sore">
                                                            <label class="form-check-label" for="snack_sore_form">Snack Sore</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="tidur_siang_form" name="tidur_siang" value="Tidur Siang">
                                                            <label class="form-check-label" for="tidur_siang_form">Tidur Siang</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="kegiatan_form" class="form-label">Kegiatan Tambahan:</label>
                                                        <textarea class="form-control @error('kegiatan') is-invalid @enderror" id="kegiatan_form" name="kegiatan" rows="3" placeholder="Pisahkan dengan koma atau baris baru"></textarea>
                                                        @error('kegiatan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="catatan_form" class="form-label">Catatan:</label>
                                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan_form" name="catatan" rows="3"></textarea>
                                                @error('catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="foto_form" class="form-label">Foto (Bisa pilih lebih dari 1):</label>
                                                <input type="file" class="form-control @error('foto.*') is-invalid @enderror" id="foto_form" name="foto[]" multiple accept="image/*"> {{-- name="foto[]" dan multiple --}}
                                                <small class="form-text text-muted">Format: jpeg, png, jpg, gif, svg. Maksimal 2MB per file.</small>
                                                @error('foto.*') {{-- Validasi error untuk multiple files --}}
                                                    <div class="invalid-feedback d-block">{{ $message }}</div> {{-- d-block agar pesan langsung terlihat --}}
                                                @enderror

                                                {{-- Area untuk menampilkan foto lama dan tombol hapus --}}
                                                <div id="existing_photos_container" class="mt-2" style="display: none;">
                                                    <p class="mb-1">Foto yang sudah ada:</p>
                                                    <div class="d-flex flex-wrap" id="existing_photos_preview">
                                                        {{-- Foto-foto lama akan dimuat di sini oleh JS --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary" id="btnSimpanLaporan">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- End Modal Tambah/Edit --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#datatable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // ====================================================================
        // FUNGSI UNTUK MODAL TAMBAH/EDIT LAPORAN KEGIATAN
        // ====================================================================

        // Fungsi untuk mereset form modal ke kondisi 'Tambah'
        window.resetLaporanForm = function() {
            $('#formLaporanKegiatan')[0].reset(); // Reset semua input form
            $('#formLaporanKegiatan').attr('action', '{{ route('laporan_kegiatan.daycare.store') }}'); // Kembali ke rute store
            $('#form_method_spoofing_laporan').val('POST'); // Kembali ke method POST
            $('#modalLaporanKegiatanLabel').text('Tambah Laporan Kegiatan'); // Ubah judul modal
            $('#laporan_id_hidden').val(''); // Kosongkan ID laporan
            $('#btnSimpanLaporan').text('Simpan'); // Ubah teks tombol simpan

            // Reset checkbox kegiatan utama
            $('#snack_pagi_form').prop('checked', false);
            $('#makan_siang_form').prop('checked', false);
            $('#snack_sore_form').prop('checked', false);
            $('#tidur_siang_form').prop('checked', false);

            // Reset preview foto dan input file
            $('#foto_form').val(''); // Reset input file
            $('.custom-file-label[for="foto_form"]').text('Pilih file'); // Reset label input file
            $('#existing_photos_container').hide(); // Sembunyikan container foto lama
            $('#existing_photos_preview').empty(); // Kosongkan preview foto lama
            
            // Bersihkan pesan error validasi jika ada
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Set tanggal default ke hari ini
            $('#tanggal_form').val('{{ date('Y-m-d') }}');
        };

        // Ketika tombol "Tambah Laporan" diklik
        window.openAddModalLaporan = function() { // Ubah nama fungsi untuk spesifikasi
            resetLaporanForm();
        };

        // Ketika modal ditutup, reset form
        $('#modalLaporanKegiatan').on('hidden.bs.modal', function () {
            resetLaporanForm();
        });

        // Fungsi untuk membuka modal dalam mode Edit dan mengisi data via Ajax
        window.openEditModalLaporan = function(id) { // Ubah nama fungsi untuk spesifikasi
            resetLaporanForm(); // Reset form terlebih dahulu

            $('#modalLaporanKegiatanLabel').text('Edit Laporan Kegiatan'); // Ubah judul modal
            $('#formLaporanKegiatan').attr('action', '{{ url('laporan-kegiatan-daycare') }}/' + id); // Atur action form ke rute update
            $('#form_method_spoofing_laporan').val('PUT'); // Atur method menjadi PUT
            $('#laporan_id_hidden').val(id); // Simpan ID laporan di hidden field
            $('#btnSimpanLaporan').text('Update Laporan'); // Ubah teks tombol simpan

            // Ambil data laporan menggunakan Ajax
            $.ajax({
                url: '{{ url('laporan-kegiatan-daycare') }}/' + id + '/get-data', // URL untuk ambil data edit
                method: 'GET',
                success: function(response) {
                    console.log("Data diterima untuk edit:", response); // DEBUGGING: Lihat data di konsol

                    // Isi form dengan data yang diterima
                    $('#peserta_id_form').val(response.peserta_id);
                    $('#tanggal_form').val(response.tanggal); // Format YYYY-MM-DD
                    $('#catatan_form').val(response.catatan);

                    // Isi checkbox kegiatan utama
                    $('#snack_pagi_form').prop('checked', response.kegiatan_utama_checkbox.snack_pagi);
                    $('#makan_siang_form').prop('checked', response.kegiatan_utama_checkbox.makan_siang);
                    $('#snack_sore_form').prop('checked', response.kegiatan_utama_checkbox.snack_sore);
                    $('#tidur_siang_form').prop('checked', response.kegiatan_utama_checkbox.tidur_siang);

                    // Isi kegiatan tambahan
                    $('#kegiatan_form').val(response.kegiatan_tambahan_string);

                    // Tampilkan foto lama jika ada (multiple)
                    $('#existing_photos_preview').empty(); // Kosongkan dulu
                    if (response.foto && response.foto.length > 0) {
                        $('#existing_photos_container').show();
                        $.each(response.foto, function(index, url) {
                            const fileName = url.split('/').pop(); // Ambil nama file dari URL
                            const photoItem = `
                                <div class="position-relative d-inline-block m-1 border p-1" style="width: 100px; height: 100px;">
                                    <img src="${url}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-existing-photo"
                                            style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;"
                                            data-file-name="${fileName}">
                                        <i class="fa fa-times" style="font-size: 0.7em;"></i>
                                    </button>
                                    <input type="hidden" name="old_foto_names[]" value="${fileName}">
                                </div>
                            `;
                            $('#existing_photos_preview').append(photoItem);
                        });
                    } else {
                        $('#existing_photos_container').hide();
                    }

                    // Tampilkan modal
                    $('#modalLaporanKegiatan').modal('show');
                },
                error: function(xhr) {
                    console.error("Error fetching report data:", xhr.responseText);
                    alert('Gagal mengambil data laporan. Silakan coba lagi.');
                    $('#modalLaporanKegiatan').modal('hide');
                }
            });
        };

        // Menangani tombol hapus foto lama (AJAX)
        // Perhatikan bahwa event listener diletakkan pada 'document' untuk elemen yang ditambahkan secara dinamis
        $(document).on('click', '.delete-existing-photo', function() {
            const button = $(this);
            const fileName = button.data('file-name');
            const parentDiv = button.closest('.position-relative');

            if (confirm('Anda yakin ingin menghapus foto ini?')) {
                // Tambahkan nama file ke hidden input untuk ditandai sebagai 'dihapus' saat submit
                $('#formLaporanKegiatan').append(`<input type="hidden" name="deleted_foto_names[]" value="${fileName}">`);
                parentDiv.remove(); // Hapus item foto dari tampilan
                // Hapus juga input hidden old_foto_names[] yang sesuai agar tidak terkirim dua kali atau salah
                $('input[name="old_foto_names[]"][value="'+fileName+'"]').remove();
            }
        });


        // Menangani submit form modal via Ajax
        $('#formLaporanKegiatan').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const actionUrl = form.attr('action');
            const formData = new FormData(this);

            // Tambahkan _method untuk spoofing PUT/DELETE (sudah ada di hidden input)

            $.ajax({
                type: 'POST', // Method selalu POST karena FormData dan _method spoofing
                url: actionUrl,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalLaporanKegiatan').modal('hide');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil disimpan.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        alert(response.message || 'Data berhasil disimpan.');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    // Bersihkan pesan error sebelumnya
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Tampilkan pesan error validasi di form
                    $.each(errors, function(key, value) {
                        // Sesuaikan ID elemen form agar sesuai dengan ID di modal
                        // Contoh: peserta_id -> peserta_id_form, tanggal -> tanggal_form
                        // kegiatan -> kegiatan_form, catatan -> catatan_form, foto -> foto_form
                        let formElementId = key.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase() + '_form'; // konversi camelCase ke snake_case dan tambah _form
                        
                        // Periksa apakah elemen form ada sebelum menambahkan kelas
                        if ($('#' + formElementId).length) {
                             $('#' + formElementId).addClass('is-invalid').after('<div class="invalid-feedback">' + value + '</div>');
                        } else {
                            // Ini mungkin untuk checkbox (snack_pagi dll) yang tidak punya _form di ID nya
                            // Atau error yang tidak terkait langsung dengan input yang punya ID *_form
                            // Coba tambahkan pesan error di elemen terkait jika ada, atau di bawah form
                            $(`[name="${key}"], [name="${key}[]"]`).addClass('is-invalid').after('<div class="invalid-feedback">' + value + '</div>');
                        }
                    });

                    // Tampilkan modal lagi jika ada error validasi
                    $('#modalLaporanKegiatan').modal('show');

                    // Tampilkan SweetAlert error
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan validasi. Mohon periksa kembali input Anda.',
                            icon: 'error',
                        });
                    } else {
                        alert('Terjadi kesalahan validasi. Mohon periksa kembali input Anda.');
                    }
                }
            });
        });

        // Menangani label custom file input saat memilih file
        $('#foto_form').on('change', function() {
            let fileNames = [];
            if (this.files && this.files.length > 0) {
                for (let i = 0; i < this.files.length; i++) {
                    fileNames.push(this.files[i].name);
                }
                $(this).next('.custom-file-label').html(fileNames.join(', '));
            } else {
                $(this).next('.custom-file-label').html('Pilih file');
            }

            // Saat file baru dipilih, sembunyikan area foto lama dan kosongkan
            $('#existing_photos_container').hide();
            $('#existing_photos_preview').empty();
            // Juga hapus semua hidden input 'old_foto_names[]' dan 'deleted_foto_names[]'
            $('input[name="old_foto_names[]"]').remove();
            $('input[name="deleted_foto_names[]"]').remove();
        });

        // Jika ada error validasi dari reload halaman (misalnya non-Ajax submit awal atau redirect withErrors)
        // Ini akan memicu modal kembali terbuka dengan pesan error jika server melakukan redirect
        @if ($errors->any())
            $(window).on('load', function() {
                let isEditMode = '{{ old('laporan_id') }}' !== '';

                // Isi kembali input dengan old() value jika ada error
                $('#peserta_id_form').val('{{ old('peserta_id') }}');
                $('#tanggal_form').val('{{ old('tanggal') }}');
                $('#kegiatan_form').val('{{ old('kegiatan') }}');
                $('#catatan_form').val('{{ old('catatan') }}');
                $('#laporan_id_hidden').val('{{ old('laporan_id') }}');

                // Isi kembali checkbox (perlu konversi old value ke boolean)
                $('#snack_pagi_form').prop('checked', {{ old('snack_pagi') ? 'true' : 'false' }});
                $('#makan_siang_form').prop('checked', {{ old('makan_siang') ? 'true' : 'false' }});
                $('#snack_sore_form').prop('checked', {{ old('snack_sore') ? 'true' : 'false' }});
                $('#tidur_siang_form').prop('checked', {{ old('tidur_siang') ? 'true' : 'false' }});

                // Set mode form dan URL action
                if (isEditMode) {
                    $('#modalLaporanKegiatanLabel').text('Edit Laporan Kegiatan');
                    $('#formLaporanKegiatan').attr('action', '{{ url('laporan-kegiatan-daycare') }}/' + '{{ old('laporan_id') }}');
                    $('#form_method_spoofing_laporan').val('PUT');
                    $('#btnSimpanLaporan').text('Update Laporan');

                    // Mengisi kembali foto lama (jika ada error validasi setelah edit submit)
                    // Ini bergantung pada bagaimana `old('old_foto_names')` dikirim kembali dari controller
                    let oldFotoNamesOnValidationError = [];
                    @if(is_array(old('old_foto_names')))
                        oldFotoNamesOnValidationError = {!! json_encode(old('old_foto_names')) !!};
                    @endif

                    $('#existing_photos_preview').empty();
                    if (oldFotoNamesOnValidationError.length > 0) {
                        $('#existing_photos_container').show();
                        $.each(oldFotoNamesOnValidationError, function(index, fileName) {
                            const url = '{{ asset('uploads/laporankegiatanhkd/') }}' + '/' + fileName;
                            const photoItem = `
                                <div class="position-relative d-inline-block m-1 border p-1" style="width: 100px; height: 100px;">
                                    <img src="${url}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-existing-photo"
                                            style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;"
                                            data-file-name="${fileName}">
                                        <i class="fa fa-times" style="font-size: 0.7em;"></i>
                                    </button>
                                    <input type="hidden" name="old_foto_names[]" value="${fileName}">
                                </div>
                            `;
                            $('#existing_photos_preview').append(photoItem);
                        });
                    }
                } else {
                    // Jika ini bukan mode edit, pastikan form direset ke mode tambah
                    resetLaporanForm();
                }
                
                $('#modalLaporanKegiatan').modal('show'); // Tampilkan modal
            });
        @endif
    });
</script>
@endpush