<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    // Show the login form (GET Request)
    public function showLoginForm()
    {
        return view('auth.pages.login');
    }



}
