<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewBerhasilMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Pastikan Session di-import

class AksaraController extends Controller
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
            // Gunakan $this->baseUrl
            $responseCheck = Http::get($this->baseUrl . '/aksara-dinamika/check-review', [
                'nim' => $civitas,
                'induk_buku' => $request->kodebuku
            ]);
            $alreadyReviewed = $responseCheck->json()['exists'] ?? false;

            if ($alreadyReviewed) {
                return redirect()->back()->with('failed', 'Anda sudah pernah mereview buku ini.')->withInput();
            }
        }

        // Gunakan $this->baseUrl
        $responseLastId = Http::get($this->baseUrl . '/aksara-dinamika/last-id');
        $lastId = $responseLastId->json()['last_id'] ?? 0;
        $newId = $lastId + 1;

        // Gunakan $this->baseUrl
        $responseIdb = Http::get($this->baseUrl . '/aksara-dinamika/last-idbuku');
        $lastIdb = $responseIdb->json()['last_idb'] ?? 0;
        $newIdb = (string) ($lastIdb + 1);

        $link = $request->sosmed;
        if ($link && !Str::startsWith($link, ['http://', 'https://'])) {
            $link = 'https://' . $link;
        }

        $currentDateTime = Carbon::now()->toDateTimeString();

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

        // Gunakan $this->baseUrl
        $response = Http::post($this->baseUrl . '/aksara-dinamika', $dataToSend);

        if ($response->successful()) {
            try {
                $emailTujuan = 'bagaskaragd@gmail.com';
                $dataEmail = $dataToSend + [
                    'judul' => $request->judul,
                    'pengarang' => $request->pengarang,
                ];

                Mail::to($emailTujuan)->send(new ReviewBerhasilMail($dataEmail));
                return redirect()->back()->with('success', 'Review berhasil disimpan dan notifikasi telah dikirim.');
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email notifikasi review: ' . $e->getMessage());
                return redirect()->back()->with('success', 'Review berhasil disimpan, tetapi notifikasi email gagal dikirim.');
            }
        } else {
            Log::error('Gagal menyimpan review ke API: ' . $response->body());
            return redirect()->back()->with('failed', 'Gagal menyimpan data review. Silakan coba lagi.')->withInput();
        }
    }

    public function viewperbaiki(Request $request)
    {
        $id = $request->id;
        $nim = $request->nim;
        $induk_buku = $request->induk_buku;

        // Gunakan $this->baseUrl dengan variabel
        $response = Http::get("{$this->baseUrl}/aksara-dinamika/detail-for-edit/{$id}/{$induk_buku}/{$nim}");

        $data = $response->successful() ? $response->json()['data'] : [];

        return view('Mahasiswa/formperbaikanaksara', [
            'data' => $data,
            'civitasId' => $nim
        ]);
    }

    public function viewAksaraDinamika1()
    {
        // Blok pengecekan session DIHAPUS, karena sudah ditangani oleh Middleware.
        $id_civitas = Session::get('civitas')['id_civitas'];

        // Gunakan $this->baseUrl dengan variabel
        $response = Http::get("{$this->baseUrl}/aksara-dinamika/aksara-user/{$id_civitas}");

        $data = $response->successful() ? $response->json()['data'] : [];

        return view('Mahasiswa/aksaradinamika', [
            'data' => $data,
            'civitasId' => $id_civitas
        ]);
    }

    public function viewAksaraDinamika2()
    {
                // Blok pengecekan session DIHAPUS, karena sudah ditangani oleh Middleware.
        $id_civitas = Session::get('civitas')['id_civitas'];

        // Gunakan $this->baseUrl dengan variabel
        $response = Http::get("{$this->baseUrl}/aksara-dinamika/aksara-user/{$id_civitas}");

        $data = $response->successful() ? $response->json()['data'] : [];

        return view('Dosen/aksaradinamika', [
            'data' => $data,
            'civitasId' => $id_civitas
        ]);
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
        // Gunakan $this->baseUrl
        $response = Http::get($this->baseUrl . '/buku/search', [
            'q' => request('q')
        ]);
        return response()->json($response->json());
    }

    public function karyawan_search()
    {
        // Gunakan $this->baseUrl
        $response = Http::get($this->baseUrl . '/karyawan/search', [
            'q' => request('q')
        ]);
        return response()->json($response->json());
    }

    public function perbaikan(Request $request, $id)
    {
        // Gunakan $this->baseUrl, ini juga menggantikan 'http://backend-pemustakaaward.test/api/...'
        $response = Http::put("{$this->baseUrl}/aksara-dinamika/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Berhasil update data');
        }
        return redirect()->back()->withErrors($response->json()['errors']);
    }

    public function destroy($id)
    {
        // Gunakan $this->baseUrl
        $response = Http::delete("{$this->baseUrl}/aksara-dinamika/{$id}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Berhasil hapus data');
        }
        return redirect()->back()->withErrors(['error' => 'Gagal hapus data']);
    }
}
