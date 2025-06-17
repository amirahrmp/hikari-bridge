@extends('layouts2.master')

{{-- Sesuaikan dengan menu sidebar Anda di layout master --}}
@section('pembayaran_kegiatan_tambahan_user_select','active')
@section('title', 'Tagihan Kegiatan Tambahan Anda')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">Daftar Tagihan Kegiatan Tambahan Anda</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table id="datatable" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><b>No</b></th>
                                                            <th><b>Nama Peserta</b></th>
                                                            <th><b>Kelas</b></th>
                                                            <th><b>Nama Kegiatan</b></th>
                                                            <th><b>Biaya Kegiatan</b></th>
                                                            <th><b>Status Pembayaran</b></th>
                                                            <th><b>Aksi</b></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($kegiatan_tambahan_user as $index => $kegiatan)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $kegiatan->anak->nama_lengkap ?? 'N/A' }}</td>
                                                            <td>{{ $kegiatan->anak->kelas ?? 'N/A' }}</td>
                                                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                                                            <td>Rp{{ number_format($kegiatan->biaya, 0, ',', '.') }}</td>
                                                            <td>
                                                                @php
                                                                    $statusText = '';
                                                                    $statusColor = '';
                                                                    switch (strtolower($kegiatan->status_pembayaran)) {
                                                                        case 'belum':
                                                                            $statusText = 'Belum Lunas';
                                                                            $statusColor = 'orange';
                                                                            break;
                                                                        case 'menunggu verifikasi':
                                                                            $statusText = 'Menunggu Verifikasi';
                                                                            $statusColor = '#007bff'; // Biru untuk menunggu
                                                                            break;
                                                                        case 'lunas':
                                                                            $statusText = 'Lunas';
                                                                            $statusColor = 'green';
                                                                            break;
                                                                        default:
                                                                            $statusText = 'Tidak Diketahui';
                                                                            $statusColor = 'gray';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <span style="background-color: {{ $statusColor }}; padding: 3px 5px; border-radius: 3px; color: white;">
                                                                    {{ $statusText }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal"
                                                                    data-id="{{ $kegiatan->id }}"
                                                                    data-nama-anak="{{ $kegiatan->anak->nama_lengkap ?? 'N/A' }}"
                                                                    data-kelas="{{ $kegiatan->anak->kelas ?? 'N/A' }}"
                                                                    data-nama-kegiatan="{{ $kegiatan->nama_kegiatan }}"
                                                                    data-biaya="{{ number_format($kegiatan->biaya, 0, ',', '.') }}"
                                                                    data-status="{{ strtolower($kegiatan->status_pembayaran) }}"
                                                                    data-bukti-path="{{ $kegiatan->bukti_pembayaran_path ? Storage::url('proof_of_payments/' . $kegiatan->bukti_pembayaran_path) : '' }}"
                                                                    data-payment-method="{{ $kegiatan->payment_method ?? '' }}">
                                                                    Lihat Detail & Unggah Bukti
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">Tidak ada tagihan kegiatan tambahan untuk Anda.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-right"><strong>Total Tagihan Belum Lunas:</strong></td>
                                                            <td colspan="3"><strong>Rp{{ number_format($total_tagihan_belum_lunas, 0, ',', '.') }}</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Tagihan & Unggah Bukti Pembayaran</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <h6>Informasi Tagihan</h6>
                        <hr>
                        <p><strong>Nama Peserta:</strong> <span id="modal-nama-anak"></span></p>
                        <p><strong>Kelas:</strong> <span id="modal-kelas"></span></p>
                        <p><strong>Nama Kegiatan:</strong> <span id="modal-nama-kegiatan"></span></p>
                        <p><strong>Biaya Kegiatan:</strong> Rp<span id="modal-biaya"></span></p>
                        <p><strong>Status Pembayaran:</strong> <span id="modal-status"></span></p>

                        <h6 class="mt-4">Metode Pembayaran Tersedia:</h6>
                        <div class="card card-body border border-primary mb-3">
                            <strong>Transfer Bank (Bank Muamalat)</strong><br>
                            Bank Muamalat Indonesia<br>
                            No. Rekening: 101xxxxxx<br>
                            A.N.: PT Hikari Bridge Indonesia<br>
                            <small class="text-muted">Mohon pastikan nama akun yang terlihat baik, benar dan sesuai untuk menghindari kesalahan.</small>
                        </div>
                        <div class="card card-body border border-primary text-center">
                            <strong>QRIS</strong><br>
                            <img src="https://placehold.co/150x150/007bff/ffffff?text=QRIS+Code" alt="QRIS Code" style="width: 150px; height: 150px; margin: 10px auto;">
                            <small class="text-muted">Scan QRIS dengan aplikasi pembayaran favorit Anda (Gopay, OVO, Dana, LinkAja, dll.)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Unggah Bukti Pembayaran</h6>
                        <hr>
                        <p>Silakan unggah bukti transfer/pembayaran Anda di sini. Pastikan gambar jelas dan berisi detail transaksi.</p>

                        <form id="uploadProofForm" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="bukti_pembayaran">Pilih File Bukti Pembayaran (Gambar)</label>
                                <input type="file" class="form-control-file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
                                <small class="form-text text-muted">Maksimal ukuran file 2MB. Format: JPG, PNG, JPEG, GIF, SVG, WEBP.</small>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Metode Pembayaran</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="QRIS">QRIS</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success" id="btn-submit-proof">
                                <i class="fas fa-upload"></i> Unggah Bukti
                            </button>
                            <p class="text-muted mt-2" id="upload-status-message" style="display: none;"></p>
                        </form>

                        <h6 class="mt-4">Bukti Pembayaran yang Diunggah:</h6>
                        <hr>
                        <div id="bukti-pembayaran-area">
                            <p class="text-muted">Belum ada bukti pembayaran diunggah.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Pastikan jQuery dan Bootstrap JS sudah dimuat di layouts2.master --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        // Untuk tampilan user, mungkin tidak perlu semua tombol export, bisa disesuaikan
        $('#datatable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print"] // Opsional: hapus tombol jika tidak diperlukan
        });
        // .buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)'); // uncomment if you enable buttons

        // Logic untuk mengisi data modal saat tombol "Lihat Detail & Unggah Bukti" diklik
        $('#detailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Tombol yang mengaktifkan modal
            var id = button.data('id');
            var namaAnak = button.data('nama-anak');
            var kelas = button.data('kelas');
            var namaKegiatan = button.data('nama-kegiatan');
            var biaya = button.data('biaya');
            var status = button.data('status');
            var buktiPath = button.data('bukti-path');
            var paymentMethod = button.data('payment-method');

            var modal = $(this);
            modal.find('#modal-nama-anak').text(namaAnak);
            modal.find('#modal-kelas').text(kelas);
            modal.find('#modal-nama-kegiatan').text(namaKegiatan);
            modal.find('#modal-biaya').text(biaya);

            var statusDisplay = '';
            var statusColor = '';
            switch (status) {
                case 'belum':
                    statusDisplay = 'Belum Lunas';
                    statusColor = 'orange';
                    break;
                case 'menunggu verifikasi':
                    statusDisplay = 'Menunggu Verifikasi';
                    statusColor = '#007bff'; // Biru
                    break;
                case 'lunas':
                    statusDisplay = 'Lunas';
                    statusColor = 'green';
                    break;
                default:
                    statusDisplay = 'Tidak Diketahui';
                    statusColor = 'gray';
                    break;
            }
            modal.find('#modal-status').html('<span style="background-color: ' + statusColor + '; padding: 3px 5px; border-radius: 3px; color: white;">' + statusDisplay + '</span>');

            // Set action form untuk upload bukti
            var form = modal.find('#uploadProofForm');
            form.attr('action', '/kegiatan-tambahan/' + id + '/upload-bukti');

            // Reset form file input dan pesan status
            form[0].reset(); // Reset form input
            $('#bukti_pembayaran').val(''); // Clear file input explicitly
            $('#upload-status-message').hide().text('');
            $('#btn-submit-proof').prop('disabled', false); // Aktifkan tombol submit

            // Tampilkan bukti pembayaran jika sudah ada
            var buktiArea = modal.find('#bukti-pembayaran-area');
            buktiArea.empty(); // Kosongkan area bukti sebelumnya

            if (buktiPath) {
                buktiArea.append('<img src="' + buktiPath + '" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm mt-2" style="max-height: 200px;">');
                buktiArea.append('<p class="text-muted mt-2"><strong>Metode Pembayaran:</strong> ' + paymentMethod + '</p>');
                buktiArea.append('<p class="text-success mt-2"><i class="fas fa-check-circle"></i> Bukti telah diunggah dan ' + statusDisplay.toLowerCase() + '.</p>');
                // Disable upload form if already uploaded and waiting or Lunas
                if (status === 'menunggu verifikasi' || status === 'lunas') {
                    form.find('input, select, button[type="submit"]').prop('disabled', true);
                    $('#upload-status-message').text('Bukti sudah diunggah atau tagihan sudah lunas.').show().removeClass('text-success').addClass('text-info');
                } else {
                    form.find('input, select, button[type="submit"]').prop('disabled', false);
                }
            } else {
                buktiArea.append('<p class="text-muted">Belum ada bukti pembayaran diunggah.</p>');
                form.find('input, select, button[type="submit"]').prop('disabled', false); // Aktifkan form jika belum ada bukti
            }
            
            // Set metode pembayaran yang sudah dipilih jika ada
            if (paymentMethod) {
                modal.find('#payment_method').val(paymentMethod);
            }
        });

        // Tangani submit form AJAX (opsional, untuk pengalaman yang lebih baik tanpa reload)
        // Jika Anda ingin menggunakan AJAX, Anda perlu menangani respons di sini
        $('#uploadProofForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah form submit default

            var form = $(this);
            var formData = new FormData(form[0]);
            var submitButton = $('#btn-submit-proof');
            var statusMessage = $('#upload-status-message');

            submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...');
            statusMessage.hide().removeClass('text-success text-danger text-info');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    statusMessage.text(response.success || 'Unggah berhasil!').addClass('text-success').show();
                    // Tutup modal setelah sukses dan reload halaman atau perbarui data secara dinamis
                    setTimeout(function() {
                        $('#detailModal').modal('hide');
                        location.reload(); // Reload halaman untuk melihat perubahan status
                    }, 1500);
                },
                error: function(xhr) {
                    submitButton.prop('disabled', false).html('<i class="fas fa-upload"></i> Unggah Bukti');
                    var errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    statusMessage.html(errorMessage).addClass('text-danger').show();
                }
            });
        });
    });
</script>
@endpush