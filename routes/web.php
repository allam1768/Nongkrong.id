<?php

use App\Http\Controllers\AiBaristaController;
use App\Http\Controllers\MidtransWebhookController;
use App\Livewire\Admin\AdminAiConfig;
use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminMenu;
use App\Livewire\Admin\AdminTable;
use App\Livewire\Admin\AdminUser;
use App\Livewire\Login\Portal;
use App\Livewire\SplashScreen;
use App\Livewire\Staff\CashierRegister;
use App\Livewire\Staff\CashierTableBoard;
use App\Livewire\Staff\CleaningBoard;
use App\Livewire\Staff\KitchenKanban;
use App\Livewire\User\CustomerOrder;
use App\Livewire\User\OrderTracking;
use App\Livewire\User\TableSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 1. Splash Screen
Route::get('/', SplashScreen::class)->name('splash');

// 2. Portal Pemilihan Akses (Karyawan / Pembeli)
Route::get('/portal', Portal::class)->name('portal');
Route::redirect('/login', '/portal')->name('login');

// 3. Pemilihan Meja
Route::get('/table', TableSelection::class)->name('table.selection');

// 4. Halaman Pemesanan Menu Pelanggan
Route::get('/order', CustomerOrder::class)->name('customer.order');

// 5. Live Tracking Pesanan Pelanggan (QRIS & Status Live)
Route::get('/order/track/{code}', OrderTracking::class)->name('order.tracking');

// 6. Staff Operasional, POS & Kebersihan
Route::prefix('staff')->middleware('auth')->group(function () {
    Route::get('/pos/kitchen-board', KitchenKanban::class)
        ->middleware('role:kitchen,barista,staff,admin')
        ->name('staff.kitchen-board');

    Route::get('/pos/tables', CashierTableBoard::class)
        ->middleware('role:cashier,staff,admin')
        ->name('staff.cashier-tables');

    Route::get('/pos/cashier-register', CashierRegister::class)
        ->middleware('role:cashier,staff,admin')
        ->name('staff.cashier-register');

    Route::get('/cleaning-board', CleaningBoard::class)
        ->middleware('role:cleaner,staff,admin')
        ->name('staff.cleaning-board');
});

// 7. Admin Dashboard & Operations
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', AdminAnalytics::class)->name('admin.dashboard');
    Route::get('/menus', AdminMenu::class)->name('admin.menus');
    Route::get('/tables', AdminTable::class)->name('admin.tables');
    Route::get('/tables/{table}/download-sticker', function (\App\Models\Table $table) {
        $path = \App\Services\QrCodeService::generateStickerForTable($table);
        $safeName = \Illuminate\Support\Str::slug($table->table_number);
        return response()->download($path, "stiker-meja-{$safeName}.png");
    })->name('admin.tables.download-sticker');
    Route::get('/users', AdminUser::class)->name('admin.users');
    Route::get('/ai-config', AdminAiConfig::class)->name('admin.ai-config');
});

// 8. Midtrans Webhook Callback
Route::post('/api/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

// 9. AI Barista Chat API
Route::post('/api/ai/barista-chat', [AiBaristaController::class, 'chat'])
    ->middleware('throttle:60,1')
    ->name('ai.barista-chat');

// 10. Staff & Admin Authentication Logout
Route::match(['get', 'post'], '/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('portal');
})->name('logout');
