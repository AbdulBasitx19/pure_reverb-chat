<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

 Route::get('/dashboard', function () { return view('layouts.pages.dashboard.index'); })->name('dashboard');


 //Get route -> pointing to LoginController function showLoginForm
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');


