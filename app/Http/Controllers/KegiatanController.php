<?php

namespace App\Http\Controllers;

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

        // Ambil semua jadwal dari API
        $response = Http::get('http://127.0.0.1:8000/api/jadwal-kegiatan');
        $data = $response->json();

        // Cari berdasarkan kode random
        $found = collect($data)->firstWhere('kode_random', $credentials['kode']);
        if (!$found) {
            return redirect()->route('kegiatan1')->with('error', 'Kode tidak valid');
        }
        //dd($request->all());
        $response = Http::get('http://127.0.0.1:8000/api/hadir-kegiatan/check-kegiatan', [
            'nim' => $civitas,
            'id_jadwal' => $found['id_jadwal'],
        ]);
        $sudahAbsen = $response->json()['exists'] ?? false;

        if ($sudahAbsen) {
            return redirect()->route('kegiatan1')->with('error', 'Kode sudah pernah digunakan untuk absensi kegiatan ini');
        }

        $response = Http::get('http://127.0.0.1:8000/api/hadir-kegiatan/last-idhadir');

        $lastId = $response->json()['last_id'] ?? 0;
        $newId = $lastId + 1;

        // Kirim data ke API absensi
        $response1 = Http::post('http://127.0.0.1:8000/api/hadir-kegiatan', [
            'id' => $newId,
            'id_jadwal' => $found['id_jadwal'],
            'nim' => $civitas,
        ]);
        //return response($response1->json());

        return redirect()->route('kegiatan1')->with('success', 'Kehadiran berhasil dicatat');
    }
}
