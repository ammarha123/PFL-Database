<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function loginForm()
    {
        return view('auth.login');
    }

    // Handle login logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout logic
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out.');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bod' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'category' => 'required|string',
            'position' => 'required|string',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $validated['name'],
            'bod' => $validated['bod'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'category' => $validated['category'],
            'position' => $validated['position'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
}
