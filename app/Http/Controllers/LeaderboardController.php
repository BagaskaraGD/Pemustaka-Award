<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    public function viewLeaderboard1()
    {
        $response = Http::get('http://127.0.0.1:8000/api/rekap-poin/leaderboard/mhs');
        $data = $response->json();

        $top5 = array_slice($data, 0, 5); // hanya ambil 5 teratas

        //dd($top5);

        return view('Mahasiswa.leaderboard', compact('top5'));
    }
    public function viewLeaderboard2()
    {
        $response = Http::get('http://127.0.0.1:8000/api/rekap-poin/leaderboard/dosen');
        $data = $response->json();

        $top5 = array_slice($data, 0, 5); // hanya ambil 5 teratas

        //dd($top5);
        return view('Dosen/leaderboard', compact('top5'));
    }
    public function viewdropdownperiode()
    {
        $response = Http::get('http://127.0.0.1:8000/api/periode');
        $data = $response->json();
        return response()->json($data); // return JSON, bukan view
    }

}
