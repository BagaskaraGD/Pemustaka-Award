<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckMahasiswaSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('civitas')) {
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        if (Session::get('status') !== 'MHS') {
            // Jika bukan mahasiswa, kembalikan ke halaman yang sesuai atau tampilkan error
            return redirect()->route('leaderboard2')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
