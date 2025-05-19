@extends('layouts2.master')

@section('register_kidz_daycare_select','active')
@section('title', 'Registrasi Hikari Kidz Daycare')

@section('content')

<br>
<br>
<br>



<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Form Pendaftaran Hikari Kidz Daycare</h2>
            <form action="{{ route('registerkidzdaycare.store') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            
        <!-- Alert untuk pesan sukses -->
        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif




    <!-- Full Name -->
    <div class="mb-3">
      <label for="full_name" class="form-label" style="color: black;">Nama Lengkap Calon Siswa</label>
      <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" maxlength="255">
    </div>

    <!-- Nickname -->
    <div class="mb-3">
      <label for="nickname" class="form-label" style="color: black;">Nama Panggilan Calon Siswa <p><i>Nama panggilan/sapaan Ananda di rumah?</i></p></label>
      <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Masukkan nama panggilan" maxlength="255">
    </div>

    <!-- Birth Date -->
    <div class="mb-3">
      <label for="birth_date" class="form-label" style="color: black;">Tanggal Lahir Calon Siswa</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date">
    </div>

    <!-- Upload File (misalnya Kartu Identitas Anak atau Foto) -->
    <div class="mb-3">
      <label for="file_upload" class="form-label" style="color: black;">Upload Foto Anak</label>
      <input type="file" class="form-control" id="file_upload" name="file_upload" accept=".jpg,.jpeg,.png" required>
      <small class="form-text text-muted">Format: JPG atau PNG. Maksimal 2MB.</small>
    </div>

    <!-- Child Order -->
    <div class="mb-3">
      <label for="child_order" class="form-label" style="color: black;">Anak ke-berapa</label>
      <input type="number" class="form-control" id="child_order" name="child_order" placeholder="Masukkan Anak ke berapa">
    </div>

    <!-- Siblings Count -->
    <div class="mb-3">
      <label for="siblings_count" class="form-label" style="color: black;">Jumlah Saudara</label>
      <input type="number" class="form-control" id="siblings_count" name="siblings_count" placeholder="Masukkan Jumlah Saudara">
    </div>

    <!-- Tinggi Badan -->
    <div class="mb-3">
      <label for="height_cm'" class="form-label" style="color: black;">Tinggi Badan Calon Siswa (satuan cm)</label>
      <input type="number" class="form-control" id="height_cm'" name="height_cm" placeholder="Masukkan tinggi badan">
    </div>

    <!-- Berat badan -->
    <div class="mb-3">
      <label for="weight_kg" class="form-label" style="color: black;">Berat Badan Calon Siswa (satuan kg)</label>
      <input type="number" class="form-control" id="weight_kg" name="weight_kg" placeholder="Masukkan berat badan">
    </div>

    <!-- Parent Information -->
    <div class="mb-3">
      <label for="father_name" class="form-label" style="color: black;">Nama Ayah</label>
      <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Masukkan nama ayah" maxlength="255">
    </div>

    <div class="mb-3">
      <label for="mother_name" class="form-label" style="color: black;">Nama Ibu</label>
      <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Masukkan nama ibu" maxlength="255">
    </div>

    <!-- Pekerjaan Ayah -->
    <div class="form-group">
      <label for="father_job" style="color: black;">Pekerjaan Ayah</label>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="karyawan" value="karyawan">
        <label class="form-check-label" for="karyawan"style="color: black;">Karyawan Swasta</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="wiraswasta" value="wiraswasta">
        <label class="form-check-label" for="wiraswasta"style="color: black;">Wiraswasta</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="pegawai_negri" value="pegawai_negri">
        <label class="form-check-label" for="pegawai_negri"style="color: black;">Pegawai Negri</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="bumn" value="bumn">
        <label class="form-check-label" for="bumn"style="color: black;">BUMN</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="dosen/pengajar" value="dosen/pengajar">
        <label class="form-check-label" for="dosen/pengajar"style="color: black;">Dosen/Pengajar</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="apoteker" value="apoteker">
        <label class="form-check-label" for="apoteker"style="color: black;">Apoteker</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="perawat" value="perawat">
        <label class="form-check-label" for="perawat"style="color: black;">Perawat</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="father_job" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" id="otherText" name="otherText" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini">
        </label>
      </div>
    </div>

    <!-- Pekerjaan Ibu -->
    <div class="form-group">
      <label for="mother_job" style="color: black;">Pekerjaan Ibu</label>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="irt" value="irt">
        <label class="form-check-label" for="irt"style="color: black;">Ibu Rumah Tangga</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="karyawan" value="karyawan">
        <label class="form-check-label" for="karyawan"style="color: black;">Karyawan Swasta</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="wiraswasta" value="wiraswasta">
        <label class="form-check-label" for="wiraswasta"style="color: black;">Wiraswasta</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="pegawai_negri" value="pegawai_negri">
        <label class="form-check-label" for="pegawai_negri"style="color: black;">Pegawai Negri</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="bumn" value="bumn">
        <label class="form-check-label" for="bumn"style="color: black;">BUMN</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="dosen/pengajar" value="dosen/pengajar">
        <label class="form-check-label" for="dosen/pengajar"style="color: black;">Dosen/Pengajar</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="apoteker" value="apoteker">
        <label class="form-check-label" for="apoteker"style="color: black;">Apoteker</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="perawat" value="perawat">
        <label class="form-check-label" for="perawat"style="color: black;">Perawat</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="mother_job" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" id="otherText" name="otherText" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini">
        </label>
      </div>
    </div>

    <!-- WhatsApp Numbers -->
    <div class="mb-3">
      <label for="father_whatsapp" class="form-label"style="color: black;">Nomor WhatsApp Ayah</label>
      <input type="number" class="form-control" id="father_whatsapp" name="father_whatsapp" placeholder="Masukkan nomor WhatsApp ayah" maxlength="15">
    </div>

    <div class="mb-3">
      <label for="mother_whatsapp" class="form-label"style="color: black;">Nomor WhatsApp Ibu</label>
      <input type="number" class="form-control" id="mother_whatsapp" name="mother_whatsapp" placeholder="Masukkan nomor WhatsApp ibu" maxlength="15">
    </div>

    <!-- Address -->
    <!-- <div class="mb-3">
      <label for="address" class="form-label"style="color: black;">Alamat</label>
      <input type="text" class="form-control" id="address" name="address" placeholder="Masukkan Alamat Lengkap" maxlength="255">
    </div> -->

    <div class="mb-3">
      <label for="address" class="form-label"style="color: black;">Alamat</label>
      <textarea class="form-control" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap"></textarea>
    </div>

    <!-- kelompok usia -->
    <div class="form-group">
      <label for="age_group"style="color: black;">Kelompok Usia Calon Siswa</label>
      <div class="form-check">
        <input class="" type="radio" name="age_group" id="<1tahun" value="<1tahun">
        <label class="form-check-label" for="<1tahun"style="color: black;"style="color: black;">6 - 11 Bulan</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="age_group" id=">1tahun" value=">1tahun">
        <label class="form-check-label" for=">1tahun"style="color: black;"style="color: black;">1 - 5 Tahun</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="age_group" id=">5tahun" value=">5tahun">
        <label class="form-check-label" for=">5tahun"style="color: black;"style="color: black;">> 5 Tahun</label>
      </div>
    </div>

                     <!-- Pilihan Tipe Paket (Radio Button) -->
                     <div class="form-group">
    <label for="package_type" style="color: black;">Pilihan Tipe Paket</label><br>
    @foreach ($paket as $p)
        <div class="form-check">
            <input class="form-check-input" type="radio" name="package_type" id="package_{{ $p->id_paket }}" value="{{ $p->id_paket }}">
            <label class="form-check-label" for="package_{{ $p->id_paket }}">
                {{ $p->nama_paket }}
            </label>
        </div>
    @endforeach
</div>

    <!-- Tampilkan Rincian Paket -->
    <!-- <div id="paket-detail" style="margin-top: 15px; background: #f5f5f5; padding: 10px; border-radius: 5px;">
      <p><strong>Rincian Biaya:</strong></p>
      <p>Uang Pendaftaran: <span id="u_pendaftaran">-</span></p>
      <p>Uang Pangkal: <span id="u_pangkal">-</span></p>
      <p>Uang Kegiatan: <span id="u_kegiatan">-</span></p>
      <p>Uang SPP: <span id="u_spp">-</span></p>
      <p>Uang Makan: <span id="u_makan">-</span></p>
    </div> -->

    <!-- Hidden input untuk dikirim -->
    <input type="hidden" name="u_pendaftaran" id="u_pendaftaran_input">
    <input type="hidden" name="u_pangkal" id="u_pangkal_input">
    <input type="hidden" name="u_kegiatan" id="u_kegiatan_input">
    <input type="hidden" name="u_spp" id="u_spp_input">
    <input type="hidden" name="u_makan" id="u_makan_input">

    <!-- Medical History -->
    <div class="mb-3">
      <label for="medical_history" class="form-label"style="color: black;">Riwayat Kesehatan<p><i>Contoh Pengisian:<p>- Memiliki riwayat penyakit asma</p><p>- Alergi terhadap ikan tongkol dan alergi dingin</p><p>Apabila tidak memiliki riwayat penyakit tertentu, isi dengan tanda (-)</p></i></p></label>
      <input type="text" class="form-control" id="medical_history" name="medical_history" placeholder="Masukkan riwayat kesehatan (opsional)" maxlength="255">
    </div>

    <!-- <div class="mb-3">
      <label for="medical_history" class="form-label">Riwayat Kesehatan<p><i>Contoh Pengisian:<p>- Memiliki riwayat penyakit asma</p><p>- Alergi terhadap ikan tongkol dan alergi dingin</p><p>Apabila tidak memiliki riwayat penyakit tertentu, isi dengan tanda (-)</p></i></p></label>
      <textarea class="form-control" id="medical_history" name="medical_history" rows="3" placeholder="Masukkan riwayat kesehatan (opsional)"></textarea>
    </div> -->

    <!-- kebiasaan makan -->
    <div class="form-group">
      <label for="eating_habit"style="color: black;">Kebiasaan Makan Calon Siswa</label>
      <div class="form-check">
        <input class="" type="radio" name="eating_habit" id="kecil" value="kecil">
        <label class="form-check-label" for="kecil"style="color: black;">Porsi Kecil</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="eating_habit" id="sedang" value="sedang">
        <label class="form-check-label" for="sedang"style="color: black;">Porsi Sedang</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="eating_habit" id="besar" value="besar">
        <label class="form-check-label" for="besar"style="color: black;">Porsi Besar</label>
      </div>
    </div>

    <!-- Makanan Kesukaan -->
    <div class="mb-3">
      <label for="favorite_food" class="form-label"style="color: black;">Makanan Kesukaan</label>
      <input type="text" class="form-control" id="favorite_food" name="favorite_food" placeholder="Masukkan Makanan Kesukaan" maxlength="255">
    </div>
    <!-- <div class="mb-3">
      <label for="favorite_food" class="form-label">Masukkan Makanan Kesukaan</label>
      <textarea class="form-control" id="favorite_food" name="favorite_food" rows="3" placeholder="Masukkan Makanan Kesukaan"></textarea>
    </div> -->

    <!-- Minuman Kesukaan -->
    <div class="mb-3">
      <label for="favorite_drink" class="form-label"style="color: black;">Minuman Kesukaan</label>
      <input type="text" class="form-control" id="favorite_drink" name="favorite_drink" placeholder="Masukkan Minuman Kesukaan" maxlength="255">
    </div>
    <!-- <div class="mb-3">
      <label for="favorite_drink" class="form-label">Masukkan Minuman Kesukaan</label>
      <textarea class="form-control" id="favorite_drink" name="favorite_drink" rows="3" placeholder="Masukkan Minuman Kesukaan"></textarea>
    </div> -->

    <!-- Mainan Kesukaan -->
    <div class="mb-3">
      <label for="favorite_toy" class="form-label"style="color: black;">Mainan Kesukaan</label>
      <input type="text" class="form-control" id="favorite_toy" name="favorite_toy" placeholder="Masukkan Mainan Kesukaan" maxlength="255">
    </div>
    <!-- <div class="mb-3">
      <label for="favorite_toy" class="form-label">Masukkan Mainan Kesukaan</label>
      <textarea class="form-control" id="favorite_toy" name="favorite_toy" rows="3" placeholder="Masukkan Mainan Kesukaan"></textarea>
    </div> -->

     <!-- Kebiasaan Tertentu -->
     <div class="mb-3">
      <label for="specific_habits" class="form-label"style="color: black;">Kebiasaan Tertentu Calon Siswa<p><i>Contoh Pengisian:<p>- Tidur dengan boneka panda</p><p>Apabila tidak ada kebiasaan tertentu, isi dengan tanda strip (-)</p></i></p></label>
      <input type="text" class="form-control" id="specific_habits" name="specific_habits" placeholder="Masukkan Kebiasaan Tertentu" maxlength="255">
    </div>
     <!-- <div class="mb-3">
      <label for="specific_habits" class="form-label"style="color: black;">Kebiasaan Tertentu Calon Siswa<p><i>Contoh Pengisian:<p>- Tidur dengan boneka panda</p><p>Apabila tidak ada kebiasaan tertentu, isi dengan tanda strip (-)</p></i></p></label>
      <textarea class="form-control" id="specific_habits" name="specific_habits" rows="3" placeholder="Masukkan Kebiasaan Tertentu"></textarea>
    </div> -->

    <!-- asuhan -->
    <div class="form-group">
      <label for="pengasuh"style="color: black;">Calon Siswa di Rumah Diasuh oleh?<p><i>Boleh memilih lebih dari 1 jawaban</i></p></label>
      <div class="form-check">
        <input class="" type="checkbox" name="caretaker[]" id="ortu" value="ortu">
        <label class="form-check-label" for="ortu"style="color: black;">Ibu dan Ayah</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="caretaker[]" id="ibu" value="ibu">
        <label class="form-check-label" for="ibu"style="color: black;">Hanya Ibu</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="caretaker[]" id="ayah" value="ayah">
        <label class="form-check-label" for="ayah"style="color: black;">Hanya Ayah</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="caretaker[]" id="pengasuh" value="pengasuh">
        <label class="form-check-label" for="pengasuh"style="color: black;">Pengasuh</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="caretaker[]" id="nenek" value="nenek">
        <label class="form-check-label" for="nenek"style="color: black;">Nenek</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="caretaker[]" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" name="caretaker_other" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini">
        </label>
      </div>
    </div>

    <!-- Trial Agreement -->
    <div class="form-group">
      <label for="trial_agreement"style="color: black;">Bersedia mengikuti Trial Daycare?</label>
      <div class="form-check">
        <input class="" type="radio" name="trial_agreement" id="free" value="free">
        <label class="form-check-label" for="free"style="color: black;">Ya, Free Trial Daycare selama 2 jam</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="trial_agreement" id="halfday" value="halfday">
        <label class="form-check-label" for="halfday"style="color: black;">Ya, Trial Daycare selama 5 jam (Half day)</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="trial_agreement" id="fullday" value="fullday">
        <label class="form-check-label" for="fullday"style="color: black;">Ya, Trial Daycare selama 8 jam (Full day)</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="trial_agreement" id="tidak" value="tidak">
        <label class="form-check-label" for="tidak"style="color: black;">Tidak</label>
      </div>
    </div>

    <!-- Trial Date -->
    <div class="mb-3">
      <label for="trial_date" class="form-label"style="color: black;">Rencana Tanggal Trial Daycare<p><i>Di isi jika memilih Trial Daycare</i></p></label>
      <input type="date" class="form-control" id="trial_date" name="trial_date">
    </div>

    <!-- start Date -->
    <div class="mb-3">
      <label for="start_date" class="form-label"style="color: black;">Rencana Tanggal Resmi Masuk Daycare</label>
      <input type="date" class="form-control" id="start_date" name="start_date">
    </div>

    <!-- alasan -->
    <div class="form-group">
      <label for="reason_for_choosing"style="color: black;">Alasan memilih Hikari Kidz Daycare<p><i>Silakan pilih dari satu</i></p></label>
      <div class="form-check">
        <input class="" type="checkbox" name="reason_for_choosing[]" id="dekat" value="dekat">
        <label class="form-check-label" for="dekat"style="color: black;">Dekat dari Rumah</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="reason_for_choosing[]" id="jasa" value="jasa">
        <label class="form-check-label" for="jasa"style="color: black;">Mau coba daycare setelah menggunakan jasa pengasuh</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="reason_for_choosing[]" id="instagram" value="instagram">
        <label class="form-check-label" for="instagram"style="color: black;">Menyukai kegiatan di Hikari Kidz Daycare dari Instagram</label>
      </div>
      <div class="form-check">
        <input class="" type="checkbox" name="reason_for_choosing[]" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" id="otherText" name="otherText" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini">
        </label>
      </div>
    </div>
    

    <!-- Sumber Informasi Program HKC Daycare-->
    <div class="form-group">
      <label for="information_source"style="color: black;">Mendapat Informasi Pertama Kali Mengenai Hikari Kidz Daycare melalui:</label>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="spanduk" value="spanduk">
        <label class="form-check-label" for="spanduk"style="color: black;">Spanduk</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="banner" value="banner">
        <label class="form-check-label" for="banner"style="color: black;">Banner di jalan</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="instagram" value="instagram">
        <label class="form-check-label" for="instagram"style="color: black;">Instagram</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="whatsapp" value="whatsapp">
        <label class="form-check-label" for="whatsapp"style="color: black;">Whatsapp</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="facebook" value="facebook">
        <label class="form-check-label" for="facebook"style="color: black;">Facebook HB</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="brosur" value="brosur">
        <label class="form-check-label" for="brosur"style="color: black;">Brosur</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="teman" value="teman">
        <label class="form-check-label" for="teman"style="color: black;">Teman</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="kantor" value="kantor">
        <label class="form-check-label" for="kantor"style="color: black;">Kantor Hikari Bridge</label>
      </div>
      <div class="form-check">
        <input class="" type="radio" name="information_source" id="other" value="other">
        <label class="form-check-label" for="other"style="color: black;">
          Other: 
          <input type="text" name="information_source_other" style="border: none; border-bottom: 1px solid black; outline: none;" placeholder="Tulis di sini"></label>
      </div>
    </div>
    
    <!-- Submit Button -->
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Kirim</button>
    </div>
  </form>
</div>

<!-- SCRIPT AJAX -->
<script>
document.querySelectorAll('.tipe-paket').forEach(function(radio) {
    radio.addEventListener('change', function () {
        let tipe = this.value;
        fetch(`/get-paket-by-tipe/${tipe}`)
        .then(res => res.json())
        .then(data => {
            if (data) {
                document.getElementById('u_pendaftaran').textContent = 'Rp ' + data.u_pendaftaran;
                document.getElementById('u_pangkal').textContent = 'Rp ' + data.u_pangkal;
                document.getElementById('u_kegiatan').textContent = 'Rp ' + (data.u_kegiatan ?? 0);
                document.getElementById('u_spp').textContent = 'Rp ' + (data.u_spp ?? 0);
                document.getElementById('u_makan').textContent = 'Rp ' + data.u_makan;

                // isi hidden input
                document.getElementById('u_pendaftaran_input').value = data.u_pendaftaran;
                document.getElementById('u_pangkal_input').value = data.u_pangkal;
                document.getElementById('u_kegiatan_input').value = data.u_kegiatan;
                document.getElementById('u_spp_input').value = data.u_spp;
                document.getElementById('u_makan_input').value = data.u_makan;
            }
        });
    });
});
</script>

@endsection