<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class LoginController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.backend.base_url');
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

        $response = Http::get($this->baseUrl . '/civitas');
        $data = $response->json();
        $found = collect($data)->firstWhere('id_civitas', $credentials['nocivitas']);

        if ($found) {
            session(['civitas' => $found]);
            Session::put('nama', $found['nama']);
            Session::put('status', $found['status']); // Simpan status pengguna yang login (MHS, DOSEN, TENDIK)

            // Ini adalah logika untuk foto profil pengguna yang sedang login
            $gender = strtolower($found['jkel'] ?? '');
            $foto = 'assets/images/profile.png'; // default
            if ($gender == 'pria') {
                $foto = 'assets/images/Cylo.png';
            } elseif ($gender == 'wanita') {
                $foto = 'assets/images/Cyla.png';
            }
            Session::put('foto_profil', $foto);

            // LANGKAH PENTING: Cek Status Periode dari API dan proses data pemenang yang relevan
            try {
                $periodResponse = Http::get($this->baseUrl . '/periode/status-terkini');
                if ($periodResponse->successful()) {
                    $periodData = $periodResponse->json();

                    // --- START LOGIKA BARU UNTUK MENENTUKAN FOTO PEMENANG BERDASARKAN ROLE PENGGUNA YANG LOGIN ---
                    $selectedWinner = null;
                    $loggedInUserRole = Session::get('status'); // Ambil status pengguna yang sedang login

                    if (isset($periodData['status']) && $periodData['status'] === 'berakhir') {
                        // Pilih pemenang berdasarkan role pengguna yang login
                        if ($loggedInUserRole === 'MHS' && isset($periodData['winner_mahasiswa'])) {
                            $selectedWinner = $periodData['winner_mahasiswa'];
                        } elseif (($loggedInUserRole === 'DOSEN' || $loggedInUserRole === 'TENDIK') && isset($periodData['winner_dosen_tendik'])) {
                            $selectedWinner = $periodData['winner_dosen_tendik'];
                        }

                        // Jika pemenang ditemukan, tentukan URL fotonya berdasarkan jkel
                        if ($selectedWinner) {
                            $winnerFoto = asset('assets/images/profile.png'); // Foto pemenang default
                            $winnerGender = strtolower($selectedWinner['jkel'] ?? '');
                            if ($winnerGender == 'pria') {
                                $winnerFoto = asset('assets/images/Cylo.png');
                            } elseif ($winnerGender == 'wanita') {
                                $winnerFoto = asset('assets/images/Cyla.png');
                            }
                            $selectedWinner['foto'] = $winnerFoto; // Tambahkan/timpa kunci 'foto' di data pemenang
                        }
                    }
                    // Simpan pemenang yang sudah dipilih (dan diproses fotonya) ke dalam kunci 'winner' di periodData
                    // Ini yang akan dibaca oleh WinnerController
                    $periodData['winner'] = $selectedWinner;
                    // --- AKHIR LOGIKA BARU UNTUK FOTO PEMENANG BERDASARKAN ROLE ---

                    session(['period_data' => $periodData]); // Simpan data periode (termasuk foto pemenang yang relevan) ke sesi

                    // Jika periode berakhir, arahkan ke halaman pemenang
                    if (isset($periodData['status']) && $periodData['status'] === 'berakhir') {
                        return redirect()->route('winner.show');
                    }
                } else {
                    // Gagal mengambil data periode dari API, atau API tidak successful
                    Log::error('API /periode/status-terkini gagal atau respons tidak sukses. Status: ' . ($periodResponse->status() ?? 'unknown') . ' Body: ' . ($periodResponse->body() ?? 'no body'));
                    session(['period_data' => ['status' => 'aktif']]); // Asumsikan aktif agar tidak redirect ke winner
                }
            } catch (\Exception $e) {
                // Jika terjadi exception saat memanggil API
                Log::error('Exception saat mengambil status periode: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
                session(['period_data' => ['status' => 'aktif']]);
            }

            // Pengalihan setelah login, jika periode belum berakhir
            if ($found['status'] == 'MHS') {
                return redirect()->route('leaderboard1');
            } else if ($found['status'] == 'DOSEN' || $found['status'] == 'TENDIK') {
                return redirect()->route('leaderboard2');
            } else {
                return redirect()->route('leaderboard2'); // Fallback
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
