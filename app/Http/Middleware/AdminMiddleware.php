<?php

namespace App\Http\Middleware;

use App\Models\BannedUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        $isBanned = BannedUser::where('ip_address', $ipAddress)
            ->orWhere('user_agent', $userAgent)
            ->exists();

        if (!$user || $isBanned || !$user->is_admin) {
            abort(403, 'Запрещен');
        }

        return $next($request);
    }
}
