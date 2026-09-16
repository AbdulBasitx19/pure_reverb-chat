<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

//middleware auth lgaya hy : Sirf authenticated (login) user can access this route
 Route::get('/dashboard', function () { return view('layouts.pages.dashboard.index'); })->middleware('auth')->name('dashboard');


 //Get route -> pointing to LoginController function showLoginForm
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
//POST route -> Login process karna (Form submit hone par yeh call hoga).
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
//POST Route: Logout process karna
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

