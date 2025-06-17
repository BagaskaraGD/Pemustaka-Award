<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL', 'http://127.0.0.1:8000/api');
    }

    public function showLoginForm()
    {
        return view('formlogin');
    }
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nocivitas' => ['required'],
        ]);
        //dd($credentials);
        $response = Http::get($this->baseUrl . '/civitas');
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

            // LANGKAH BARU: Cek Status Periode dari API
            try {
                $periodResponse = Http::get($this->baseUrl . '/periode/status-terkini');
                if ($periodResponse->successful()) {
                    $periodData = $periodResponse->json();
                    session(['period_data' => $periodData]); // Simpan di session

                    // Jika periode berakhir, arahkan ke halaman pemenang
                    if (isset($periodData['status']) && $periodData['status'] === 'berakhir') {
                        return redirect()->route('winner.show');
                    }
                } else {
                    // Gagal mengambil data periode, lanjutkan ke flow normal
                    session(['period_data' => ['status' => 'aktif']]);
                }
            } catch (\Exception $e) {
                // Jika API tidak terjangkau, asumsikan periode aktif
                session(['period_data' => ['status' => 'aktif']]);
            }

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
        Session::flush();
        return redirect()->route('login');
    }
}
