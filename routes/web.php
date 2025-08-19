<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WasteReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('success', 'Logged out successfully!');
})->name('logout');
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/signup', [LoginController::class, 'signup'])->name('signup.store');
Route::post('/signin', [LoginController::class, 'signin'])->name('signin');

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// All other routes require authentication
Route::middleware(['auth'])->group(function () {

    Route::get('community', [App\Http\Controllers\CommunityController::class, 'index'])->name('community');

    Route::get('forum', [App\Http\Controllers\ForumController::class, 'index'])->name('forum.index');
    Route::post('forum', [App\Http\Controllers\ForumController::class, 'store'])->name('forum.store');


    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/picture', [App\Http\Controllers\ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');

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

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/contact', function () {
        return view('contact');
    });

    Route::get('/leaderboard', function () {
        return view('leaderboard');
    });

    Route::get('/event', function () {
        return view('event');
    })->name('event');

    Route::get('/reward', [App\Http\Controllers\RewardController::class, 'index'])->name('reward.index');
    Route::post('/reward/add-coins', [App\Http\Controllers\RewardController::class, 'addCoins'])->name('reward.add.coins');
    Route::post('/cart/add', [App\Http\Controllers\RewardController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove', [App\Http\Controllers\RewardController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/purchase/complete', [App\Http\Controllers\RewardController::class, 'completePurchase'])->name('purchase.complete');

    // Admin route to add coins
    Route::get('/admin/add-coins', function () {
        return view('admin.add-coins');
    })->name('admin.add.coins');

    Route::post('/wastereport', [WasteReportController::class, 'store'])->name('wastereport.store');
    Route::get('/wastereport/recent', [WasteReportController::class, 'recent'])->name('wastereport.recent');
    Route::get('/wastereport/stats', [WasteReportController::class, 'stats'])->name('wastereport.stats');
    Route::get('/wastereport/community-activity', [WasteReportController::class, 'communityActivity'])->name('wastereport.community');
});