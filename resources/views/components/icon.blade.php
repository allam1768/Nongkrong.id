@props([
    'name',
    'size' => null,
    'type' => null, // stroke | fill
])

@php
    static $fileCache = [];
    static $vbCache = [];

    $icons = [
        // Built-in stroke icons (Lucide Icons Set - 100% Flat & Uniform)
        'coffee' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 2v2M14 2v2M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h12ZM6 2v2"/>',
        ],
        'check' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        ],
        'refresh' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
        ],
        'close' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        ],
        'x' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        ],
        'plus' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>',
        ],
        'minus' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>',
        ],
        'arrow-right' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>',
        ],
        'arrow-left' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>',
        ],
        'chevron-right' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/>',
        ],
        'chevron-left' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>',
        ],
        'trash' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>',
        ],
        'cash' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="20" height="12" x="2" y="6" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><circle cx="12" cy="12" r="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h.01M18 12h.01"/>',
        ],
        'banknote' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="20" height="12" x="2" y="6" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><circle cx="12" cy="12" r="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h.01M18 12h.01"/>',
        ],
        'qris' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="5" height="5" x="3" y="3" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect width="5" height="5" x="16" y="3" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect width="5" height="5" x="3" y="16" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-1"/>',
        ],
        'qr-code' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="5" height="5" x="3" y="3" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect width="5" height="5" x="16" y="3" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect width="5" height="5" x="3" y="16" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7"/>',
        ],
        'alert-circle' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" y1="16" x2="12.01" y2="16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'shield' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        ],
        'camera' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
        ],
        'search' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<circle cx="11" cy="11" r="8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.3-4.3"/>',
        ],
        'sparkles' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/>',
        ],
        'bot' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8V4m0 4a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4m0-12a4 4 0 0 0-4 4v4a4 4 0 0 0 4 4m-7-6h2m10 0h2m-9 0h.01m4 0h.01"/>',
        ],
        'kanban' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5v11M12 5v6M18 5v14"/>',
        ],
        'monitor' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="20" height="14" x="2" y="3" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="8" x2="16" y1="21" y2="21" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" x2="12" y1="17" y2="21" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'trending-up' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M19 9l-5 5-4-4-3 3"/>',
        ],
        'receipt' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1ZM14 8H8m8 4H8m5 4H8"/>',
        ],
        'shopping-bag' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4ZM3 6h18m-5 4a4 4 0 0 1-8 0"/>',
        ],
        'clock' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polyline points="12 6 12 12 16 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'logout' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14 5-5-5-5m5 5H9"/>',
        ],
        'calendar' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="18" height="18" x="3" y="4" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="16" x2="16" y1="2" y2="6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="8" x2="8" y1="2" y2="6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="3" x2="21" y1="10" y2="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'snowflake' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 4-2 2m0 0 2 2M8 6h8m0 0 2-2m-2 2 2 2M6 8l2 2m0 0-2 2m2-2v8m0 0-2 2m2-2 2 2m8-8 2 2m0 0-2 2m2-2H8"/>',
        ],
        'flame' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
        ],
        'wifi' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13a10 10 0 0 1 14 0M8.5 16.5a5 5 0 0 1 7 0M12 20h.01"/>',
        ],
        'zap' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'utensils' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2M15 11v11M5 2v8a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V2M7 12v10"/>',
        ],
        'user' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'edit' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>',
        ],
        'save' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2zM17 21v-8H7v8M7 3v5h8"/>',
        ],
        'smartphone' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="14" height="20" x="5" y="2" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" x2="12.01" y1="18" y2="18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'ticket' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><line x1="13" x2="13" y1="5" y2="19" stroke-dasharray="2" stroke-linecap="round" stroke-width="2"/>',
        ],
        'credit-card' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<rect width="20" height="14" x="2" y="5" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="2" x2="22" y1="10" y2="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],
        'bell' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
        ],
        'star' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
        ],

        // App logo text asset
        'NongkrongId' => [
            'viewBox' => '0 0 441 44',
            'type' => 'fill',
            'size' => 'w-32 h-auto',
            'file' => 'assets/img/NongkrongId.svg',
        ],
    ];

    $aliases = [
        'at-icons_eye' => 'eye',
        'at-icons_eye-closed' => 'eye-closed',
        'ci_paper-plane' => 'paper-plane',
        'send' => 'paper-plane',
        'cancel' => 'close',
        'close' => 'close',
        'card' => 'credit-card',
        'toleft' => 'arrow-left',
        'toright' => 'arrow-right',
        'coffee-bean' => 'coffee',
        'cofee' => 'coffee',
        'icont_noks' => 'coffee',
        'icont-noks' => 'coffee',
        'noks' => 'coffee',
        'logo-icon' => 'coffee',
        'star' => 'star',
        'Star-ic' => 'star',
        'cart' => 'shopping-bag',
        'arrow-left' => 'arrow-left',
        'chevron-left' => 'chevron-left',
        'chevron-right' => 'chevron-right',
        'nongkrong-id' => 'NongkrongId',
        'logo' => 'NongkrongId',
        'qr' => 'qris',
        'qrcode' => 'qris',
        'warning' => 'alert-circle',
        'alert' => 'alert-circle',
        'error' => 'alert-circle',
        'shield-check' => 'shield',
        'video' => 'camera',
        'webcam' => 'camera',
        'sync' => 'refresh',
        'reload' => 'refresh',
        'analytics' => 'trending-up',
        'sales' => 'trending-up',
        'log-out' => 'logout',
    ];

    $resolvedName = $aliases[$name] ?? $name;
    $icon = $icons[$resolvedName] ?? null;

    // Automatic fallback for any other SVGs in public/assets
    if (! $icon) {
        foreach (["assets/icn/{$name}.svg", "assets/img/{$name}.svg"] as $candidate) {
            $candidatePath = public_path($candidate);
            if (! file_exists($candidatePath)) {
                $candidatePath = base_path('public/' . $candidate);
            }
            if (file_exists($candidatePath)) {
                $icon = ['file' => $candidate, 'type' => 'fill'];
                if (! isset($vbCache[$candidate])) {
                    $raw = file_get_contents($candidatePath);
                    if (preg_match('/viewBox=[\"\x27]([^\"\x27]+)[\"\x27]/', $raw, $matches)) {
                        $vbCache[$candidate] = $matches[1];
                    } else {
                        $vbCache[$candidate] = '0 0 24 24';
                    }
                }
                $icon['viewBox'] = $vbCache[$candidate];
                break;
            }
        }
    }

    $body = '';
    if ($icon) {
        if (isset($icon['file'])) {
            $fileKey = $icon['file'];
            if (! isset($fileCache[$fileKey])) {
                $path = public_path($icon['file']);
                if (! file_exists($path)) {
                    $path = base_path('public/' . $icon['file']);
                }
                if (file_exists($path)) {
                    $raw = file_get_contents($path);
                    $start = strpos($raw, '>') + 1;
                    $end = strrpos($raw, '</svg>');
                    $fileCache[$fileKey] = trim(substr($raw, $start, $end - $start));
                } else {
                    $fileCache[$fileKey] = '';
                }
            }
            $body = $fileCache[$fileKey];
        } else {
            $body = $icon['body'] ?? '';
        }
    }

    $hasExplicitDimension = $attributes->has('class') && preg_match('/(^|\s)(w-|h-|size-)/', (string) $attributes->get('class'));
    $effectiveSize = $size ?? ($hasExplicitDimension ? '' : ($icon['size'] ?? 'w-5 h-5'));
    $renderType = $type ?? ($icon['type'] ?? 'stroke');
@endphp

@if ($icon && $body)
    <svg
        {{ $attributes->merge([
            'class' => "{$effectiveSize} inline-block shrink-0 transition-colors duration-150",
            'viewBox' => $icon['viewBox'] ?? '0 0 24 24',
            'fill' => $renderType === 'fill' ? 'currentColor' : 'none',
            'stroke' => $renderType === 'stroke' ? 'currentColor' : 'none',
        ]) }}
    >
        {!! $body !!}
    </svg>
@endif
