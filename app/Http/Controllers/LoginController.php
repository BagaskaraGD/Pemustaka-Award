<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
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
        $response = Http::get($this->baseUrl .'/civitas');
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
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
