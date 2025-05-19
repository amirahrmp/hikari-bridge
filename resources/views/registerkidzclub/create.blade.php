@extends('layouts2.master')

@section('register_kidz_club_select','active')
@section('title', 'Registrasi Hikari Kidz Club')

@section('content')

<br><br><br>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Formulir Pendaftaran Siswa Baru Hikari Kidz Club</h2>
            <form action="{{ route('registerkidzclub.store') }}"
      method="POST"
      enctype="multipart/form-data">
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
    </div>

    <!-- Nickname -->
    <div class="mb-3">
      <label for="nickname" class="form-label"style="color: black;">Nama Panggilan</label>
      <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Masukkan nama panggilan" required maxlength="255">
    </div>

    <!-- Birth Date -->
    <div class="mb-3">
      <label for="birth_date" class="form-label"style="color: black;">Tanggal Lahir</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date" required>
    </div>

    <!-- Upload File (misalnya Kartu Identitas Anak atau Foto) -->
    <div class="mb-3">
      <label for="file_upload" class="form-label" style="color: black;">Upload Foto Anak</label>
      <input type="file" class="form-control" id="file_upload" name="file_upload" accept=".jpg,.jpeg,.png" required>
      <small class="form-text text-muted">Format: JPG atau PNG. Maksimal 2MB.</small>
    </div>

    <!-- Parent Name -->
    <div class="mb-3">
      <label for="parent_name" class="form-label"style="color: black;">Nama Orang Tua</label>
      <input type="text" class="form-control" id="parent_name" name="parent_name" placeholder="Masukkan nama orang tua" required maxlength="255">
    </div>

    <!-- WhatsApp Number -->
    <div class="mb-3">
      <label for="whatsapp_number" class="form-label"style="color: black;">Nomor WhatsApp</label>
      <input type="number" class="form-control" id="whatsapp_number" name="whatsapp_number" placeholder="Masukkan nomor WhatsApp" required maxlength="15">
    </div>

    <!-- Address -->
    <div class="mb-3">
      <label for="address" class="form-label"style="color: black;">Alamat</label>
      <textarea class="form-control" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap" required></textarea>
    </div>

    <!-- Agama -->
    <div class="form-group">
      <label for="agama"style="color: black;">Agama</label>
      <div class="form-check">
        <input type="radio" name="agama" id="islam" value="islam" required>
        <label class="form-check-label" for="islam"style="color: black;">Islam</label>
      </div>
      <div class="form-check">
        <input type="radio" name="agama" id="kristen" value="kristen" required>
        <label class="form-check-label" for="kristen"style="color: black;">Kristen</label>
      </div>
      <div class="form-check">
        <input type="radio" name="agama" id="hindu" value="hindu" required>
        <label class="form-check-label" for="hindu"style="color: black;">Hindu</label>
      </div>
      <div class="form-check">
        <input type="radio" name="agama" id="budha" value="budha" required>
        <label class="form-check-label" for="budha"style="color: black;">Budha</label>
      </div>
      <div class="form-check">
        <input type="radio" name="agama" id="konghucu" value="konghucu" required>
        <label class="form-check-label" for="konghucu"style="color: black;">Konghucu</label>
      </div>
      <div class="invalid-feedback"style="color: black;">Pilih salah satu agama.</div>
    </div>
    

    <!-- non muslim -->
    <div class="form-group">
      <label for="nonmuslim"style="color: black;"> Bagi calon siswa Non Muslim dapat memilih program berikut</label>
        <div class="form-check">
          <input type="radio" name="nonmuslim" id="paket1" value="paket1">
          <label class="form-check-label" for="paket1"style="color: black;">1 paket : English</label>
        </div>
        <div class="form-check">
          <input type="radio" name="nonmuslim" id="paket2" value="paket2">
          <label class="form-check-label" for="paket2"style="color: black;">2 paket : English & Kreativitas & Motorik</label>
        </div>
    </div>

    <!-- member -->
    <div class="form-group">
      <label for="member"style="color: black;">Pilihan Tipe Member</label>
        <div class="form-check">
          <input type="radio" name="member" id="tetap" value="tetap">
          <label class="form-check-label" for="tetap"style="color: black;">Member Tetap</label>
        </div>
        <div class="form-check">
          <input type="radio" name="member" id="harian" value="harian">
          <label class="form-check-label" for="harian"style="color: black;">Member Harian</label>
        </div>
    </div>

    <!-- kelas -->
    <div class="form-group">
      <label for="kelas"style="color: black;">Kelas</label>
        <div class="form-check">
          <input type="radio" name="kelas" id="himawari" value="himawari">
          <label class="form-check-label" for="himawari"style="color: black;">Himawari (4 tahun)</label>
        </div>
        <div class="form-check">
          <input type="radio" name="kelas" id="sakura" value="sakura">
          <label class="form-check-label" for="sakura"style="color: black;">Sakura (3 tahun)</label>
        </div>
        <div class="form-check">
          <input type="radio" name="kelas" id="bara" value="bara">
          <label class="form-check-label" for="bara"style="color: black;">Bara (2 tahun)</label>
        </div>
    </div>

    <!-- Sumber Informasi Program HKC -->
    <div class="form-group">
      <label for="sumberinfo"style="color: black;">Sumber Informasi Program HKC</label>
      <div class="form-check">
        <input type="radio" name="information_source" id="website" value="website">
        <label class="form-check-label" for="website"style="color: black;">
          Website <a href="https://www.hikaribridge.com//" target="_blank">www.hikari-bridge.com</a>
          </label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="facebook" value="facebook">
        <label class="form-check-label" for="facebook"style="color: black;">Facebook</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="instagram" value="instagram">
        <label class="form-check-label" for="instagram"style="color: black;">Instagram</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="whatsapp" value="whatsapp">
        <label class="form-check-label" for="whatsapp"style="color: black;">Whatsapp</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="kantor" value="kantor">
        <label class="form-check-label" for="kantor"style="color: black;">Kantor HB</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="spanduk" value="spanduk">
        <label class="form-check-label" for="spanduk"style="color: black;">Spanduk</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="brosur" value="brosur">
        <label class="form-check-label" for="brosur"style="color: black;">Brosur</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="tetangga" value="tetangga">
        <label class="form-check-label" for="tetangga"style="color: black;">Tetangga</label>
      </div>
      <div class="form-check">
        <input type="radio" name="information_source" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" id="information_source_other" name="information_source_other" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini">
        </label>
      </div>
    </div>

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