<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulir Pendaftaran Hikari Kidz Daycare</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <style>
    body {
      background-image: url('img/hikarikidzdaycare.jpg'); /* Jalur lokal ke gambar */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .form-container {
      max-width: 700px;
      margin: 50px auto;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9); /* Transparansi background */
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .form-header {
      font-size: 1.8rem;
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
  <div class="form-header">Formulir Pendaftaran Hikari Kidz Daycare</div>
  <div class="form-description">
    Mohon isi formulir berikut dengan informasi lengkap dan benar.
  </div>

  <!-- Alert untuk pesan sukses -->
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Form Pendaftaran -->
  <form action="{{ route('registerkidzdaycare.store') }}" method="POST">
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

    <!-- Child Order -->
    <div class="mb-3">
      <label for="child_order" class="form-label">Anak ke-berapa</label>
      <input type="number" class="form-control" id="child_order" name="child_order" required>
    </div>

    <!-- Siblings Count -->
    <div class="mb-3">
      <label for="siblings_count" class="form-label">Jumlah Saudara</label>
      <input type="number" class="form-control" id="siblings_count" name="siblings_count" required>
    </div>

    <!-- Parent Information -->
    <div class="mb-3">
      <label for="father_name" class="form-label">Nama Ayah</label>
      <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Masukkan nama ayah" required maxlength="255">
    </div>

    <div class="mb-3">
      <label for="mother_name" class="form-label">Nama Ibu</label>
      <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Masukkan nama ibu" required maxlength="255">
    </div>

    <!-- WhatsApp Numbers -->
    <div class="mb-3">
      <label for="father_whatsapp" class="form-label">Nomor WhatsApp Ayah</label>
      <input type="text" class="form-control" id="father_whatsapp" name="father_whatsapp" placeholder="Masukkan nomor WhatsApp ayah" required maxlength="15">
    </div>

    <div class="mb-3">
      <label for="mother_whatsapp" class="form-label">Nomor WhatsApp Ibu</label>
      <input type="text" class="form-control" id="mother_whatsapp" name="mother_whatsapp" placeholder="Masukkan nomor WhatsApp ibu" required maxlength="15">
    </div>

    <!-- Address -->
    <div class="mb-3">
      <label for="address" class="form-label">Alamat</label>
      <textarea class="form-control" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap" required></textarea>
    </div>

    <!-- Medical History -->
    <div class="mb-3">
      <label for="medical_history" class="form-label">Riwayat Kesehatan</label>
      <textarea class="form-control" id="medical_history" name="medical_history" rows="3" placeholder="Masukkan riwayat kesehatan (opsional)"></textarea>
    </div>

    <!-- Trial Agreement -->
    <div class="mb-3">
      <label for="trial_date" class="form-label">Apakah setuju untuk trial?</label>
      <select class="form-select" id="trial_agreement" name="trial_agreement" required>
        <option value="1">Ya</option>
        <option value="0">Tidak</option>
      </select>
    </div>

    <!-- Trial Date -->
    <div class="mb-3">
      <label for="trial_date" class="form-label">Tanggal Trial (Jika Ya)</label>
      <input type="date" class="form-control" id="trial_date" name="trial_date">
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
