<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Import library PDF
use Illuminate\Support\Facades\Http;

class VoucherController extends Controller
{
    /**
     * URL dasar untuk API backend.
     * Diambil dari config/services.php yang membaca file .env
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Constructor untuk menginisialisasi base URL.
     */
    public function __construct()
    {
        // Mengambil base URL dari file konfigurasi sekali saja.
        $this->baseUrl = config('services.backend.base_url');
    }
    // app/Http/Controllers/VoucherController.php (di proyek FRONTEND)

    public function generateVoucher($id_penerima)
    {
        // Panggil API backend
        $response = Http::get("{$this->baseUrl}/penerima-reward/voucher/{$id_penerima}");

        // 1. Cek apakah panggilan API berhasil DAN apakah kunci 'data' ada di dalam respons
        if (!$response->successful() || !isset($response->json()['data'])) {
            // Jika gagal atau 'data' tidak ada, tampilkan error yang jelas
            abort(404, 'Voucher tidak ditemukan atau terjadi kesalahan saat mengambil data dari API.');
        }

        // 2. Buka "amplop" dan ambil "isi surat" dari dalam kunci 'data'
        $voucherData = $response->json()['data'];

        // 3. Siapkan data untuk dikirim ke tampilan PDF, ambil dari variabel $voucherData
        $data = [
            'nama'    => $voucherData['nama_penerima'], // <-- Ambil dari array, BUKAN dari $response
            'level'   => $voucherData['level_reward'],
            'hadiah'  => $voucherData['bentuk_reward'],
            'tanggal' => \Carbon\Carbon::parse($voucherData['tanggal_klaim'])->translatedFormat('d F Y')
        ];

        // 4. Muat tampilan (view), kirim data, dan generate PDF
        $pdf = PDF::loadView('voucher.pdf', $data);

        // 5. Tampilkan PDF di browser
        return $pdf->stream('voucher-pemustaka-award.pdf');
    }
}
