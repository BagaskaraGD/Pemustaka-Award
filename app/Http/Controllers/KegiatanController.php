<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KegiatanController extends Controller
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
    public function viewKegiatan1()
    {
        return view('Mahasiswa/kegiatan');
    }
    public function viewriwayatKegiatan1()
    {
        return view('Mahasiswa/riwayatkegiatan');
    }

    public function viewKegiatan2()
    {
        return view('Dosen/kegiatan');
    }
    public function viewriwayatKegiatan2()
    {
        return view('Dosen/riwayatkegiatan');
    }
    public function storekehadiran(Request $request)
    {
        $credentials = $request->validate([
            'kode' => 'required',
        ]);

        $civitas = session('civitas')['id_civitas'];
        $status = session('civitas')['status'];
        if ($status == 'MHS') {
            $route = 'kegiatan1';
        } else {
            $route = 'kegiatan2';
        }

        // Ambil semua jadwal dari API
        $response = Http::get($this->baseUrl . '/jadwal-kegiatan');
        $data = $response->json();

        // Cari berdasarkan kode random
        $found = collect($data)->firstWhere('kode_random', $credentials['kode']);
        if (!$found) {
            return redirect()->route($route)->with('error_modal', 'Kode tidak valid');
        }

        // 2. Ambil data periode yang sedang aktif dari API
        $periodResponse = Http::get($this->baseUrl . '/periode/status-terkini');
        if (!$periodResponse->successful() || !isset($periodResponse->json()['periode'])) {
            return redirect()->route($route)->with('error_modal', 'Gagal memverifikasi periode award saat ini.');
        }
        $periodData = $periodResponse->json()['periode'];
        $tglMulaiPeriode = Carbon::parse($periodData['tgl_mulai']);
        $tglSelesaiPeriode = Carbon::parse($periodData['tgl_selesai']);

        // 3. Ambil tanggal kegiatan dari data jadwal yang ditemukan
        $tglKegiatan = Carbon::parse($found['tgl_kegiatan']);

        // 4. Validasi: Tolak jika tanggal kegiatan TIDAK berada dalam rentang periode aktif
        if (!$tglKegiatan->between($tglMulaiPeriode, $tglSelesaiPeriode)) {
            return redirect()->route($route)->with('error_modal', 'Kode presensi tidak valid untuk periode award saat ini.');
        }

        $response = Http::get($this->baseUrl . '/hadir-kegiatan/check-kegiatan', [
            'nim' => $civitas,
            'id_jadwal' => $found['id_jadwal'],
        ]);
        $sudahAbsen = $response->json()['exists'] ?? false;

        if ($sudahAbsen) {
            return redirect()->route($route)->with('error_modal', 'Kode sudah pernah digunakan untuk absensi kegiatan ini');
        }
        $response = Http::get($this->baseUrl . '/hadir-kegiatan/last-idhadir');

        $lastId = $response->json()['last_id'] ?? 0;
        $newId = $lastId + 1;


        // Kirim data ke API absensi
        Http::post($this->baseUrl . '/hadir-kegiatan', [
            'id' => $newId,
            'id_jadwal' => $found['id_jadwal'],
            'nim' => $civitas,
        ]);

        return redirect()->route($route)->with('success', 'Kehadiran berhasil dicatat');
    }
}
