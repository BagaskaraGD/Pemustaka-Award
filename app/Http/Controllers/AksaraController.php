<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AksaraController extends Controller
{
    public function viewAksaraDinamika1()
    {
        return view('Mahasiswa/aksaradinamika');
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
        $response = Http::get('http://127.0.0.1:8000/api/buku');
        $data = $response->json();
        return response()->json($data); // return JSON, bukan view
    }
    public function karyawan_search()
    {
        $response = Http::get('http://127.0.0.1:8000/api/karyawan');
        $data = $response->json();
        return response()->json($data); // return JSON, bukan view
    }
    public function store(Request $request)
    {
        $request->validate([
            'kodebuku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'review' => 'required',
            'rekomendasi' => 'required',
            'sosmed' => 'required',
        ]);
        $civitas = session('civitas')['id_civitas'];

        $lastId = DB::connection('oracle')
            ->table('AKSARA_DINAMIKA')
            ->max('ID_AKSARA_DINAMIKA');

        $newId = $lastId + 1;

        // $lastIdb = DB::connection('oracle')
        //     ->table('AKSARA_DINAMIKA')
        //     ->max('ID_BUKU');

        // $newIdb = $lastIdb + 1;

        // Pastikan link sosmed valid
        $link = $request->sosmed;
        if (!Str::startsWith($link, ['http://', 'https://'])) {
            $link = 'https://' . $link;
        }
        //dd($request->all());

        $response = Http::post('http://127.0.0.1:8000/api/aksara-dinamika', [
            // Ini field yang sesuai database
            'id' => $newId,
            'nim' => $civitas,
            'id_buku' => "1",
            'induk_buku' => $request->kodebuku, // sama aja kalau ID_Buku = INDUK
            'review' => $request->review,
            'dosen_usulan' => $request->rekomendasi,
            'link_upload' => $request->sosmed,
        ]);
        //dd($response->json());

        if ($response->successful()) {
            return redirect()->back()->with('success', true);
        } else {
            return redirect()->back()->with('failed', true);
        }

        //return redirect()->back()->withErrors($response->json()['errors']);
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
