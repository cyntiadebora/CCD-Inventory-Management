<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan form login
    public function login()
    {
        return view('auth.login');
    }

    // Proses login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek login dengan Auth::attempt
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (Auth::user()->role == 'cabin_crew') {
                return redirect('/cabin_crew');
            }

            return redirect('/dashboard');
        }

        // Jika gagal, balik ke halaman login dengan error
        return redirect()->back()->with('loginError', 'Incorrect email or password!')->withInput();

    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Successfully Logged Out');
    }
}
