<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AksaraController extends Controller
{
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
    public function store(Request $request)
    {
        $request->validate([
            'kodebuku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'review' => 'required',
            'rekomendasi',
            'sosmed',
        ]);
        //dd($request->all());

        $civitas = session('civitas')['id_civitas'];
        //dd($civitas);
        // Cek apakah nim sudah pernah review buku yang sama (induk_buku)
        $response = Http::get('http://127.0.0.1:8000/api/aksara-dinamika/check-review', [
            'nim' => $civitas,
            'induk_buku' => $request->kodebuku
        ]);

        $alreadyReviewed = $response->json()['exists'] ?? false;

        if ($alreadyReviewed) {
            return redirect()->back()->with('failed', true);
        }

        $response = Http::get('http://127.0.0.1:8000/api/aksara-dinamika/last-id');

        $lastId = $response->json()['last_id'] ?? 0;
        $newId = $lastId + 1;

        $responseIdb = Http::get('http://127.0.0.1:8000/api/aksara-dinamika/last-idbuku');
        $lastIdb = $responseIdb->json()['last_idb'] ?? 0;
        $newIdb = (string) ($lastIdb + 1);

        // Pastikan link sosmed valid
        $link = $request->sosmed;
        if (!Str::startsWith($link, ['http://', 'https://'])) {
            $link = 'https://' . $link;
        }

        // Tanggal dan waktu sekarang
        $currentDateTime = Carbon::now()->toDateTimeString(); // format: Y-m-d H:i:s

        $response = Http::post('http://127.0.0.1:8000/api/aksara-dinamika', [
            'id' => $newId,
            'nim' => $civitas,
            'id_buku' => $newIdb,
            'induk_buku' => $request->kodebuku,
            'review' => $request->review,
            'dosen_usulan' => $request->rekomendasi,
            'link_upload' => $link,
            'tgl_review' => $currentDateTime,
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', true);
        } else {
            return redirect()->back()->with('failed', true);
        }
    }
    public function update(Request $request, $id)
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
