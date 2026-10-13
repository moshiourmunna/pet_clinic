<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    /** Login with phone OR email + password. */
    public function login(Request $request)
    {
        $data = $request->validate(['login' => ['required', 'string', 'max:150'], 'password' => ['required', 'string']]);
        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $value = $field === 'phone' ? preg_replace('/[^\d+]/', '', $data['login']) : $data['login'];

        if (! Auth::attempt([$field => $value, 'password' => $data['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['login' => __('These credentials do not match our records.')]);
        }
        $request->session()->regenerate();

        return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
