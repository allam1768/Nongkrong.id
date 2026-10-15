<?php

use App\Http\Controllers\MidtransWebhookController;
use App\Livewire\Admin\AdminAiConfig;
use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminMenu;
use App\Livewire\Login\Portal;
use App\Livewire\SplashScreen;
use App\Livewire\Staff\CashierRegister;
use App\Livewire\Staff\KitchenKanban;
use App\Livewire\User\CustomerOrder;
use App\Livewire\User\OrderTracking;
use Illuminate\Support\Facades\Route;

// 1. Splash Screen
Route::get('/', SplashScreen::class)->name('splash');

// 2. Portal Pemilihan Akses (Karyawan / Pembeli)
Route::get('/portal', Portal::class)->name('portal');
Route::redirect('/login', '/portal')->name('login.portal');

// 3. Pemilihan Meja (Dialihkan ke Portal Overlay)
Route::redirect('/table', '/portal')->name('table.selection');

// 4. Halaman Pemesanan Menu Pelanggan
Route::get('/order', CustomerOrder::class)->name('customer.order');

// 5. Live Tracking Pesanan Pelanggan (QRIS & Status Live)
Route::get('/order/track/{code}', OrderTracking::class)->name('order.tracking');

// 6. Staff Operasional & POS
Route::prefix('staff/pos')->group(function () {
    Route::get('/kitchen-board', KitchenKanban::class)->name('staff.kitchen-board');
    Route::get('/cashier-register', CashierRegister::class)->name('staff.cashier-register');
});

// 7. Admin Dashboard & Operations
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', AdminAnalytics::class)->name('admin.dashboard');
    Route::get('/menus', AdminMenu::class)->name('admin.menus');
    Route::get('/ai-config', AdminAiConfig::class)->name('admin.ai-config');
});

// 8. Midtrans Webhook Callback
Route::post('/api/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

//
