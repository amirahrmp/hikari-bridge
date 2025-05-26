@extends('layouts.master')

@section('jam_pulang_select', 'active')
@section('title', 'Jam Pulang Daycare')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid"><h1>Input Jam pulang</h1></div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('absensi_daycare.store_jam_pulang') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Anak</label>
                            <select name="id_anak" id="id_anak" class="form-control" onchange="getProgram()">
                                <option value="">-- Pilih Anak --</option>
                                @foreach($peserta as $p)
                                    <option value="{{ $p->id_anak }}">{{ $p->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jam Pulang</label>
                            <input type="time" name="jam_pulang" class="form-control" required>
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
document.getElementById('id_anak').addEventListener('change', function () {
    let idAnak = this.value;
    if (idAnak) {
        fetch(`/get-program-anak/${idAnak}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('program').value = data.nama_program || 'Tidak ditemukan';
            })
            .catch(error => {
                console.error(error);
                document.getElementById('program').value = 'Gagal mengambil data';
            });
    } else {
        document.getElementById('program').value = '';
    }
});
</script>
@endsection
