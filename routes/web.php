<?php

use App\Http\Controllers\Admin\AlbumController as AdminAlbumController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VotePollController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::get('/members/{member:slug}', [MemberController::class, 'show'])->name('members.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/store', [AlbumController::class, 'index'])->name('store.index');
Route::get('/store/{album:slug}', [AlbumController::class, 'show'])->name('store.show');

Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{forumPost}', [ForumController::class, 'show'])->name('forum.show');

/*
|--------------------------------------------------------------------------
| GUEST ONLY (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED (semua role: admin, staff, member)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Pembayaran (SIMULASI — tanpa gateway asli)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/checkout/{order}/pay', [CheckoutController::class, 'confirm'])->name('checkout.confirm');

    // Pesanan
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    // Review album (hanya user terautentikasi)
    Route::post('/store/{album:slug}/review', [AlbumController::class, 'storeReview'])->name('store.review');

    // Komentar berita
    Route::post('/news/{news:slug}/comments', [NewsController::class, 'storeComment'])->name('news.comments.store');

    // Forum (UGC)
    Route::get('/forum-create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::post('/forum/{forumPost}/comments', [ForumController::class, 'storeComment'])->name('forum.comments.store');
    Route::delete('/forum/{forumPost}', [ForumController::class, 'destroy'])->name('forum.destroy');

    // Likes (universal: forum post & comment)
    Route::post('/likes/toggle', [LikeController::class, 'toggle'])->name('likes.toggle');

    // Voting
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::post('/voting/{votePoll}/vote', [VotingController::class, 'vote'])->name('voting.vote');
    Route::get('/voting/{votePoll}/results', [VotingController::class, 'results'])->name('voting.results');
});

/*
|--------------------------------------------------------------------------
| ADMIN & STAFF DASHBOARD (role:admin,staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD konten
    Route::resource('albums', AdminAlbumController::class)->except(['show']);
    Route::resource('news', AdminNewsController::class)->except(['show']);
    Route::resource('members', AdminMemberController::class)->except(['show']);

    // Voting
    Route::get('/voting', [VotePollController::class, 'index'])->name('voting.index');
    Route::get('/voting/create', [VotePollController::class, 'create'])->name('voting.create');
    Route::post('/voting', [VotePollController::class, 'store'])->name('voting.store');
    Route::delete('/voting/{votePoll}', [VotePollController::class, 'destroy'])->name('voting.destroy');

    // Manajemen pesanan
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.status');

    // Ekspor pesanan (CSV native)
    Route::get('/export/orders', [ExportController::class, 'ordersCsv'])->name('export.orders');

    // Log aktivitas
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY (role:admin murni) — manajemen user
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.role');
    Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle');
});
