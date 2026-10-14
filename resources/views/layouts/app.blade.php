<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#004AAD">
        <meta name="description" content="Nongkrong.id - Sistem Pesan Cepat dan Portal Karyawan Cafe Modern">

        <title>{{ isset($title) ? $title . ' | Nongkrong.id' : 'Nongkrong.id' }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/icont_noks.svg') }}">

        <!-- Google Fonts: Poppins & Sora -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="antialiased min-h-screen bg-white text-slate-900 selection:bg-brand-blue selection:text-white font-sans">
        {{ $slot }}

        <!-- Midtrans Snap JS (Sandbox / Production) -->
        <script
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"
        ></script>

        @livewireScripts
    </body>
</html>
