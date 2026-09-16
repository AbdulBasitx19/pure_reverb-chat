<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ 1. Auth facade import kiya

class LoginController extends Controller
{

    // Show the login form (GET Request)
    public function showLoginForm()
    {
        return view('auth.pages.login');
    }

    //handle login request (POST request)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password'); // sirf email and password nikala

        // Auth::attempt: Database mein check karo ke email/password match karte hain
        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate(); // Session regenerate karo (security ke liye)
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'email or password is wrong.',
        ])->onlyInput('email'); // Sirf email field ko wapis bheja (password nahi)
    }



}
