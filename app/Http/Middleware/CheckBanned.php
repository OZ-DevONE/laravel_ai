<?php

namespace App\Http\Middleware;

use App\Models\BannedUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        $isBanned = BannedUser::where('ip_address', $ipAddress)
            ->orWhere('user_agent', $userAgent)
            ->exists();

        if ($isBanned || (Auth::check() && $user->is_banned)) {
            if (Auth::check()) {
                Auth::logout();
                Session::flush();
            }
            abort(403, 'VAC Blocked -> send message https://t.me/KGB_OZ');
        }

        return $next($request);
    }
}
