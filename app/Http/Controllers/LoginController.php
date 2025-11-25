<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Display login page
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Get credentials
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Check if user exists by username first
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan',
            ])->withInput($request->except('password'));
        }

        // Attempt to login
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil!');
        }

        // If login fails
        return back()->withErrors([
            'password' => 'Password salah',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear remember me cookie
        if (isset($_COOKIE['username'])) {
            setcookie('username', '', time() - 3600, '/');
        }

        return redirect('/login')->with('success', 'Anda telah berhasil logout');
    }
}
