<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
        Route::get('/register', [AdminController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AdminController::class, 'register']);
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
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
Route::get('/event', [App\Http\Controllers\EventController::class, 'index'])->name('event');
Route::middleware(['auth'])->group(function () {
    // Reward products routes
    Route::get('/reward', [App\Http\Controllers\RewardController::class, 'index'])->name('reward');
    Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/history', [PurchaseController::class, 'history'])->name('purchase.history');

    Route::get('community', [App\Http\Controllers\CommunityController::class, 'index'])->name('community');

    Route::get('forum', [App\Http\Controllers\ForumController::class, 'index'])->name('forum.index');
    Route::post('forum', [App\Http\Controllers\ForumController::class, 'store'])->name('forum.store');


    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/picture', [App\Http\Controllers\ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');

    Route::get('volunteer', function () {
        return view('volunteer');
    })->name('volunteer');


    Route::get('/help', function () {
        return view('help');
    })->name('help');

    Route::get('/reportWaste', function () {
        return view('reportWaste');
    })->name('reportWaste');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/dashboard-data', [HomeController::class, 'getDashboardApiData'])->name('home.dashboard.data');
    Route::post('/request-collection', [HomeController::class, 'requestCollection'])->name('collection.request');
    Route::post('/submit-collection', [HomeController::class, 'submitCollection'])->name('collection.submit');

    Route::get('/contact', function () {
        return view('contact');
    });

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/api', [LeaderboardController::class, 'apiData'])->name('leaderboard.api');
    Route::get('/leaderboard/user-rank', [LeaderboardController::class, 'getUserRank'])->name('leaderboard.user.rank');
    Route::post('/leaderboard/update-score', [LeaderboardController::class, 'updatePerformanceScore'])->name('leaderboard.update.score');


    Route::get('/reward', [App\Http\Controllers\RewardController::class, 'index'])->name('reward.index');
    Route::post('/reward/add-coins', [App\Http\Controllers\RewardController::class, 'addCoins'])->name('reward.add.coins');
    // Admin route to add coins
    Route::get('/admin/add-coins', function () {
        return view('admin.add-coins');
    })->name('admin.add.coins');

    Route::post('/wastereport', [WasteReportController::class, 'store'])->name('wastereport.store');
    Route::get('/wastereport/recent', [WasteReportController::class, 'recent'])->name('wastereport.recent');
    Route::get('/wastereport/stats', [WasteReportController::class, 'stats'])->name('wastereport.stats');
    Route::get('/wastereport/community-activity', [WasteReportController::class, 'communityActivity'])->name('wastereport.community');
});