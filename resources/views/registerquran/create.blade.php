@extends('layouts2.master')

@section('register_quran_select','active')
@section('title', 'Registrasi Hikari Quran')

@section('content')

<br><br><br>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Formulir Pendaftaran Siswa Baru Hikari Quran</h2>
            <div class="form-description">Quran di Hatiku, Cahaya dalam Hidupku<p>Selamat Datang Siswa Baru Hikari Kidz Club, silahkan untuk mengisi formulir pendaftaran.</p></div>
            <form id="registrationForm" action="{{ route('registerquran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 
                
                <!-- Alert untuk pesan sukses -->
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif


    <!-- Full Name -->
    <div class="mb-3">
      <label for="full_name" class="form-label"style="color: black;">Nama Lengkap</label>
      <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" required maxlength="255">
      <div class="error" id="error-full_name"></div>
    </div>

    <!-- NickName -->
    <div class="mb-3">
      <label for="nickname" class="form-label"style="color: black;">Nama Panggilan</label>
      <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Masukkan nama panggilan" required maxlength="255">
      <div class="error" id="error-nickname"></div>
    </div>


    <!-- Birth Date -->
    <div class="mb-3">
      <label for="birth_date" class="form-label"style="color: black;">Tanggal Lahir</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date" required>
      <div class="error" id="error-birth_date"></div>
    </div>

    <!-- Upload File (misalnya Kartu Identitas Anak atau Foto) -->
    <div class="mb-3">
      <label for="file_upload" class="form-label" style="color: black;">Upload Foto Anak</label>
      <input type="file" class="form-control" id="file_upload" name="file_upload" accept=".jpg,.jpeg,.png" required>
      <small class="form-text text-muted">Format: JPG atau PNG. Maksimal 2MB.</small>
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

    <!-- Pilihan Kelas (Radio Button) -->
    <div class="form-group">
    <label for="kelas" style="color: black;">Pilihan Kelas</label><br>
    @foreach ($pakethq as $p)
        <div class="form-check">
            <input class="" type="radio" name="kelas" id="kids{{ $p->id_pakethq }}" value="{{ $p->id_pakethq }}">
            <label class="form-check-label" for="kids{{ $p->id_pakethq }}">
                {{ $p->kelas }}
            </label>
        </div>
    @endforeach
    </div>

    <!-- tipe -->
    <div class="form-group">
      <label for="tipe"style="color: black;">Tipe Kelas</label>
        <div class="form-check">
          <input type="radio" name="tipe" id="online" value="online">
          <label class="form-check-label" for="online"style="color: black;">Online</label>
        </div>
        <div class="form-check">
          <input type="radio" name="tipe" id="offline" value="offline">
          <label class="form-check-label" for="offline"style="color: black;">Offline</label>
        </div>
    </div>
    <br> 

    <!-- Sumber Informasi Program HKC -->
    <div class="form-group">
      <label for="sumberinfo"style="color: black;">Sumber Informasi Program Hikari Quran</label>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="facebook" value="facebook">
        <label class="form-check-label" for="facebook"style="color: black;">Facebook</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="instagram" value="instagram">
        <label class="form-check-label" for="instagram"style="color: black;">
            IG <a href="https://www.instagram.com/hikarikidz.club/" target="_blank">hikarikidz.club</a>
        </label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="whatsapp" value="whatsapp">
        <label class="form-check-label" for="whatsapp"style="color: black;">Whatsapp</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="teman" value="teman">
        <label class="form-check-label" for="teman"style="color: black;">Teman/Keluarga</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="kantor" value="kantor">
        <label class="form-check-label" for="kantor"style="color: black;">Kantor HB (Dewadaru Residence, R-4 Cikoneng, Bojongsoang, Kab. Bandung)</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="spanduk" value="spanduk">
        <label class="form-check-label" for="spanduk"style="color: black;">Spanduk</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="brosur" value="brosur">
        <label class="form-check-label" for="brosur"style="color: black;">Brosur/Selembaran</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="tetangga" value="tetangga">
        <label class="form-check-label" for="tetangga"style="color: black;">Tetangga</label>
      </div>
      <div class="form-check">
        <input type="radio" name="sumberinfo" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" id="sumberinfo_other" name="sumberinfo_other" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini">
        </label>
      </div>
    </div>
    <br>

    <!-- promotor -->
    <div class="mb-3">
      <label for="promotor" class="form-label"style="color: black;"><i>Mengetahui dari Promotor a.n Nama ?</i><p>Jika calon siswa tidak mendaftar melalui Promotor, silahkan ketik tanda hubung (-) / Tidak.</p></label>
      <input type="text" class="form-control" id="promotor" name="promotor" placeholder="Masukkan nama" required maxlength="20">
      <div class="error" id="error-promotor"></div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Kirim</button>
    </div>
  </form>
</div>


@endsection