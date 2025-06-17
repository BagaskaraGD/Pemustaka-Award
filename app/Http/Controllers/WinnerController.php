<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WinnerController extends Controller
{
    /**
     * Menampilkan halaman pemenang.
     */
    public function show()
    {
        // Ambil data periode dari session yang disimpan saat login
        $periodData = session('period_data');

        // Jika tidak ada data periode atau statusnya bukan 'berakhir',
        // kembalikan ke halaman leaderboard utama.
        if (!$periodData || !isset($periodData['status']) || $periodData['status'] !== 'berakhir') {
            // Cek status user untuk redirect ke leaderboard yang sesuai
            $userStatus = session('civitas.status');
            if ($userStatus == 'MHS') {
                return redirect()->route('leaderboard1');
            } elseif ($userStatus == 'Dosen' || $userStatus == 'TENDIK') {
                return redirect()->route('leaderboard2');
            }
            // Fallback jika tidak ada status
            return redirect()->route('login');
        }

        // Kirim data pemenang dan periode ke view
        return view('winner', [
            'winner' => $periodData['winner'] ?? null,
            'periode' => $periodData['periode'] ?? null,
        ]);
    }
}
