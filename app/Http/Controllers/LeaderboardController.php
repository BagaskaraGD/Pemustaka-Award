<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request; // 1. Tambahkan use statement untuk Request
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
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

    // --- PERUBAHAN UNTUK LEADERBOARD MAHASISWA ---
    public function viewLeaderboard1(Request $request) // 2. Tambahkan Request $request
    {
        // 3. Ambil periode_id dari query string, atau null jika tidak ada
        $periodeId = $request->query('periode');

        // 4. Bangun URL API secara dinamis
        $apiUrl = $this->baseUrl . '/rekap-poin/leaderboard/mhs';
        if ($periodeId) {
            $apiUrl .= '?periode=' . $periodeId;
        }

        $response = Http::get($apiUrl);
        $data = $response->json();

        // 5. Ambil nama periode yang dipilih untuk ditampilkan di tombol
        $selectedPeriodeName = $data['periode_aktif'] ?? 'Periode Saat Ini';

        $top5 = array_slice($data['leaderboard'] ?? [], 0, 5);

        // 6. Kirim data periode ke view
        return view('Mahasiswa.leaderboard', compact('top5', 'selectedPeriodeName'));
    }

    // --- PERUBAHAN UNTUK LEADERBOARD DOSEN ---
    public function viewLeaderboard2(Request $request) // 2. Tambahkan Request $request
    {
        // 3. Ambil periode_id dari query string, atau null jika tidak ada
        $periodeId = $request->query('periode');

        // 4. Bangun URL API secara dinamis
        $apiUrl = $this->baseUrl . '/rekap-poin/leaderboard/dosen';
        if ($periodeId) {
            $apiUrl .= '?periode=' . $periodeId;
        }

        $response = Http::get($apiUrl);
        $data = $response->json();

        // 5. Ambil nama periode yang dipilih untuk ditampilkan di tombol
        $selectedPeriodeName = $data['periode_aktif'] ?? 'Periode Saat Ini';

        $top5 = array_slice($data['leaderboard'] ?? [], 0, 5);

        // 6. Kirim data periode ke view
        return view('Dosen/leaderboard', compact('top5', 'selectedPeriodeName'));
    }

    public function viewdropdownperiode()
    {
        $response = Http::get($this->baseUrl . '/periode');
        $data = $response->json();
        return response()->json($data); // return JSON, bukan view
    }
}
