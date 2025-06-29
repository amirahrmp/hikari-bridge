@extends('layouts.master')

@section('jam_pulang_select', 'active')
@section('title', 'Jam Pulang Daycare')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid"><h1>Input Jam Pulang</h1></div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="jamPulangForm" action="{{ route('absensi_daycare.store_jam_pulang') }}" method="POST">
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
                        </div>

                        <div class="form-group">
                            <label>Jam Pulang</label>
                            <input type="time" name="jam_pulang" id="jam_pulang" class="form-control" required>
                            <small id="jamDatangWarning" class="form-text text-danger d-none mt-1">
                                Jam datang belum diinputkan untuk anak ini. Silakan isi jam datang terlebih dahulu.
                            </small>
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
    document.getElementById('jamPulangForm').addEventListener('submit', async function (e) {
        e.preventDefault(); // hentikan submit default

        const idAnak = document.getElementById('id_anak').value;
        const warning = document.getElementById('jamDatangWarning');

        if (!idAnak) return; // biarkan HTML5 validasi 'required'

        try {
            const response = await fetch(`/cek-jam-datang/${idAnak}`);
            const result = await response.json();

            if (!result.jam_datang_terisi) {
                warning.classList.remove('d-none');
                return; // stop submit jika jam datang belum diisi
            }

            warning.classList.add('d-none'); // sembunyikan warning jika valid
            this.submit(); // submit form setelah valid
        } catch (error) {
            console.error('Gagal validasi jam datang:', error);
            alert('Terjadi kesalahan saat memeriksa jam datang.');
        }
    });
</script>
@endsection
