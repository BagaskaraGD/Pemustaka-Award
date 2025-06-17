<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPeriodStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $periodData = session('period_data');

        // Cek jika periode sudah berakhir
        if (isset($periodData['status']) && $periodData['status'] === 'berakhir') {
            // Izinkan akses hanya ke halaman pemenang dan proses logout
            if (!$request->routeIs('winner.show') && !$request->routeIs('logout')) {
                return redirect()->route('winner.show');
            }
        }

        return $next($request);
    }
}
