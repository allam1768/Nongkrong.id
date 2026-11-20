<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Terbatas | Nongkrong.id</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/icont_noks.svg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen bg-slate-50 text-slate-900 flex flex-col justify-center items-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 text-center">
        <!-- Badge Status -->
        <div class="w-16 h-16 mx-auto mb-6 bg-amber-50 rounded-2xl flex items-center justify-center border border-amber-200/60 shadow-inner">
            <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-amber-700 bg-amber-100/70 rounded-full mb-3">
            Perlindungan Hak Akses
        </span>

        <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight mb-2">
            Akses Tidak Diizinkan
        </h1>

        <p class="text-sm text-slate-500 leading-relaxed mb-6">
            Tautan halaman yang Anda buka dikhususkan untuk divisi lain. Untuk menjaga integritas operasional cafe, sistem membatasi akses sesuai peran akun Anda.
        </p>

        @auth
            @php
                $userRole = auth()->user()->role;
                $targetUrl = match ($userRole) {
                    'admin' => route('admin.dashboard'),
                    'cashier' => route('staff.cashier-register'),
                    'kitchen', 'barista' => route('staff.kitchen-board'),
                    'cleaner' => route('staff.cleaning-board'),
                    default => route('portal'),
                };
                $roleLabel = match ($userRole) {
                    'admin' => 'Administrator',
                    'cashier' => 'Kasir Walk-in',
                    'kitchen' => 'Dapur & KDS',
                    'barista' => 'Barista',
                    'cleaner' => 'Tim Pembersih',
                    default => 'Karyawan',
                };
            @endphp

            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-6 text-left text-xs text-slate-600">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-slate-400">Akun Masuk:</span>
                    <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Peran Aktif:</span>
                    <span class="font-bold text-brand-blue">{{ $roleLabel }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-2.5">
                <a href="{{ $targetUrl }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-brand-blue text-white text-sm font-semibold hover:bg-blue-700 transition active:scale-[0.99] shadow-md shadow-brand-blue/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Ruang Kerja Saya
                </a>

                <a href="{{ route('logout') }}" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Ganti Akun Lain
                </a>
            </div>
        @else
            <a href="{{ route('portal') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-brand-blue text-white text-sm font-semibold hover:bg-blue-700 transition active:scale-[0.99] shadow-md shadow-brand-blue/20">
                Kembali ke Portal Masuk
            </a>
        @endauth
    </div>
</body>
</html>