<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

 Route::get('/dashboard', function () { return view('layouts.pages.dashboard.index'); })->name('dashboard');

 Route::get('/login', function () {
    return view('auth.pages.login');
})->name('login');