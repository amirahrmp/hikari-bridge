<!DOCTYPE html>
<html>
<head>
    <title>Kuitansi Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 700px; margin: auto; padding: 20px; border: 1px solid #ccc; }
        .header { text-align: center; margin-bottom: 20px; }
        .status-paid { background-color: green; color: white; padding: 2px 8px; border-radius: 4px; }
        table { width: 100%; margin-top: 10px; }
        th, td { padding: 6px; text-align: left; vertical-align: top; }
        .right { text-align: right; }
        .footer { margin-top: 40px; text-align: right; }

        @media print {
            button { display: none !important; }
            body { margin: 0; }
            .container { border: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/hikari_logo2.png') }}" alt="Logo" height="50"><br>
            <h3>KUITANSI PEMBAYARAN</h3>
            <p>Hikari Bridge<br>
            Jl Cikoneng Bojongsoang, Ruko Komplek Dewadaru Residence No R-4 Kab.Bandung</p>
        </div>

        @php
            $type = class_basename($payment->registration_type);
            $programDisplay = match($type) {
                'RegistrationHikariKidzClub' => 'Registrasi Kidz Club',
                'RegistrationHikariKidzDaycare' => 'Registrasi Kidz Daycare',
                'RegistrationHikariQuran' => 'Registrasi Quran',
                default => '-'
            };

            $paketName = match($type) {
                'RegistrationHikariKidzClub' => $payment->registration?->getPaketHkc()?->nama_paket ?? '-',
                'RegistrationHikariKidzDaycare' => $payment->registration?->paket?->nama_paket ?? '-',
                'RegistrationHikariQuran' => $payment->registration?->pakethq?->nama_paket ?? '-',
                default => '-'
            };
        @endphp

        <table>
            <tr><td><strong>Nama Anak</strong></td><td>: {{ $childName }}</td></tr>
            <tr><td><strong>Email</strong></td><td>: {{ $payment->user->email ?? '-' }}</td></tr>
            <tr><td><strong>Program</strong></td><td>: {{ $programDisplay }}</td></tr>
            <tr><td><strong>Paket</strong></td><td>: {{ $paketName }}</td></tr>
            <tr><td><strong>Status Pembayaran</strong></td>
                <td>: <span class="status-paid">{{ strtoupper($payment->status ?? 'Belum Bayar') }}</span></td></tr>
            <tr><td><strong>Tanggal Pembayaran</strong></td>
                <td>: {{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y') }}</td></tr>
        </table>

        <br>

        <table border="1" cellspacing="0">
            <thead>
                <tr><th>Deskripsi</th><th>Jumlah</th></tr>
            </thead>
            <tbody>
                @foreach($payment->components as $component)
                    <tr>
                        <td>{{ $component->komponen }}</td>
                        <td class="right">Rp {{ number_format($component->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="right"><strong>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <strong>Finance Hikari Bridge</strong><br>
            {{ $payment->user->name ?? 'Hikari Bridge' }}
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()">Cetak Kwitansi</button>
        </div>
    </div>

    @if($print)
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
    @endif
</body>
</html>
