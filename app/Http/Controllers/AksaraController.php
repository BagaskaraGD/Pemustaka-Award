<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // <-- TAMBAHKAN INI
use App\Mail\ReviewBerhasilMail;     // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Log; // <-- TAMBAHKAN INI (Opsional, untuk logging error)

class AksaraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kodebuku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'review' => 'required',
            'rekomendasi',
            'sosmed',
            'perbaikan'
        ]);

        $perbaikan = $request->perbaikan;
        $civitas = session('civitas')['id_civitas'];

        if (!$perbaikan) {
            $responseCheck = Http::get('http://127.0.0.1:8000/api/aksara-dinamika/check-review', [
                'nim' => $civitas,
                'induk_buku' => $request->kodebuku
            ]);
            $alreadyReviewed = $responseCheck->json()['exists'] ?? false;

            if ($alreadyReviewed) {
                // Beri pesan yang lebih spesifik
                return redirect()->back()->with('failed', 'Anda sudah pernah mereview buku ini.')->withInput();
            }
        }

        $responseLastId = Http::get('http://127.0.0.1:8000/api/aksara-dinamika/last-id');
        $lastId = $responseLastId->json()['last_id'] ?? 0;
        $newId = $lastId + 1;

        $responseIdb = Http::get('http://127.0.0.1:8000/api/aksara-dinamika/last-idbuku');
        $lastIdb = $responseIdb->json()['last_idb'] ?? 0;
        $newIdb = (string) ($lastIdb + 1);

        $link = $request->sosmed;
        // Pastikan link tidak null sebelum memprosesnya
        if ($link && !Str::startsWith($link, ['http://', 'https://'])) {
            $link = 'https://' . $link;
        }

        $currentDateTime = Carbon::now()->toDateTimeString();

        // Siapkan data untuk dikirim ke API
        $dataToSend = [
            'id' => $newId,
            'nim' => $civitas,
            'id_buku' => $newIdb,
            'induk_buku' => $request->kodebuku,
            'review' => $request->review,
            'dosen_usulan' => $request->rekomendasi,
            'link_upload' => $link,
            'tgl_review' => $currentDateTime,
        ];

        // Kirim data ke API
        $response = Http::post('http://127.0.0.1:8000/api/aksara-dinamika', $dataToSend);

        // Cek jika pengiriman ke API berhasil
        if ($response->successful()) {

            // --- AWAL BAGIAN PENGIRIMAN EMAIL ---
            try {
                $emailTujuan = 'bagaskaragd@gmail.com'; // Alamat email tujuan

                // Siapkan data untuk email (termasuk judul & pengarang)
                $dataEmail = $dataToSend + [
                    'judul' => $request->judul,
                    'pengarang' => $request->pengarang,
                ];

                // Kirim email
                Mail::to($emailTujuan)->send(new ReviewBerhasilMail($dataEmail));

                // Jika berhasil, redirect dengan pesan sukses
                return redirect()->back()->with('success', 'Review berhasil disimpan dan notifikasi telah dikirim.');
            } catch (\Exception $e) {
                // Jika email GAGAL, catat error (opsional)
                Log::error('Gagal mengirim email notifikasi review: ' . $e->getMessage());

                // Tetap redirect dengan pesan sukses, tapi tambahkan peringatan email
                return redirect()->back()->with('success', 'Review berhasil disimpan, tetapi notifikasi email gagal dikirim.');
            }
            // --- AKHIR BAGIAN PENGIRIMAN EMAIL ---

        } else {
            // Jika GAGAL menyimpan ke API
            Log::error('Gagal menyimpan review ke API: ' . $response->body()); // Catat error API
            return redirect()->back()->with('failed', 'Gagal menyimpan data review. Silakan coba lagi.')->withInput();
        }
    }
    public function viewperbaiki(Request $request)
    {
        $request->validate([
            'id',
            'nim',
            'induk_buku',
        ]);
        $id = $request->id;
        $nim = $request->nim;
        $induk_buku = $request->induk_buku;
        //dd($id, $nim, $induk_buku);
        $response = Http::get("http://127.0.0.1:8000/api/aksara-dinamika/detail-for-edit/{$id}/{$induk_buku}/{$nim}");
        if ($response->successful()) {
            $data = $response->json()['data']; // Ambil langsung 'data' dari hasil JSON
        } else {
            $data = []; // Atasi jika API gagal
        }
        //dd($data);
        return view('Mahasiswa/formperbaikanaksara', [
            'data' => $data,
            'civitasId' => $nim // Lewatkan id_civitas ke view dengan nama 'civitasId'
        ]);
    }
    public function viewAksaraDinamika1()
    {
        $civitas = session('civitas');

        // Cek apakah session tersedia dan memiliki id_civitas
        if (!$civitas || !isset($civitas['id_civitas'])) {
            // Bisa redirect ke login, tampilkan pesan, atau abort
            return redirect('/login')->with('error', 'Session civitas tidak ditemukan. Silakan login kembali.');
        }

        $id_civitas = $civitas['id_civitas']; // Ini adalah ID civitas yang akan kita gunakan

        // Panggil API untuk mendapatkan data aksara dinamika milik user tersebut
        $response = Http::get("http://127.0.0.1:8000/api/aksara-dinamika/aksara-user/{$id_civitas}");

        if ($response->successful()) {
            $data = $response->json()['data']; // Ambil langsung 'data' dari hasil JSON
        } else {
            $data = []; // Atasi jika API gagal
        }

        // Kirim data aksara dinamika DAN id_civitas ke view
        return view('Mahasiswa/aksaradinamika', [
            'data' => $data,
            'civitasId' => $id_civitas // Lewatkan id_civitas ke view dengan nama 'civitasId'
        ]);
    }
    public function viewAksaraDinamika2()
    {
        return view('Dosen/aksaradinamika');
    }
    public function viewFormAksaraDinamika1()
    {
        return view('Mahasiswa/formaksaradinamika');
    }
    public function viewFormAksaraDinamika2()
    {
        return view('Dosen/formaksaradinamika');
    }


    public function search()
    {
        $response = Http::get('http://127.0.0.1:8000/api/buku/search', [
            'q' => request('q') // teruskan keyword ke API
        ]);

        return response()->json($response->json());
    }
    public function karyawan_search()
    {
        $response = Http::get('http://127.0.0.1:8000/api/karyawan/search', [
            'q' => request('q') // teruskan keyword ke API
        ]);
        return response()->json($response->json());
    }
    public function perbaikan(Request $request, $id)
    {
        $response = Http::put("http://backend-pemustakaaward.test/api/aksara-dinamika/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Berhasil update data');
        }

        return redirect()->back()->withErrors($response->json()['errors']);
    }
    public function destroy($id)
    {
        $response = Http::delete("http://backend-pemustakaaward.test/api/aksara-dinamika/{$id}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Berhasil hapus data');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal hapus data']);
    }
}
