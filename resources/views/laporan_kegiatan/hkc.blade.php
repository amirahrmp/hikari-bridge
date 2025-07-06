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

                <div class="mb-3">
                    <button class="btn btn-primary" onclick="openAddModalLaporanHkc()">
                        <i class="fa fa-plus"></i> Tambah Laporan HKC
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatableHkc">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tema</th>
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
                                <td>{{ $laporan->kegiatan['tema'] ?? '-' }}</td>
                                <td>{{ $laporan->kegiatan['nama'] ?? '-' }}</td>
                                <td>{{ $laporan->catatan ?? '-' }}</td>
                                <td>
                                    @if(is_array($laporan->foto) && count($laporan->foto) > 0)
                                    <div class="d-flex flex-wrap" style="max-width: 150px;">
                                        @foreach($laporan->foto as $foto)
                                        <a href="{{ asset('uploads/laporankegiatanhkc/'.$foto) }}" target="_blank">
                                            <img src="{{ asset('uploads/laporankegiatanhkc/'.$foto) }}" width="50" class="m-1 border">
                                        </a>
                                        @endforeach
                                    </div>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="openEditModalLaporanHkc({{ $laporan->id }})"><i class="fa fa-edit"></i></button>
                                    <form action="{{ route('laporan_kegiatan.destroy.hkc', $laporan->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus laporan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">Belum ada data laporan HKC.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah/Edit HKC -->
<div class="modal fade" id="modalLaporanKegiatanHkc" tabindex="-1" role="dialog" aria-labelledby="modalLaporanKegiatanHkcLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formLaporanKegiatanHkc" method="POST" enctype="multipart/form-data" action="{{ route('laporan_kegiatan.store.hkc') }}">
            @csrf
            <input type="hidden" name="_method" id="form_method_hkc" value="POST">
            <input type="hidden" name="laporan_id" id="laporan_id_hkc">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLaporanKegiatanHkcLabel">Tambah Laporan Kegiatan HKC</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal_hkc" class="form-label">Tanggal:</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" id="tanggal_hkc" required value="{{ date('Y-m-d') }}">
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tema_kegiatan_hkc" class="form-label">Tema Kegiatan:</label>
                        <input type="text" class="form-control @error('tema_kegiatan') is-invalid @enderror" name="tema_kegiatan" id="tema_kegiatan_hkc" required>
                        @error('tema_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama_kegiatan_hkc" class="form-label">Nama Kegiatan:</label>
                        <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" name="nama_kegiatan" id="nama_kegiatan_hkc" required>
                        @error('nama_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="catatan_hkc" class="form-label">Catatan:</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan_hkc"></textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="foto_hkc" class="form-label">Upload Foto (Bisa pilih lebih dari 1):</label>
                        <input type="file" class="form-control @error('foto.*') is-invalid @enderror" name="foto[]" id="foto_hkc" multiple accept="image/*">
                        <small class="form-text text-muted">Format: jpeg, png, jpg, gif, svg. Maksimal 2MB per file.</small>
                        @error('foto.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="current_fotos_preview_hkc" class="d-flex flex-wrap mt-2">
                            {{-- Foto-foto lama akan dimuat di sini oleh JS --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanLaporanHkc">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#datatableHkc').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // ====================================================================
        // FUNGSI UNTUK MODAL TAMBAH/EDIT LAPORAN KEGIATAN HKC
        // ====================================================================

        // Fungsi untuk mereset form modal ke kondisi 'Tambah'
        window.resetFormLaporanHkc = function() {
            $('#formLaporanKegiatanHkc')[0].reset();
            $('#formLaporanKegiatanHkc').attr('action', "{{ route('laporan_kegiatan.store.hkc') }}");
            $('#form_method_hkc').val('POST');
            $('#modalLaporanKegiatanHkcLabel').text('Tambah Laporan Kegiatan HKC');
            $('#laporan_id_hkc').val('');
            $('#btnSimpanLaporanHkc').text('Simpan');

            // Reset preview foto dan input file
            $('#foto_hkc').val('');
            $('#current_fotos_preview_hkc').empty();
            $('input[name="old_foto_names[]"]').remove();
            $('input[name="deleted_foto_names[]"]').remove();

            // Bersihkan pesan error validasi jika ada
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Set tanggal default ke hari ini
            $('#tanggal_hkc').val('{{ date('Y-m-d') }}');
        };

        // Ketika tombol "Tambah Laporan HKC" diklik
        window.openAddModalLaporanHkc = function() {
            resetFormLaporanHkc();
            $('#modalLaporanKegiatanHkc').modal('show');
        };

        // Ketika modal ditutup, reset form
        $('#modalLaporanKegiatanHkc').on('hidden.bs.modal', function () {
            resetFormLaporanHkc();
        });

        // Fungsi untuk membuka modal dalam mode Edit dan mengisi data via Ajax
        window.openEditModalLaporanHkc = function(id) {
            resetFormLaporanHkc(); // Reset form terlebih dahulu

            $('#modalLaporanKegiatanHkcLabel').text('Edit Laporan Kegiatan HKC');
            $('#form_method_hkc').val('PUT');
            $('#formLaporanKegiatanHkc').attr('action', `/laporan-kegiatan-daycare/hkc/${id}`); // Perhatikan rute update HKC
            $('#laporan_id_hkc').val(id);
            $('#btnSimpanLaporanHkc').text('Update Laporan');

            $.ajax({
                url: `/laporan-kegiatan-daycare/hkc/${id}/edit`, // URL untuk ambil data edit HKC
                method: 'GET',
                success: function(res) {
                    console.log("Data diterima untuk edit HKC:", res);

                    // Isi form dengan data yang diterima
                    $('#tanggal_hkc').val(res.tanggal);
                    $('#tema_kegiatan_hkc').val(res.tema_kegiatan);
                    $('#nama_kegiatan_hkc').val(res.nama_kegiatan);
                    $('#catatan_hkc').val(res.catatan);

                    let preview = $('#current_fotos_preview_hkc');
                    preview.empty(); // Kosongkan dulu
                    if (res.foto && res.foto.length > 0) {
                        $.each(res.foto, function(index, url) {
                            const fileName = res.old_foto_names[index]; // Gunakan nama file dari old_foto_names
                            preview.append(`
                                <div class="position-relative m-1 border p-1" style="width: 100px; height: 100px;">
                                    <img src="${url}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-existing-photo-hkc"
                                            style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;"
                                            data-file-name="${fileName}">
                                        <i class="fa fa-times" style="font-size: 0.7em;"></i>
                                    </button>
                                    <input type="hidden" name="old_foto_names[]" value="${fileName}">
                                </div>
                            `);
                        });
                    }

                    $('#modalLaporanKegiatanHkc').modal('show');
                },
                error: function(xhr) {
                    console.error("Error fetching HKC report data:", xhr.responseText);
                    alert("Gagal memuat data laporan HKC. Silakan coba lagi.");
                    $('#modalLaporanKegiatanHkc').modal('hide');
                }
            });
        };

        // Menangani tombol hapus foto lama HKC (AJAX)
        $(document).on('click', '.delete-existing-photo-hkc', function() {
            const button = $(this);
            const fileName = button.data('file-name');
            const parentDiv = button.closest('.position-relative');

            if (confirm('Anda yakin ingin menghapus foto ini?')) {
                $('#formLaporanKegiatanHkc').append(`<input type="hidden" name="deleted_foto_names[]" value="${fileName}">`);
                parentDiv.remove();
                $('input[name="old_foto_names[]"][value="'+fileName+'"]').remove();
            }
        });

        // Menangani submit form modal HKC via Ajax
        $('#formLaporanKegiatanHkc').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const actionUrl = form.attr('action');
            const formData = new FormData(this);

            formData.append('_method', $('#form_method_hkc').val());

            $.ajax({
                type: 'POST', // method AJAX tetap POST karena spoofing
                url: actionUrl,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalLaporanKegiatanHkc').modal('hide');
                    alert(response.message || 'Data berhasil disimpan.');
                    location.reload();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $.each(errors, function(key, value) {
                        let fieldId = key + '_hkc'; // Asumsi ID input adalah nama_field + '_hkc'
                        if ($('#' + fieldId).length) {
                            $('#' + fieldId).addClass('is-invalid').after('<div class="invalid-feedback">' + value + '</div>');
                        } else {
                            $('[name="' + key + '"], [name="' + key + '[]"]').addClass('is-invalid').after('<div class="invalid-feedback">' + value + '</div>');
                        }
                    });

                    $('#modalLaporanKegiatanHkc').modal('show');
                    alert('Terjadi kesalahan validasi. Mohon periksa input.');
                }
            });
        });

        // Menangani label custom file input saat memilih file untuk HKC
        $('#foto_hkc').on('change', function(event) {
            const files = event.target.files;
            const container = $('#current_fotos_preview_hkc');
            container.empty(); // Kosongkan preview foto lama saat ada file baru dipilih
            $('input[name="old_foto_names[]"]').remove(); // Hapus hidden input foto lama
            $('input[name="deleted_foto_names[]"]').remove(); // Hapus hidden input foto yang dihapus

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        container.append(`<div class="m-1 border p-1" style="width: 100px; height: 100px;">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>`);
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        });

        // Jika ada error validasi dari reload halaman untuk HKC
        @if ($errors->any() && (old('laporan_id_hkc') || request()->routeIs('laporan_kegiatan.store.hkc')))
            $(window).on('load', function() {
                let isEditMode = '{{ old('laporan_id_hkc') }}' !== '';

                // Isi kembali input dengan old() value jika ada error
                $('#tanggal_hkc').val('{{ old('tanggal') }}');
                $('#tema_kegiatan_hkc').val('{{ old('tema_kegiatan') }}');
                $('#nama_kegiatan_hkc').val('{{ old('nama_kegiatan') }}');
                $('#catatan_hkc').val('{{ old('catatan') }}');
                $('#laporan_id_hkc').val('{{ old('laporan_id_hkc') }}');

                // Set mode form dan URL action
                if (isEditMode) {
                    $('#modalLaporanKegiatanHkcLabel').text('Edit Laporan Kegiatan HKC');
                    $('#formLaporanKegiatanHkc').attr('action', '{{ url('laporan-kegiatan-daycare/hkc') }}/' + '{{ old('laporan_id_hkc') }}');
                    $('#form_method_hkc').val('PUT');
                    $('#btnSimpanLaporanHkc').text('Update Laporan');

                    // Mengisi kembali foto lama (jika ada error validasi setelah edit submit)
                    let oldFotoNamesOnValidationError = [];
                    @if(is_array(old('old_foto_names')))
                        oldFotoNamesOnValidationError = {!! json_encode(old('old_foto_names')) !!};
                    @endif

                    $('#current_fotos_preview_hkc').empty();
                    if (oldFotoNamesOnValidationError.length > 0) {
                        $.each(oldFotoNamesOnValidationError, function(index, fileName) {
                            const url = '{{ asset('uploads/laporankegiatanhkc/') }}' + '/' + fileName;
                            $('#current_fotos_preview_hkc').append(`
                                <div class="position-relative m-1 border p-1" style="width: 100px; height: 100px;">
                                    <img src="${url}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute delete-existing-photo-hkc"
                                            style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;"
                                            data-file-name="${fileName}">
                                        <i class="fa fa-times" style="font-size: 0.7em;"></i>
                                    </button>
                                    <input type="hidden" name="old_foto_names[]" value="${fileName}">
                                </div>
                            `);
                        });
                    }
                } else {
                    // Jika ini bukan mode edit, pastikan form direset ke mode tambah
                    resetFormLaporanHkc();
                }
                
                $('#modalLaporanKegiatanHkc').modal('show'); // Tampilkan modal
            });
        @endif
    });
</script>
@endpush
