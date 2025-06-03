@extends('layouts2.master')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Form Pembayaran</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <h5>Data Peserta</h5>
        <p><strong>Nama:</strong> {{ $peserta->full_name ?? ($peserta->name ?? '-') }}</p>
        <p><strong>Jenis Pendaftaran:</strong> {{ $registration_type }}</p>
    </div>

    <div class="mb-4">
        <h5>Data Paket</h5>
        <p><strong>Nama Paket:</strong>
            {{ $paket->nama_paket ?? $paket->member ?? $paket->kelas ?? '-' }}
        </p>
    </div>

    @php
        $komponenList = [];
        if ($registration_type === 'Hikari Kidz Daycare') {
            $komponenList = [
                'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                'Uang Pangkal' => $paket->u_pangkal ?? 0,
                'SPP Bulanan' => $paket->u_spp ?? 0,
                'Uang Makan' => $paket->u_makan ?? 0,
            ];
        } elseif ($registration_type === 'Hikari Kidz Club') {
            $komponenList = [
                'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                'Uang Perlengkapan' => $paket->u_perlengkapan ?? 0,
                'Uang Sarana' => $paket->u_sarana ?? 0,
                'SPP Bulanan' => $paket->u_spp ?? 0,
            ];
        } elseif ($registration_type === 'Hikari Quran') {
            $komponenList = [
                'Uang Pendaftaran' => $paket->u_pendaftaran ?? 0,
                'Uang Modul' => $paket->u_modul ?? 0,
                'SPP Bulanan' => $paket->u_spp ?? 0,
            ];
        }
    @endphp

    <form method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="registration_id" value="{{ $registration_id }}">
        <input type="hidden" name="registration_type" value="{{ $registration_type }}">

        <div class="form-group mb-3">
            <label>Komponen Pembayaran <small>(Uang Pendaftaran wajib dipilih)</small></label><br>

            @foreach ($komponenList as $komponen => $nominal)
                @if($komponen === 'Uang Pendaftaran')
                    <input type="checkbox" name="komponen[]" value="{{ $komponen }}" id="komponen_{{ \Str::slug($komponen) }}" checked disabled>
                    <input type="hidden" name="komponen[]" value="{{ $komponen }}">
                    <label for="komponen_{{ \Str::slug($komponen) }}">{{ $komponen }}</label><br>
                @elseif($nominal > 0)
                    <input type="checkbox" name="komponen[]" value="{{ $komponen }}" id="komponen_{{ \Str::slug($komponen) }}">
                    <label for="komponen_{{ \Str::slug($komponen) }}">{{ $komponen }}</label><br>
                @endif
            @endforeach
        </div>

        <div class="form-group mb-3">
            <label for="jumlah">Total Jumlah (Rp)</label>
            <input type="text" id="jumlah" name="jumlah" class="form-control" readonly>
        </div>

        <div class="mb-3">
      <label for="bukti_transfer" class="form-label" style="color: black;">Upload Bukti Pembayaran</label>
      <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer="accept=".jpg,.jpeg,.png" required>
      
    </div>

        <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
    </form>
</div>

<script>
    const komponenCheckboxes = document.querySelectorAll('input[name="komponen[]"]');
    const jumlahField = document.getElementById('jumlah');
    const nominalKomponen = @json($komponenList);

    function hitungJumlah() {
        let total = 0;
        komponenCheckboxes.forEach(chk => {
            if (chk.checked) {
                total += Number(nominalKomponen[chk.value]) || 0;
            }
        });
        jumlahField.value = total > 0 ? total.toLocaleString('id-ID') : '';
    }

    komponenCheckboxes.forEach(chk => {
        chk.addEventListener('change', hitungJumlah);
    });

    window.onload = hitungJumlah;
</script>

@endsection
