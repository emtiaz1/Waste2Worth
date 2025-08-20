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
use App\Http\Controllers\ContactController;

Route::get('/', [WasteReportController::class, 'index'])->name('dashboard');


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
        Route::match(['get', 'post'], '/products', [AdminController::class, 'productStore'])->name('products');
        Route::get('/purchases', [AdminController::class, 'showPurchases'])->name('purchases');
        Route::post('/purchases/update-status', [AdminController::class, 'updatePurchaseStatus'])->name('purchases.update-status');
        Route::post('/purchases/{purchase}/confirm', [AdminController::class, 'confirmPurchase'])->name('purchases.confirm');
        Route::get('/events', [\App\Http\Controllers\AdminEventController::class, 'index'])->name('events');
        Route::post('/events', [\App\Http\Controllers\AdminEventController::class, 'store'])->name('events.store');
        Route::put('/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'destroy'])->name('events.delete');
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::match(['get', 'post'], '/products', [AdminController::class, 'productStore'])->name('products');
        Route::get('/volunteers', [\App\Http\Controllers\VolunteerController::class, 'index'])->name('volunteers');
        Route::get('/contact-messages', [\App\Http\Controllers\AdminMessageController::class, 'index'])->name('contact.messages');
        Route::get('/user-details', [AdminController::class, 'showUserDetails'])->name('user.details');
        Route::get('/user-detail/{id}', [AdminController::class, 'showUserDetail'])->name('user.detail');
        Route::get('/waste-reports', [AdminController::class, 'wasteReports'])->name('waste.reports');
        Route::post('/confirm-collection', [AdminController::class, 'confirmCollection'])->name('confirm.collection');
        Route::post('/reject-collection', [AdminController::class, 'rejectCollection'])->name('reject.collection');
    Route::get('/events', [\App\Http\Controllers\AdminEventController::class, 'index'])->name('events');
    Route::post('/events', [\App\Http\Controllers\AdminEventController::class, 'store'])->name('events.store');
    Route::put('/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'destroy'])->name('events.delete');
    Route::get('/home', [AdminController::class, 'home'])->name('home');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::match(['get', 'post'], '/products', [AdminController::class, 'productStore'])->name('products');
    Route::get('/volunteers', [\App\Http\Controllers\VolunteerController::class, 'index'])->name('volunteers');
        Route::get('/events', [\App\Http\Controllers\AdminEventController::class, 'index'])->name('events');
        Route::post('/events', [\App\Http\Controllers\AdminEventController::class, 'store'])->name('events.store');
        Route::put('/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'destroy'])->name('events.delete');
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::match(['get', 'post'], '/products', [AdminController::class, 'productStore'])->name('products');
        Route::get('/volunteers', [\App\Http\Controllers\VolunteerController::class, 'index'])->name('volunteers');
    });
});
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('success', 'Logged out successfully!');
})->name('logout');



Route::post('/signup', [LoginController::class, 'signup'])->name('signup.store');
Route::post('/signin', [LoginController::class, 'signin'])->name('signin');

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Public routes that don't require authentication
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::get('/leaderboard/api/data', [LeaderboardController::class, 'apiData'])->name('leaderboard.api');

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
    Route::post('volunteer', [\App\Http\Controllers\VolunteerController::class, 'store'])->name('volunteer.store');


    Route::get('/help', function () {
        return view('help');
    })->name('help');

    Route::get('/reportWaste', [WasteReportController::class, 'create'])->name('reportWaste');

    Route::get('/contact', function () {
        return view('contact');
    });

    Route::get('/leaderboard/user-rank', [LeaderboardController::class, 'getUserRank'])->name('leaderboard.user.rank');
    Route::post('/leaderboard/update-score', [LeaderboardController::class, 'updatePerformanceScore'])->name('leaderboard.update.score');


    Route::get('/reward', [App\Http\Controllers\RewardController::class, 'index'])->name('reward.index');
    Route::post('/reward/add-coins', [App\Http\Controllers\RewardController::class, 'addCoins'])->name('reward.add.coins');
    // Admin route to add coins
    Route::get('/admin/add-coins', function () {
        return view('admin.add-coins');
    })->name('admin.add.coins');
    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

    Route::get('/home', [WasteReportController::class, 'index'])->name('home');
    Route::get('/home/dashboard-data', [WasteReportController::class, 'getDashboardApiData'])->name('home.dashboard.data');
    Route::get('/home/community-activity', [WasteReportController::class, 'communityActivity'])->name('home.community.activity');
    Route::post('/waste-report', [WasteReportController::class, 'store'])->name('waste-report.store');
    Route::get('/wastereport/recent', [WasteReportController::class, 'getRecentReports']);
    Route::get('/wastereport/stats', [WasteReportController::class, 'getWasteStats']);
    Route::post('/request-collection', [WasteReportController::class, 'requestCollection']);
    Route::post('/submit-collection', [WasteReportController::class, 'submitCollection']);
});
