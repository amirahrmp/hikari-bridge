<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulir Pendaftaran Hikari Kidz Club</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <style>
    body {
      background-image: url('img/hikarikidzclub.jpg'); /* Jalur lokal ke gambar */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .form-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9); /* Transparansi background */
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .form-header {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }
    .form-description {
      font-size: 1rem;
      color: #666;
      margin-bottom: 30px;
    }
    .form-control {
      border-radius: 5px;
    }
    .btn-primary {
      background-color: #4285f4;
      border-color: #4285f4;
    }
    .btn-primary:hover {
      background-color: #357ae8;
    }
  </style>
</head>
<body>

<div class="form-container">
  <div class="form-header">Formulir Pendaftaran Siswa Baru Hikari Kidz Club</div>
  <div class="form-description">
    Selamat Datang Siswa Baru Hikari Kidz Club, silahkan untuk mengisi formulir pendaftaran.
  </div>

  <!-- Alert untuk pesan sukses -->
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Form Pendaftaran -->
  <form action="{{ route('registerkidzclub.store') }}" method="POST">
    @csrf
    <!-- Full Name -->
    <div class="mb-3">
      <label for="full_name" class="form-label">Nama Lengkap</label>
      <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" required maxlength="255">
    </div>

    <!-- Nickname -->
    <div class="mb-3">
      <label for="nickname" class="form-label">Nama Panggilan</label>
      <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Masukkan nama panggilan" required maxlength="255">
    </div>

    <!-- Birth Date -->
    <div class="mb-3">
      <label for="birth_date" class="form-label">Tanggal Lahir</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date" required>
    </div>

    <!-- Parent Name -->
    <div class="mb-3">
      <label for="parent_name" class="form-label">Nama Orang Tua</label>
      <input type="text" class="form-control" id="parent_name" name="parent_name" placeholder="Masukkan nama orang tua" required maxlength="255">
    </div>

    <!-- WhatsApp Number -->
    <div class="mb-3">
      <label for="whatsapp_number" class="form-label">Nomor WhatsApp</label>
      <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" placeholder="Masukkan nomor WhatsApp" required maxlength="15">
    </div>

    <!-- Address -->
    <div class="mb-3">
      <label for="address" class="form-label">Alamat</label>
      <textarea class="form-control" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap" required></textarea>
    </div>

    <!-- Submit Button -->
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Kirim</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
