@extends('layouts.master')

@section('laporan_kegiatan_hkc_select','active')
@section('title', 'Laporan Harian Kegiatan Cetak (HKC)')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Harian Kegiatan Cetak (HKC)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Pencatatan</li>
                        <li class="breadcrumb-item active">Laporan HKC</li>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalLaporanKegiatanHkc" onclick="openAddModalLaporanHkc()">
                                <i class="fa fa-plus"></i> Tambah Laporan HKC
                            </button>
                        </div>

                        {{-- Tabel Data Laporan --}}
                        <div class="table-responsive">
                            <table id="datatableHkc" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Anak</th>
                                        <th>Tema Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Catatan</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laporan_kegiatan as $laporan)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $laporan->nama_anak }}</td>
                                            {{-- Akses tema dan nama kegiatan dari array 'kegiatan' --}}
                                            <td>{{ $laporan->kegiatan['tema'] ?? '-' }}</td>
                                            <td>{{ $laporan->kegiatan['nama'] ?? '-' }}</td>
                                            <td>{{ $laporan->catatan ?? '-' }}</td>
                                            <td>
                                                @if(is_array($laporan->foto) && count($laporan->foto) > 0)
                                                    <div class="d-flex flex-wrap" style="max-width: 150px;">
                                                        @foreach($laporan->foto as $foto_name)
                                                            <img src="{{ asset('uploads/laporankegiatanhkc/' . $foto_name) }}" alt="foto" width="50" class="m-1 border">
                                                        @endforeach
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    {{-- Tombol Edit: Memanggil modal dan mengisi data via Ajax --}}
                                                    <button class="btn btn-sm btn-info me-1" data-toggle="modal" data-target="#modalLaporanKegiatanHkc"
                                                            onclick="openEditModalLaporanHkc({{ $laporan->id }})">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    {{-- Form Hapus --}}
                                                    <form action="{{ route('laporan_kegiatan.destroy.hkc', $laporan->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data laporan HKC.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Modal Tambah/Edit Laporan Kegiatan HKC --}}
                        <div class="modal fade" id="modalLaporanKegiatanHkc" tabindex="-1" aria-labelledby="modalLaporanKegiatanHkcLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLaporanKegiatanHkcLabel">Tambah Laporan Kegiatan HKC</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {{-- Form: action dan method akan diatur oleh JavaScript --}}
                                    <form id="formLaporanKegiatanHkc" action="{{ route('laporan_kegiatan.store.hkc') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        {{-- Hidden input untuk menyimpan ID laporan saat edit --}}
                                        <input type="hidden" id="laporan_id_hkc_hidden" name="laporan_id">
                                        {{-- Hidden input untuk spoofing method PUT/PATCH saat edit --}}
                                        <input type="hidden" name="_method" id="form_method_spoofing_hkc" value="POST">

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="peserta_id_hkc_form" class="form-label">Nama Anak:</label>
                                                        {{-- Menggunakan Select2 untuk multi-select --}}
                                                        <select class="form-control select2 @error('peserta_id') is-invalid @enderror" id="peserta_id_hkc_form" name="peserta_id[]" multiple="multiple" data-placeholder="Pilih satu atau lebih anak" style="width: 100%;" required>
                                                            @foreach($pesertaHKC as $p) {{-- $pesertaHKC dari controller --}}
                                                                <option value="{{ $p->id_anak }}">{{ $p->full_name }} ({{ $p->kelas }})</option>
                                                            @endforeach
                                                        </select>
                                                        @error('peserta_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="tanggal_hkc_form" class="form-label">Tanggal:</label>
                                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal_hkc_form" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                        @error('tanggal')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="tema_kegiatan_hkc_form" class="form-label">Tema Kegiatan:</label>
                                                        <input type="text" class="form-control @error('tema_kegiatan') is-invalid @enderror" id="tema_kegiatan_hkc_form" name="tema_kegiatan" placeholder="Contoh: Literasi, Seni" value="{{ old('tema_kegiatan') }}" required>
                                                        @error('tema_kegiatan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="nama_kegiatan_hkc_form" class="form-label">Nama Kegiatan:</label>
                                                        <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan_hkc_form" name="nama_kegiatan" placeholder="Contoh: Membaca Dongeng" value="{{ old('nama_kegiatan') }}" required>
                                                        @error('nama_kegiatan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="catatan_hkc_form" class="form-label">Catatan Tambahan:</label>
                                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan_hkc_form" name="catatan" rows="3" placeholder="Tambahkan catatan khusus terkait kegiatan">{{ old('catatan') }}</textarea>
                                                @error('catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="foto_hkc_form" class="form-label">Foto Dokumentasi (Opsional, bisa lebih dari satu):</label>
                                                <input type="file" class="form-control @error('foto.*') is-invalid @enderror" id="foto_hkc_form" name="foto[]" multiple accept="image/*">
                                                <small class="form-text text-muted">Maks. 2MB per foto. Format: JPEG, PNG, JPG, GIF, SVG.</small>
                                                @error('foto.*')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror

                                                {{-- Area untuk menampilkan foto lama dan tombol hapus --}}
                                                <div id="existing_photos_hkc_container" class="mt-2" style="display: none;">
                                                    <p class="mb-1">Foto yang sudah ada:</p>
                                                    <div class="d-flex flex-wrap" id="existing_photos_hkc_preview">
                                                        {{-- Foto-foto lama akan dimuat di sini oleh JS --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary" id="btnSimpanLaporanHkc">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- End Modal Tambah/Edit HKC --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk HKC
        $('#datatableHkc').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [[ 0, "desc" ]] // Urutkan berdasarkan tanggal descending
        });

        // Inisialisasi Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });

        // Inisialisasi custom file input
        bsCustomFileInput.init();

        // ====================================================================
        // FUNGSI UNTUK MODAL TAMBAH/EDIT LAPORAN KEGIATAN HKC
        // ====================================================================

        // Fungsi untuk mereset form modal ke kondisi 'Tambah'
        window.resetLaporanHkcForm = function() {
            $('#formLaporanKegiatanHkc')[0].reset(); // Reset semua input form
            $('#formLaporanKegiatanHkc').attr('action', '{{ route('laporan_kegiatan.store.hkc') }}'); // Kembali ke rute store
            $('#form_method_spoofing_hkc').val('POST'); // Kembali ke method POST
            $('#modalLaporanKegiatanHkcLabel').text('Tambah Laporan Kegiatan HKC'); // Ubah judul modal
            $('#laporan_id_hkc_hidden').val(''); // Kosongkan ID laporan
            $('#btnSimpanLaporanHkc').text('Simpan'); // Ubah teks tombol simpan

            // Reset Select2: deselect semua opsi
            $('#peserta_id_hkc_form').val(null).trigger('change');

            // Reset preview foto dan input file
            $('#foto_hkc_form').val(''); // Reset input file
            $('.custom-file-label[for="foto_hkc_form"]').text('Pilih file'); // Reset label input file
            $('#existing_photos_hkc_container').hide(); // Sembunyikan container foto lama
            $('#existing_photos_hkc_preview').empty(); // Kosongkan preview foto lama
            
            // Bersihkan pesan error validasi jika ada
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Set tanggal default ke hari ini
            $('#tanggal_hkc_form').val('{{ date('Y-m-d') }}');
        };

        // Ketika tombol "Tambah Laporan HKC" diklik
        window.openAddModalLaporanHkc = function() {
            resetLaporanHkcForm();
        };

        // Ketika modal ditutup, reset form
        $('#modalLaporanKegiatanHkc').on('hidden.bs.modal', function () {
            resetLaporanHkcForm();
        });

        // Fungsi untuk membuka modal dalam mode Edit dan mengisi data via Ajax
        window.openEditModalLaporanHkc = function(id) {
            resetLaporanHkcForm(); // Reset form terlebih dahulu

            $('#modalLaporanKegiatanHkcLabel').text('Edit Laporan Kegiatan HKC'); // Ubah judul modal
            // Atur action form ke rute update HKC
            $('#formLaporanKegiatanHkc').attr('action', '{{ url('laporan-kegiatan') }}/' + id + '/update-hkc'); 
            $('#form_method_spoofing_hkc').val('PUT'); // Atur method menjadi PUT
            $('#laporan_id_hkc_hidden').val(id); // Simpan ID laporan di hidden field
            $('#btnSimpanLaporanHkc').text('Update Laporan'); // Ubah teks tombol simpan

            // Ambil data laporan menggunakan Ajax
            $.ajax({
                url: '{{ url('laporan-kegiatan') }}/' + id + '/edit-hkc-data', // URL untuk ambil data edit HKC
                method: 'GET',
                success: function(response) {
                    console.log("Data diterima untuk edit HKC:", response); // DEBUGGING: Lihat data di konsol

                    // Isi form dengan data yang diterima
                    $('#tanggal_hkc_form').val(response.tanggal); // Format YYYY-MM-DD
                    $('#tema_kegiatan_hkc_form').val(response.tema_kegiatan);
                    $('#nama_kegiatan_hkc_form').val(response.nama_kegiatan);
                    $('#catatan_hkc_form').val(response.catatan);

                    // Isi Select2 dengan peserta yang sudah ada (response.peserta_id adalah array)
                    $('#peserta_id_hkc_form').val(response.peserta_id).trigger('change');

                    // Tampilkan foto lama jika ada (multiple)
                    $('#existing_photos_hkc_preview').empty(); // Kosongkan dulu
                    if (response.foto && response.foto.length > 0) {
                        $('#existing_photos_hkc_container').show();
                        $.each(response.foto, function(index, url) {
                            const fileName = url.split('/').pop(); // Ambil nama file dari URL
                            const photoItem = `
                                <div class="position-relative d-inline-block m-1 border p-1" style="width: 100px; height: 100px;">
                                    <img src="${url}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-existing-photo-hkc"
                                            style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;"
                                            data-file-name="${fileName}">
                                        <i class="fa fa-times" style="font-size: 0.7em;"></i>
                                    </button>
                                    <input type="hidden" name="old_foto_names[]" value="${fileName}">
                                </div>
                            `;
                            $('#existing_photos_hkc_preview').append(photoItem);
                        });
                    } else {
                        $('#existing_photos_hkc_container').hide();
                    }

                    // Tampilkan modal
                    $('#modalLaporanKegiatanHkc').modal('show');
                },
                error: function(xhr) {
                    console.error("Error fetching HKC report data:", xhr.responseText);
                    alert('Gagal mengambil data laporan HKC. Silakan coba lagi.');
                    $('#modalLaporanKegiatanHkc').modal('hide');
                }
            });
        };

        // Menangani tombol hapus foto lama (untuk HKC)
        $(document).on('click', '.delete-existing-photo-hkc', function() {
            const button = $(this);
            const fileName = button.data('file-name');
            const parentDiv = button.closest('.position-relative');

            if (confirm('Anda yakin ingin menghapus foto ini?')) {
                // Tambahkan nama file ke hidden input untuk ditandai sebagai 'dihapus' saat submit
                $('#formLaporanKegiatanHkc').append(`<input type="hidden" name="deleted_foto_names[]" value="${fileName}">`);
                parentDiv.remove(); // Hapus item foto dari tampilan
                // Hapus juga input hidden old_foto_names[] yang sesuai
                $('input[name="old_foto_names[]"][value="'+fileName+'"]').remove();
            }
        });


        // Menangani submit form modal via Ajax (untuk HKC)
        $('#formLaporanKegiatanHkc').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const actionUrl = form.attr('action');
            const formData = new FormData(this);

            $.ajax({
                type: 'POST', // Method selalu POST karena FormData dan _method spoofing
                url: actionUrl,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalLaporanKegiatanHkc').modal('hide');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || 'Laporan HKC berhasil disimpan.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        alert(response.message || 'Laporan HKC berhasil disimpan.');
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
                        // Sesuaikan ID elemen form agar sesuai dengan ID di modal HKC
                        let formElementId = key.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase() + '_hkc_form'; 
                        
                        if ($('#' + formElementId).length) {
                             $('#' + formElementId).addClass('is-invalid').after('<div class="invalid-feedback">' + value + '</div>');
                        } else {
                            // Ini mungkin untuk array peserta_id atau error umum lainnya
                            $(`[name="${key}"], [name="${key}[]"]`).addClass('is-invalid').after('<div class="invalid-feedback">' + value + '</div>');
                        }
                    });

                    // Tampilkan modal lagi jika ada error validasi
                    $('#modalLaporanKegiatanHkc').modal('show');

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

        // Menangani label custom file input saat memilih file (untuk HKC)
        $('#foto_hkc_form').on('change', function() {
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
            $('#existing_photos_hkc_container').hide();
            $('#existing_photos_hkc_preview').empty();
            // Juga hapus semua hidden input 'old_foto_names[]' dan 'deleted_foto_names[]'
            $('input[name="old_foto_names[]"]').remove();
            $('input[name="deleted_foto_names[]"]').remove();
        });

        // Jika ada error validasi dari reload halaman
        @if ($errors->any())
            $(window).on('load', function() {
                // Cek apakah ada error terkait HKC
                // Cara paling aman adalah memeriksa apakah ada field yang seharusnya diisi oleh form HKC yang memiliki error
                // Misalnya, jika 'tema_kegiatan' ada di error, kemungkinan besar ini dari form HKC
                let hasHkcErrorFields = false;
                @foreach($errors->keys() as $errorField)
                    @if (Str::contains($errorField, ['tema_kegiatan', 'nama_kegiatan', 'peserta_id'])) // Contoh field HKC
                        hasHkcErrorFields = true;
                        @break
                    @endif
                @endforeach

                if (hasHkcErrorFields) {
                    $('#laporan_id_hkc_hidden').val('{{ old('laporan_id') }}');
                    $('#tanggal_hkc_form').val('{{ old('tanggal') }}');
                    $('#tema_kegiatan_hkc_form').val('{{ old('tema_kegiatan') }}');
                    $('#nama_kegiatan_hkc_form').val('{{ old('nama_kegiatan') }}');
                    $('#catatan_hkc_form').val('{{ old('catatan') }}');
                    
                    // Repopulate Select2
                    let oldPesertaIds = {!! json_encode(old('peserta_id', [])) !!};
                    $('#peserta_id_hkc_form').val(oldPesertaIds).trigger('change');

                    // Set mode edit jika ada laporan_id_hidden
                    if ($('#laporan_id_hkc_hidden').val() !== '') {
                        $('#modalLaporanKegiatanHkcLabel').text('Edit Laporan Kegiatan HKC');
                        $('#formLaporanKegiatanHkc').attr('action', '{{ url('laporan-kegiatan') }}/' + '{{ old('laporan_id') }}' + '/update-hkc');
                        $('#form_method_spoofing_hkc').val('PUT');
                        $('#btnSimpanLaporanHkc').text('Update Laporan');

                        // Mengisi kembali foto lama jika ada error validasi setelah edit submit
                        let oldFotoNamesOnValidationError = [];
                        @if(is_array(old('old_foto_names')))
                            oldFotoNamesOnValidationError = {!! json_encode(old('old_foto_names')) !!};
                        @endif

                        $('#existing_photos_hkc_preview').empty();
                        if (oldFotoNamesOnValidationError.length > 0) {
                            $('#existing_photos_hkc_container').show();
                            $.each(oldFotoNamesOnValidationError, function(index, fileName) {
                                const url = '{{ asset('uploads/laporankegiatanhkc/') }}' + '/' + fileName;
                                const photoItem = `
                                    <div class="position-relative d-inline-block m-1 border p-1" style="width: 100px; height: 100px;">
                                        <img src="${url}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute delete-existing-photo-hkc"
                                                style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;"
                                                data-file-name="${fileName}">
                                            <i class="fa fa-times" style="font-size: 0.7em;"></i>
                                        </button>
                                        <input type="hidden" name="old_foto_names[]" value="${fileName}">
                                    </div>
                                `;
                                $('#existing_photos_hkc_preview').append(photoItem);
                            });
                        }
                    }
                    $('#modalLaporanKegiatanHkc').modal('show'); // Tampilkan modal
                }
            });
        @endif
    });
</script>
@endpush