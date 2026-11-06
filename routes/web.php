<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Maullstore Frontend
|--------------------------------------------------------------------------
*/

// ===== HOME =====
Route::get('/', function () {
    return view('home');
})->name('home');

// ===== CUSTOMER AUTH =====
Route::middleware('guest')->group(function () {
    Route::get('/login',    [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login',   [\App\Http\Controllers\Auth\CustomerAuthController::class, 'login'])->name('customer.login.post');
    Route::get('/register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register',[\App\Http\Controllers\Auth\CustomerAuthController::class, 'register'])->name('customer.register.post');
});
Route::post('/logout', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->middleware('auth')->name('customer.logout');

// ===== TOP UP =====
Route::get('/topup', function () {
    $games = \App\Models\Game::where('is_active', true)
        ->where('available_topup', true)
        ->orderBy('sort_order')
        ->with(['topupProducts' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
        ->get();
    return view('topup.index', compact('games'));
});

Route::get('/topup/{slug}', function ($slug) {
    $game = \App\Models\Game::where('slug', $slug)
        ->where('is_active', true)
        ->where('available_topup', true)
        ->with(['topupProducts' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
        ->firstOrFail();
    return view('topup.form', compact('game'));
});
Route::post('/topup/checkout', [\App\Http\Controllers\CheckoutController::class, 'processTopup'])->name('topup.checkout');

// ===== JOKI =====
Route::get('/joki', function () {
    $games = \App\Models\Game::where('is_active', true)
        ->where('available_joki', true)
        ->orderBy('sort_order')
        ->with(['jokiTypes' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
        ->get();
    return view('joki.index', compact('games'));
});

Route::get('/joki/{slug}', function ($slug) {
    $game = \App\Models\Game::where('slug', $slug)
        ->where('is_active', true)
        ->where('available_joki', true)
        ->with([
            'jokiTypes'        => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'jokiTypes.tiers'  => fn($q) => $q->where('is_active', true)->orderBy('tier_order'),
            'jokiLoginMethods' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ])
        ->firstOrFail();
    return view('joki.form', compact('game'));
});
Route::post('/joki/checkout', [\App\Http\Controllers\CheckoutController::class, 'processJoki'])->name('joki.checkout');

// ===== CEK STATUS (fallback untuk guest) =====
Route::get('/cek-status', function () {
    return view('cek-status');
})->name('cek-status');
Route::post('/api/cek-status', [\App\Http\Controllers\CekStatusController::class, 'check'])->name('api.cek-status');

// ===== RIWAYAT TRANSAKSI (harus login) =====
Route::get('/riwayat-transaksi', [\App\Http\Controllers\TransactionHistoryController::class, 'index'])
    ->middleware('auth')
    ->name('transaction.history');

// ===== DETAIL TRANSAKSI =====
Route::get('/transaksi/{invoice}', [\App\Http\Controllers\PaymentSyncController::class, 'detail'])
    ->name('transaction.detail');

// ===== SINKRONISASI PEMBAYARAN (cek status ke Midtrans API) =====
Route::post('/api/payment/sync', [\App\Http\Controllers\PaymentSyncController::class, 'sync'])
    ->name('payment.sync');

// ===== WEBHOOK =====
Route::post('/webhook/midtrans', [\App\Http\Controllers\WebhookController::class, 'midtrans'])->name('webhook.midtrans');

// ===== CHAT API (FRONTEND) =====
Route::get('/api/chat/fetch', [\App\Http\Controllers\CustomerChatController::class, 'fetch'])->name('api.chat.fetch');
Route::post('/api/chat/send', [\App\Http\Controllers\CustomerChatController::class, 'send'])->name('api.chat.send');

// ===== FAQ & STATIC PAGES =====
Route::get('/faq', function () {
    return view('faq');
});

Route::get('/syarat-ketentuan', function () {
    return view('faq'); // placeholder — bisa dibuat halaman terpisah
});

Route::get('/kebijakan-privasi', function () {
    return view('faq'); // placeholder — bisa dibuat halaman terpisah
});

// ===== ADMIN PANEL =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Master Data
        Route::resource('games', \App\Http\Controllers\Admin\GameController::class);
        Route::resource('topup-products', \App\Http\Controllers\Admin\TopupProductController::class);
        Route::resource('joki-types', \App\Http\Controllers\Admin\JokiTypeController::class);
        Route::resource('joki-tiers', \App\Http\Controllers\Admin\JokiTierController::class);
        Route::resource('joki-login-methods', \App\Http\Controllers\Admin\JokiLoginMethodController::class);
        
        // Transactions
        Route::get('orders-topup/export', [\App\Http\Controllers\Admin\OrderTopupController::class, 'export'])->name('orders-topup.export');
        Route::resource('orders-topup', \App\Http\Controllers\Admin\OrderTopupController::class)->only(['index', 'show', 'update', 'destroy']);
        
        Route::get('orders-joki/export', [\App\Http\Controllers\Admin\OrderJokiController::class, 'export'])->name('orders-joki.export');
        Route::resource('orders-joki', \App\Http\Controllers\Admin\OrderJokiController::class)->only(['index', 'show', 'update', 'destroy']);
        
        // Chat
        Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
        Route::get('chat/{conversation}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('chat.show');
        Route::post('chat/{conversation}/reply', [\App\Http\Controllers\Admin\ChatController::class, 'reply'])->name('chat.reply');
        Route::post('chat/{conversation}/close', [\App\Http\Controllers\Admin\ChatController::class, 'close'])->name('chat.close');
        
        // Reports
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
        
        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        
        // Logs
        Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('notification-logs', [\App\Http\Controllers\Admin\NotificationLogController::class, 'index'])->name('notification-logs.index');
        
        // Admin Users
        Route::resource('admin-users', \App\Http\Controllers\Admin\AdminUserController::class)->except('show');

        // Kelola Customer Users
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except('show');
    });
});

