<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfilController extends Controller
{

    public function viewProfil1()
    {
        $data = $this->getprofillevel();
        return view('Mahasiswa.profile', compact('data'));
    }
    public function viewProfil2()
    {
        $data = $this->getprofillevel();;
        return view('Dosen.profile', compact('data'));
    }

    public function getprofillevel()
    {
        $response1 = Http::get('http://127.0.0.1:8000/api/pembobotan/level1');
        $response2 = Http::get('http://127.0.0.1:8000/api/pembobotan/level2');
        $response3 = Http::get('http://127.0.0.1:8000/api/pembobotan/level3');

        // Gabungkan data dari ketiga response
        return [
            'level1' => $response1->json(),
            'level2' => $response2->json(),
            'level3' => $response3->json(),
        ];
        // Kirim data ke view

    }


}
