<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [LoginController::class, 'signup'])->name('signup.store');
Route::post('/signin', [LoginController::class, 'signin'])->name('signin');

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('community', function () {
    return view('community');
})->name('community');

Route::get('volunteer', function () {
    return view('volunteer');
})->name('volunteer');

Route::get('event', function () {
    return view('event');
})->name('event');

Route::get('/help', function () {
    return view('help');
})->name('help');

Route::get('/reportWaste', function () {
    return view('reportWaste');
})->name('reportWaste');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/leaderboard', function () {
    return view('leaderboard');
});

Route::get('/event', function () {
    return view('event');
})->name('event');

Route::get('/reward', function () {
    return view('reward');
});