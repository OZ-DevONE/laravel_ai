<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return route('admin.users.index');
        }

        return '/home';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->update([
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
    }
}
