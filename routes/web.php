<?php

use App\Livewire\CustomerOrder;
use App\Livewire\Portal;
use App\Livewire\SplashScreen;
use App\Livewire\TableSelection;
use Illuminate\Support\Facades\Route;

// 1. Splash Screen
Route::get('/', SplashScreen::class)->name('splash');

// 2. Portal Pemilihan Akses (Karyawan / Pembeli)
Route::get('/portal', Portal::class)->name('portal');

// 3. Pemilihan Meja (Scan QR / Input Kode Meja)
Route::get('/table', TableSelection::class)->name('table.selection');

// 4. Halaman Pemesanan Menu Pelanggan
Route::get('/order', CustomerOrder::class)->name('customer.order');
