<?php

// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BannedUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('banned', ['only' => ['showRegistrationForm', 'register']]);
    }

    protected function validator(array $data)
    {
        $ipAddress = request()->ip();
        $userAgent = request()->header('User-Agent');

        $isBanned = BannedUser::where('ip_address', $ipAddress)
            ->orWhere('user_agent', $userAgent)
            ->exists();

        if ($isBanned) {
            abort(403, 'Ваш аккаунт был заблокирован');
        }

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
