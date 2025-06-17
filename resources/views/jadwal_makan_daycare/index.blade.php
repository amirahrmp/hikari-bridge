@extends('layouts.master')

@section('jadwal_makan_daycare_select','active')
@section('title', 'Jadwal Makan Daycare')

@push('styles')
<style>
    .readonly-input {
        background: #f1f1f1 !important;
        cursor: not-allowed;
    }
    /* Hapus gaya .meal-input-container, .hidden-meal-input, .libur-placeholder
       karena kita tidak lagi menyembunyikan input */
</style>
@endpush

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Jadwal Makan Daycare</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Jadwal Makan Daycare</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-12">
                    <div class="p-3 bg-white border shadow-sm">

                        {{-- BUTTON TAMBAH --}}
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
                            <i class="fa fa-plus"></i> Tambah Jadwal Pekanan
                        </button>

                        {{-- TABLE --}}
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>No</th><th>Bulan</th><th>Pekan</th><th>Hari</th>
                                        <th>Snack Pagi</th><th>Makan Siang</th><th>Snack Sore</th><th width="18%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwal_makan_daycare as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ DateTime::createFromFormat('!m',$p->bulan)->format('F') }}</td>
                                        <td>Pekan {{ $p->pekan }}</td>
                                        <td>{{ $p->hari }}</td>
                                        {{-- Tampilkan "LIBUR" jika is_libur true, atau nilai sebenarnya --}}
                                        <td>{{ $p->is_libur ? 'LIBUR' : ($p->snack_pagi ?? '-') }}</td>
                                        <td>{{ $p->is_libur ? 'LIBUR' : ($p->makan_siang ?? '-') }}</td>
                                        <td>{{ $p->is_libur ? 'LIBUR' : ($p->snack_sore ?? '-') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{ $p->id }}"><i class="fa fa-edit"></i></button>
                                            @if($p->hari === 'Senin')
                                            <form class="d-inline" action="{{ route('jadwal_makan_daycare.deleteByPeriode') }}" method="POST"
                                                  onsubmit="return confirm('Hapus semua jadwal Pekan {{ $p->pekan }} bulan {{ DateTime::createFromFormat('!m',$p->bulan)->format('F') }}?')">
                                                @csrf
                                                <input type="hidden" name="bulan" value="{{ $p->bulan }}">
                                                <input type="hidden" name="pekan" value="{{ $p->pekan }}">
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus Pekan</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- MODAL EDIT (tidak berubah) --}}
                                    <div class="modal fade" id="edit{{ $p->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <form action="{{ route('jadwal_makan_daycare.update', $p->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Jadwal: {{ $p->hari }} - Pekan {{ $p->pekan }} - {{ DateTime::createFromFormat('!m',$p->bulan)->format('F') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Hari</label>
                                                            <input type="text" name="hari" value="{{ $p->hari }}" class="form-control" required readonly>
                                                        </div>

                                                        {{-- Checkbox Hari Libur untuk modal EDIT --}}
                                                        <div class="form-group text-center">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input libur-edit" id="liburEditSwitch{{ $p->id }}" data-id="{{ $p->id }}" {{ $p->is_libur ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="liburEditSwitch{{ $p->id }}">Hari Libur</label>
                                                                <input type="hidden" name="is_libur" id="liburEdit{{ $p->id }}" value="{{ $p->is_libur ? 1 : 0 }}">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Snack Pagi</label>
                                                            <input type="text" name="snack_pagi" value="{{ $p->snack_pagi }}" id="pagiEdit{{ $p->id }}"
                                                                class="form-control meal-input-edit {{ $p->is_libur ? 'readonly-input' : '' }}" {{ $p->is_libur ? 'readonly' : '' }}>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Makan Siang</label>
                                                            <input type="text" name="makan_siang" value="{{ $p->makan_siang }}" id="siangEdit{{ $p->id }}"
                                                                class="form-control meal-input-edit {{ $p->is_libur ? 'readonly-input' : '' }}" {{ $p->is_libur ? 'readonly' : '' }}>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Snack Sore</label>
                                                            <input type="text" name="snack_sore" value="{{ $p->snack_sore }}" id="soreEdit{{ $p->id }}"
                                                                class="form-control meal-input-edit {{ $p->is_libur ? 'readonly-input' : '' }}" {{ $p->is_libur ? 'readonly' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- MODAL TAMBAH PEKAN --}}
                        <div class="modal fade" id="addModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('jadwal_makan_daycare.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tambah Jadwal Pekanan</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Bulan</label>
                                                    <select name="bulan" class="form-control" required>
                                                        @foreach(range(1,12) as $m)
                                                        <option value="{{ $m }}">{{ DateTime::createFromFormat('!m',$m)->format('F') }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Pekan</label>
                                                    <select name="pekan" class="form-control" required>
                                                        @foreach(range(1,4) as $w)
                                                        <option value="{{ $w }}">Pekan {{ $w }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <table class="table mt-3">
                                                <thead>
                                                    <tr><th>Hari</th><th>Status</th><th>Snack Pagi</th><th>Makan Siang</th><th>Snack Sore</th></tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $i=>$hari)
                                                    <tr>
                                                        <td><input type="hidden" name="data[{{ $i }}][hari]" value="{{ $hari }}">{{ $hari }}</td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input libur-add" id="liburAddSwitch{{ $i }}" data-i="{{ $i }}" data-hari="{{ $hari }}">
                                                                <label class="custom-control-label" for="liburAddSwitch{{ $i }}">Hari Libur</label>
                                                                <input type="hidden" name="data[{{ $i }}][is_libur]" id="isLiburAdd{{ $i }}" value="0">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="data[{{ $i }}][snack_pagi]" id="pagiAdd{{ $i }}" class="form-control meal-input-add">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="data[{{ $i }}][makan_siang]" id="siangAdd{{ $i }}" class="form-control meal-input-add">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="data[{{ $i }}][snack_sore]" id="soreAdd{{ $i }}" class="form-control meal-input-add">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-primary">Simpan Jadwal</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- END MODAL --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Hari-hari yang secara default dianggap libur (misal: Sabtu dan Minggu)
    const defaultHariLibur = ['Sabtu', 'Minggu'];

    // --- Logika untuk Modal TAMBAH (AddModal) ---
    function handleLiburAdd(i, hari, isChecked, userInput = false) {
        const pagiInput = $(`#pagiAdd${i}`);
        const siangInput = $(`#siangAdd${i}`);
        const soreInput = $(`#soreAdd${i}`);
        const isLiburHiddenInput = $(`#isLiburAdd${i}`);

        if (isChecked) {
            // Ketika dicentang (Hari Libur):
            // 1. Set nilai input ke 'LIBUR'
            // 2. Buat input jadi readonly
            // 3. Tambahkan kelas 'readonly-input' untuk styling
            pagiInput.val('LIBUR').prop('readonly', true).addClass('readonly-input');
            siangInput.val('LIBUR').prop('readonly', true).addClass('readonly-input');
            soreInput.val('LIBUR').prop('readonly', true).addClass('readonly-input');
            isLiburHiddenInput.val(1); // Set hidden input is_libur ke 1
        } else {
            // Ketika tidak dicentang (Bukan Hari Libur):
            // 1. Hapus properti readonly
            // 2. Hapus kelas 'readonly-input'
            // 3. Jika nilai input saat ini adalah 'LIBUR', maka kosongkan.
            //    Jika bukan 'LIBUR' (berarti sebelumnya sudah diisi manual), biarkan nilainya.
            pagiInput.prop('readonly', false).removeClass('readonly-input');
            siangInput.prop('readonly', false).removeClass('readonly-input');
            soreInput.prop('readonly', false).removeClass('readonly-input');
            
            if (pagiInput.val() === 'LIBUR') pagiInput.val('');
            if (siangInput.val() === 'LIBUR') siangInput.val('');
            if (soreInput.val() === 'LIBUR') soreInput.val('');

            isLiburHiddenInput.val(0); // Set hidden input is_libur ke 0
        }
    }

    // Event listener untuk setiap checkbox Hari Libur di modal TAMBAH
    $('.libur-add').on('change', function () {
        const i = $(this).data('i');
        const hari = $(this).data('hari');
        const isChecked = $(this).is(':checked');
        handleLiburAdd(i, hari, isChecked, true); // True karena ini perubahan dari user
    });

    // Inisialisasi status hari libur saat modal TAMBAH dibuka
    $('#addModal').on('shown.bs.modal', function () {
        $('.libur-add').each(function () {
            const i = $(this).data('i');
            const hari = $(this).data('hari');
            // Cek apakah hari tersebut defaultnya libur
            const isChecked = defaultHariLibur.includes(hari);
            
            // Set checkbox state
            $(this).prop('checked', isChecked);
            // Panggil handler untuk mengatur readonly/value awal
            handleLiburAdd(i, hari, isChecked, false); // False karena ini inisialisasi, bukan input user
        });
    });

    // Reset AddModal form ketika disembunyikan
    $('#addModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); // Reset form fields

        $('.libur-add').each(function () {
            const i = $(this).data('i');
            const hari = $(this).data('hari');
            // Reset checkbox state berdasarkan hari libur default
            const isChecked = defaultHariLibur.includes(hari);
            $(this).prop('checked', isChecked);
            // Panggil handler untuk mengembalikan kondisi awal input (readonly/editable)
            handleLiburAdd(i, hari, isChecked, false); // False karena ini proses reset
        });
    });


    // --- Logika untuk Modal EDIT (Tidak berubah, karena sudah sesuai) ---
    $(document).on('change', '.libur-edit', function () {
        const id = $(this).data('id');
        const isLibur = $(this).is(':checked');
        const value = isLibur ? 'LIBUR' : '';

        const pagiInput = $(`#pagiEdit${id}`);
        const siangInput = $(`#siangEdit${id}`);
        const soreInput = $(`#soreEdit${id}`);

        if (isLibur) {
            // Simpan nilai asli sebelum mengubahnya menjadi 'LIBUR'
            pagiInput.data('original-value', pagiInput.val());
            siangInput.data('original-value', siangInput.val());
            soreInput.data('original-value', soreInput.val());

            pagiInput.val(value).prop('readonly', true).addClass('readonly-input');
            siangInput.val(value).prop('readonly', true).addClass('readonly-input');
            soreInput.val(value).prop('readonly', true).addClass('readonly-input');
        } else {
            // Kembalikan nilai asli jika ada, atau kosongkan jika tidak
            pagiInput.val(pagiInput.data('original-value') || '').prop('readonly', false).removeClass('readonly-input');
            siangInput.val(siangInput.data('original-value') || '').prop('readonly', false).removeClass('readonly-input');
            soreInput.val(soreInput.data('original-value') || '').prop('readonly', false).removeClass('readonly-input');
        }

        $(`#liburEdit${id}`).val(isLibur ? 1 : 0);
    });

    // Inisialisasi status readonly/value saat modal edit dibuka
    $(document).on('shown.bs.modal', '[id^="edit"]', function () {
        const modalId = $(this).attr('id');
        const id = modalId.replace('edit', '');

        const isLibur = $(`#liburEditSwitch${id}`).is(':checked');
        const pagiInput = $(`#pagiEdit${id}`);
        const siangInput = $(`#siangEdit${id}`);
        const soreInput = $(`#soreEdit${id}`);

        if (isLibur) {
            // Simpan nilai asli saat modal dibuka jika is_libur true, untuk berjaga-jaga jika kemudian di-uncheck
            pagiInput.data('original-value', pagiInput.val() === 'LIBUR' ? '' : pagiInput.val());
            siangInput.data('original-value', siangInput.val() === 'LIBUR' ? '' : siangInput.val());
            soreInput.data('original-value', soreInput.val() === 'LIBUR' ? '' : soreInput.val());

            pagiInput.val('LIBUR').prop('readonly', true).addClass('readonly-input');
            siangInput.val('LIBUR').prop('readonly', true).addClass('readonly-input');
            soreInput.val('LIBUR').prop('readonly', true).addClass('readonly-input');
        } else {
            pagiInput.prop('readonly', false).removeClass('readonly-input');
            siangInput.prop('readonly', false).removeClass('readonly-input');
            soreInput.prop('readonly', false).removeClass('readonly-input');
            // Nilai input akan diisi dari value="{{ $p->snack_pagi }}" di blade secara default
        }
    });
});
</script>
@endpush