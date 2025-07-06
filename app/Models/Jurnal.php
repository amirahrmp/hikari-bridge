<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = "jurnal"; // Nama tabel jurnal

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_transaksi',
        'kode_akun',
        'tgl_jurnal',
        'posisi_d_c',
        'nominal',
        'kelompok',
        'transaksi'
    ];

    /**
     * Mengambil data jurnal umum berdasarkan periode.
     * @param string $periode Format YYYY-MM
     * @return array
     */
    public static function viewjurnalumum(string $periode): array
    {
        $sql = "SELECT a.*, b.nama_akun
                FROM jurnal a JOIN coa b
                ON (a.kode_akun = b.kode_akun)
                WHERE DATE_FORMAT(tgl_jurnal, '%Y-%m') = ?
                ORDER BY tgl_jurnal ASC, a.id_transaksi ASC"; // Urutkan untuk konsistensi
        $list = DB::select($sql, [$periode]);
        return $list;
    }

    /**
     * Mengambil daftar unik akun (kode dan nama) yang pernah memiliki transaksi di tabel jurnal.
     * Digunakan untuk mengisi dropdown filter akun di Buku Besar.
     * @return array
     */
    public static function viewakunbukubesar(): array
    {
        $sql = "SELECT DISTINCT b.kode_akun, b.nama_akun
                FROM jurnal a JOIN coa b
                ON (a.kode_akun = b.kode_akun)
                ORDER BY b.nama_akun ASC"; // Urutkan berdasarkan nama akun
        $list = DB::select($sql);
        return $list;
    }

    /**
     * Mengambil transaksi jurnal untuk akun dan periode tertentu (bulan berjalan).
     * @param string $periode Format YYYY-MM
     * @param string $kode_akun
     * @return array
     */
    public static function viewdatabukubesar(string $periode, string $kode_akun): array
    {
        $sql = "SELECT a.*, b.nama_akun
                FROM jurnal a JOIN coa b
                ON (a.kode_akun = b.kode_akun)
                WHERE DATE_FORMAT(tgl_jurnal, '%Y-%m') = ?
                AND b.kode_akun = ?
                ORDER BY tgl_jurnal ASC, a.id_transaksi ASC"; // Urutkan berdasarkan tanggal dan ID transaksi
        $list = DB::select($sql, [$periode, $kode_akun]);
        return $list;
    }

    /**
     * Menentukan posisi saldo normal (Debet/Kredit) dari sebuah akun berdasarkan kode akunnya.
     * Asumsi: Digit pertama dari kode akun menunjukkan kelompok akun (1=Aset, 2=Liabilitas, 3=Ekuitas, 4=Pendapatan, 5=Beban).
     * @param string $kode_akun
     * @return string|null 'd' for Debet, 'c' for Kredit, null if not found
     */
    public static function viewposisisaldonormalakun(string $kode_akun): ?string
    {
        // Ambil header akun dari COA atau langsung dari digit pertama kode akun
        // jika header_akun tidak disimpan terpisah di COA atau kurang konsisten
        $header_digit = substr($kode_akun, 0, 1);

        return match ($header_digit) {
            '1', '5' => 'd', // Aset (1), Beban (5) normalnya Debet
            '2', '3', '4' => 'c', // Liabilitas (2), Ekuitas (3), Pendapatan (4) normalnya Kredit
            default => null, // Tipe akun tidak dikenal
        };
    }

    /**
     * Menghitung saldo awal akun pada awal periode yang dipilih.
     * Saldo awal adalah akumulasi debet/kredit dari semua transaksi sebelum periode tersebut.
     * @param string $periode Format YYYY-MM
     * @param string $kode_akun
     * @return float
     */
    public static function viewsaldobukubesar(string $periode, string $kode_akun): float
    {
        $posisi_saldo_normal = self::viewposisisaldonormalakun($kode_akun);

        if (is_null($posisi_saldo_normal)) {
            return 0.0; // Jika posisi normal tidak ditemukan, anggap saldo awal 0
        }

        // Query untuk menjumlahkan semua debet dan kredit untuk akun yang dipilih
        // dari transaksi yang terjadi SEBELUM periode yang ditentukan
        $sql = "SELECT
                    SUM(CASE WHEN posisi_d_c = 'd' THEN nominal ELSE 0 END) as total_debet_sebelum,
                    SUM(CASE WHEN posisi_d_c = 'c' THEN nominal ELSE 0 END) as total_kredit_sebelum
                FROM jurnal
                WHERE kode_akun = ?
                AND DATE_FORMAT(tgl_jurnal, '%Y-%m') < ?";
        $result = DB::selectOne($sql, [$kode_akun, $periode]);

        $saldo_debet_sebelum = (float) ($result->total_debet_sebelum ?? 0);
        $saldo_kredit_sebelum = (float) ($result->total_kredit_sebelum ?? 0);

        // Hitung saldo awal berdasarkan posisi saldo normal akun
        $saldo_awal = 0.0;
        if ($posisi_saldo_normal === 'd') {
            $saldo_awal = $saldo_debet_sebelum - $saldo_kredit_sebelum;
        } else { // 'c'
            $saldo_awal = $saldo_kredit_sebelum - $saldo_debet_sebelum;
        }

        return $saldo_awal;
    }
}