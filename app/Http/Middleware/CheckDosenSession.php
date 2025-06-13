<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckDosenSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('civitas')) {
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        $status = Session::get('status');
        if (!in_array($status, ['DOSEN', 'TENDIK'])) {
            // Jika bukan dosen atau tendik, kembalikan ke halaman mahasiswa atau tampilkan error
            return redirect()->route('leaderboard1')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
