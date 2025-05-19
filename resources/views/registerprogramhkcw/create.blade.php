@extends('layouts2.master')

@section('register_hkcw_select','active')
@section('title', 'Registrasi Program Lain HKC Weekend')

@section('content')

<br><br><br>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Formulir Pendaftaran Program HKC Weekend</h2>
            <div class="form-description">Program Kegiatan Hikari Kidz Club yang diadakan pada Weekend tertentu sebagai tempat belajar juga bermain.</p></div>
            <form id="registrationForm" action="{{ route('registerprogramhkcw.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 
                
                <!-- Alert untuk pesan sukses -->
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

  <!-- nama Kegiatan -->
    <div class="mb-3">
      <label for="nama_kegiatan" class="form-label"style="color: black;">Nama Kegiatan</label>
      <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" placeholder="Masukkan nama kegiatan" required maxlength="255">
      <div class="error" id="error-nama_kegiatan"></div>
    </div>

    <!-- Full Name -->
    <div class="mb-3">
      <label for="full_name" class="form-label"style="color: black;">Nama Lengkap</label>
      <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" required maxlength="255">
      <div class="error" id="error-full_name"></div>
    </div>

    <!-- Birth Date -->
    <div class="mb-3">
      <label for="nama_panggilan" class="form-label"style="color: black;">Nama Panggilan</label>
      <input type="text" class="form-control" id="nama_panggilan" name="nama_panggilan" placeholder="Masukkan nama panggilan" required maxlength="255">
      <div class="error" id="error-nama_panggilan"></div>
    </div>

    <!-- Parent Name -->
    <div class="mb-3">
      <label for="parent_name" class="form-label"style="color: black;">Nama Orang Tua (Ayah & Ibu)</label>
      <input type="text" class="form-control" id="parent_name" name="parent_name" placeholder="Masukkan nama orang tua" required maxlength="255">
      <div class="error" id="error-parent_name"></div>
    </div>

    <!-- WhatsApp Number -->
    <div class="mb-3">
      <label for="whatsapp_number" class="form-label"style="color: black;">Nomor WhatsApp</label>
      <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" placeholder="Masukkan nomor WhatsApp" required maxlength="15">
      <div class="error" id="error-whatsapp_number"></div>
    </div>

    <!-- Address -->
    <div class="mb-3">
      <label for="address" class="form-label"style="color: black;">Alamat</label>
      <textarea class="form-control" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap" required></textarea>
      <div class="error" id="error-address"></div>
    </div>

    <!-- Pilihan Tipe Kelas (Radio Button) -->
    <div class="form-group">
        <label for="kelas" style="color: black;">Pilihan Tipe kelas</label><br>
        @foreach ($pakethkc->unique('kelas') as $p)
            <div class="form-check">
                <input type="radio" name="kelas" id="kelas{{ $p->kelas }}" value="{{ $p->kelas }}">
                <label class="form-check-label" for="kelas{{ $p->kelas }}">
                    {{ ucfirst($p->kelas) }}
                </label>
            </div>
        @endforeach
    </div>

    <!-- Pilihan Tipe Member (Radio Button) -->
    <div class="form-group">
        <label for="tipe" style="color: black;">Pilihan Tipe</label><br>
        @foreach ($pakethkc->unique('tipe') as $p)
            <div class="form-check">
                <input type="radio" name="tipe" id="tipe{{ $p->tipe }}" value="{{ $p->tipe }}">
                <label class="form-check-label" for="tipe{{ $p->tipe }}">
                    {{ ucfirst($p->tipe) }}
                </label>
            </div>
        @endforeach
    </div>

    <!-- Upload File bukti bayar -->
    <div class="mb-3">
      <label for="bukti_bayar" class="form-label" style="color: black;">Upload Bukti Bayar Pendaftaran</label>
      <input type="file" class="form-control" id="bukti_bayar" name="bukti_bayar" accept=".jpg,.jpeg,.png" required>
      <small class="form-text text-muted">Format: JPG atau PNG. Maksimal 2MB.</small>
    </div>

    <br> 

    <!-- Submit Button -->
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Kirim</button>
    </div>
  </form>
</div>


@endsection