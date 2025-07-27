<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Menampilkan form reset password
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // Menyimpan password baru
    public function reset(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('status', 'Password berhasil diubah!');
    }
}
