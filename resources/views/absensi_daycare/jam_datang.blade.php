@extends('layouts.master')

@section('jam_datang_select', 'active')
@section('title', 'Jam Datang Daycare')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid"><h1>Input Jam Datang</h1></div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="jamDatangForm" action="{{ route('absensi_daycare.store_jam_datang') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Anak</label>
                            <select name="id_anak" id="id_anak" class="form-control" required>
                                <option value="">-- Pilih Anak --</option>
                                @foreach($peserta as $p)
                                    <option value="{{ $p->id_anak }}">{{ $p->full_name }}</option>
                                @endforeach
                            </select>
                            <small id="anakError" class="form-text text-danger d-none">Silakan pilih anak terlebih dahulu.</small>
                        </div>

                        <div class="form-group">
                            <label>Jam Datang</label>
                            <input type="time" name="jam_datang" class="form-control" required>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    // Validasi client-side saat form disubmit
    document.getElementById('jamDatangForm').addEventListener('submit', function (e) {
        let idAnak = document.getElementById('id_anak').value;
        let anakError = document.getElementById('anakError');

        if (!idAnak) {
            e.preventDefault(); // hentikan pengiriman form
            anakError.classList.remove('d-none'); // tampilkan pesan error
            document.getElementById('id_anak').classList.add('is-invalid');
        } else {
            anakError.classList.add('d-none');
            document.getElementById('id_anak').classList.remove('is-invalid');
        }
    });
</script>
@endsection
