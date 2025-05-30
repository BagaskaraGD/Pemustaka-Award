<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function viewLoginForm()
    {
        return view('formlogin');
    }
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nocivitas' => ['required'],
        ]);
        //dd($credentials);
        $response = Http::get('http://127.0.0.1:8000/api/civitas');
        $data = $response->json();
        $found = collect($data)->firstWhere('id_civitas', $credentials['nocivitas']);
        //dd($data);

        if ($found) {
            //dd($found);
            session(['civitas' => $found]);
            Session::put('nama', $found['nama']);
            Session::put('status', $found['status']);

            $gender = strtolower($found['jkel'] ?? '');
            //dd($gender);
            $foto = 'assets/images/profile.png'; // default

            if ($gender == 'pria') {
                $foto = 'assets/images/Cylo.png';
            } elseif ($gender == 'wanita') {
                $foto = 'assets/images/Cyla.png';
            }

            Session::put('foto_profil', $foto);

            if ($found['status'] == 'MHS') {
                Session::put('status', $found['status']);
                return redirect()->route('leaderboard1');
            } else if ($found['status'] == 'DOSEN' || $found['status'] == 'TENDIK') {
                Session::put('status', $found['status']);
                return redirect()->route('leaderboard2');
            } else {
                Session::put('status', $found['status']);
                return redirect()->route('leaderboard2');
            }
        }

        return back()->withErrors([
            'nocivitas' => 'The provided credentials do not match our records.',
        ])->onlyInput('nocivitas');
    }
}
