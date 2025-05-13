<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
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

        // Cek apakah civitas sudah absen di jadwal ini
        $sudahAbsen = DB::connection('oracle')
            ->table('HADIRKEGIATAN_PUST')
            ->where('ID_JADWAL', $found['id_jadwal'])
            ->where('NIM', $civitas)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('kegiatan1')->with('error', 'Kode sudah pernah digunakan untuk absensi kegiatan ini');
        }

        // Generate ID baru
        $lastId = DB::connection('oracle')->table('HADIRKEGIATAN_PUST')->max('ID_HADIR');
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
