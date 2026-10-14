@props([
    'name',
    'size' => null,
    'type' => null, // stroke | fill
])

@php
    static $fileCache = [];
    static $vbCache = [];

    $icons = [
        // Built-in stroke icons
        'coffee' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>',
        ],
        'check' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        ],
        'close' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        ],
        'arrow-right' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>',
        ],
        'trash' => [
            'viewBox' => '0 0 24 24',
            'type' => 'stroke',
            'body' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>',
        ],

        // App icons (compact inline SVG bodies)
        'eye' => [
            'viewBox' => '0 0 16 16',
            'type' => 'fill',
            'body' => '<path d="M8.00132 3C11.2193 3 13.7893 6.235 14.6713 7.501C14.7744 7.64697 14.8298 7.82129 14.8298 8C14.8298 8.17871 14.7744 8.35303 14.6713 8.499C13.7903 9.765 11.2203 13 8.00132 13C4.78232 13 2.21132 9.765 1.33032 8.499C1.22723 8.35303 1.17187 8.17871 1.17188 8C1.17187 7.82129 1.22723 7.64697 1.33032 7.501C2.21232 6.235 4.78432 3.001 8.00132 3ZM8.00032 5C7.20467 5 6.44161 5.31607 5.879 5.87868C5.31639 6.44129 5.00032 7.20435 5.00032 8C5.00032 8.79565 5.31639 9.55871 5.879 10.1213C6.44161 10.6839 7.20467 11 8.00032 11C8.79597 11 9.55903 10.6839 10.1216 10.1213C10.6842 9.55871 11.0003 8.79565 11.0003 8C11.0003 7.20435 10.6842 6.44129 10.1216 5.87868C9.55903 5.31607 8.79597 5 8.00032 5Z" fill="currentColor"/>',
        ],
        'eye-closed' => [
            'viewBox' => '0 0 16 16',
            'type' => 'fill',
            'body' => '<path d="M13.2125 6.38605C13.3645 6.19111 13.5834 6.0594 13.8269 6.01637C14.0704 5.97333 14.3211 6.02201 14.5308 6.15302C14.7405 6.28402 14.8942 6.48806 14.9623 6.72574C15.0304 6.96343 15.008 7.2179 14.8995 7.44005L14.8495 7.53005V7.53305L14.8465 7.53705L14.8365 7.55005L14.8095 7.59305L14.7125 7.73805C14.6285 7.86105 14.5055 8.03205 14.3495 8.23505C14.1445 8.50005 13.8765 8.82105 13.5555 9.16605C13.6662 9.23989 13.761 9.33509 13.8345 9.44605L14.8345 10.9461C14.91 11.0552 14.9631 11.1784 14.9905 11.3083C15.018 11.4383 15.0193 11.5723 14.9944 11.7028C14.9695 11.8332 14.9188 11.9574 14.8454 12.068C14.772 12.1787 14.6772 12.2736 14.5667 12.3472C14.4562 12.4209 14.3322 12.4718 14.2018 12.497C14.0714 12.5221 13.9373 12.5211 13.8073 12.4939C13.6774 12.4667 13.5551 12.4139 13.4448 12.3385C13.3354 12.2632 13.2422 12.1668 13.1705 12.0551L12.1705 10.5551C12.1525 10.5282 12.1358 10.5005 12.1205 10.4721C11.2705 11.1151 10.2155 11.6921 9.00246 11.9081V14.0001C9.00246 14.2653 8.8971 14.5196 8.70957 14.7072C8.52203 14.8947 8.26768 15.0001 8.00246 15.0001C7.73724 15.0001 7.48289 14.8947 7.29535 14.7072C7.10782 14.5196 7.00246 14.2653 7.00246 14.0001V11.9081C5.78946 11.6931 4.73346 11.1151 3.88246 10.4721C3.86846 10.5001 3.85246 10.5277 3.83446 10.5551L2.83446 12.0551C2.68726 12.2757 2.45844 12.4289 2.19833 12.4808C1.93822 12.5328 1.66812 12.4792 1.44746 12.3321C1.2268 12.1849 1.07365 11.956 1.0217 11.6959C0.969755 11.4358 1.02326 11.1657 1.17046 10.9451L2.17046 9.44505C2.24371 9.33454 2.33821 9.2397 2.44846 9.16605C2.16969 8.86866 1.90507 8.55831 1.65546 8.23605C1.49447 8.0273 1.34104 7.81283 1.19546 7.59305L1.16746 7.55005L1.15846 7.53705L1.15546 7.53305V7.53105C1.0804 7.41982 1.02849 7.29462 1.00283 7.16291C0.977168 7.03119 0.978279 6.89566 1.0061 6.76439C1.03391 6.63311 1.08787 6.50878 1.16474 6.39879C1.24162 6.28881 1.33984 6.19542 1.45357 6.12419C1.56729 6.05296 1.69419 6.00535 1.8267 5.98419C1.95921 5.96303 2.09463 5.96876 2.22488 6.00103C2.35513 6.0333 2.47755 6.09146 2.58485 6.17204C2.69216 6.25262 2.78215 6.35397 2.84946 6.47005L2.85146 6.47405L2.86746 6.49805L2.93946 6.60605C3.36095 7.20792 3.84168 7.76604 4.37446 8.27205C5.36846 9.20605 6.63146 10.0001 8.00246 10.0001C9.37346 10.0001 10.6365 9.20605 11.6295 8.27105C12.1619 7.76499 12.6423 7.20687 13.0635 6.60505C13.0968 6.55705 13.1211 6.52105 13.1365 6.49705L13.1525 6.47305L13.1545 6.46905L13.2125 6.38605Z" fill="currentColor"/>',
        ],
        'paper-plane' => [
            'viewBox' => '0 0 43 43',
            'type' => 'stroke',
            'body' => '<path d="M18.3611 24.3891L26.993 15.7571M35.8209 10.4917L28.5356 34.1717C27.8819 36.2949 27.5559 37.3565 26.9912 37.7075C26.7508 37.8577 26.4779 37.9483 26.1954 37.9719C25.9128 37.9954 25.6287 37.9512 25.3667 37.8428C24.754 37.5881 24.2552 36.596 23.2613 34.6081L18.6479 25.3776C18.5535 25.1638 18.4384 24.9597 18.3041 24.7685C18.2114 24.6477 18.1037 24.5394 17.9835 24.446C17.7979 24.3172 17.6005 24.2062 17.3939 24.1147L8.14383 19.487C6.15774 18.4931 5.1638 17.9961 4.90908 17.3834C4.80043 17.1211 4.75603 16.8367 4.77958 16.5538C4.80313 16.2709 4.89394 15.9978 5.04446 15.7571C5.39537 15.1942 6.45699 14.8665 8.58024 14.2128L32.262 6.92745C33.931 6.41267 34.7646 6.15617 35.3293 6.36458C35.5718 6.45365 35.7921 6.59442 35.9748 6.77712C36.1575 6.95983 36.2983 7.18009 36.3873 7.42264C36.594 7.98552 36.3375 8.81914 35.8245 10.4864L35.8209 10.4917Z" stroke="currentColor" stroke-width="3.5625" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        'cofee' => [
            'viewBox' => '0 0 16 16',
            'type' => 'stroke',
            'body' => '<g clip-path="url(#clip0_4035_4352)"><path d="M2.70507 13.3C3.77869 14.3736 5.36673 14.8449 7.11984 14.6101C8.87294 14.3753 10.6475 13.4537 12.0532 12.0481C13.4588 10.6424 14.3804 8.86786 14.6152 7.11475C14.8499 5.36164 14.3787 3.77361 13.3051 2.69999C12.2315 1.62637 10.6434 1.15511 8.89031 1.38989C7.1372 1.62467 5.36263 2.54625 3.95699 3.9519C2.55134 5.35755 1.62976 7.13212 1.39498 8.88522C1.1602 10.6383 1.63145 12.2264 2.70507 13.3Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.2059 2.79997C13.3393 9.3333 2.67259 6.66663 2.80593 13.2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_4035_4352"><rect width="16" height="16" fill="currentColor"/></clipPath></defs>',
        ],
        'star' => [
            'viewBox' => '0 0 100 95',
            'type' => 'fill',
            'body' => '<path d="M49.9297 0L61.7167 35.9311H99.8602L69.0014 58.1378L80.7884 94.0689L49.9297 71.8622L19.071 94.0689L30.8579 58.1378L-0.000778198 35.9311H38.1427L49.9297 0Z" fill="currentColor"/>',
        ],

        // App icons mapped to local SVG assets (loaded on-demand and static-cached)
        'cancel' => [
            'viewBox' => '0 0 35 35',
            'type' => 'fill',
            'file' => 'assets/icn/cancel.svg',
        ],
        'card' => [
            'viewBox' => '0 0 16 16',
            'type' => 'fill',
            'file' => 'assets/icn/card.svg',
        ],
        'search' => [
            'viewBox' => '0 0 16 16',
            'type' => 'fill',
            'file' => 'assets/icn/search.svg',
        ],
        'toleft' => [
            'viewBox' => '0 0 39 22',
            'type' => 'fill',
            'file' => 'assets/icn/toleft.svg',
        ],
        'toright' => [
            'viewBox' => '0 0 40 22',
            'type' => 'fill',
            'file' => 'assets/icn/toright.svg',
        ],
        'icont_noks' => [
            'viewBox' => '0 0 101 107',
            'type' => 'fill',
            'file' => 'assets/img/icont_noks.svg',
        ],
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
        'coffee-bean' => 'cofee',
        'cart' => 'card',
        'arrow-left' => 'toleft',
        'chevron-left' => 'toleft',
        'chevron-right' => 'toright',
        'icont-noks' => 'icont_noks',
        'noks' => 'icont_noks',
        'logo-icon' => 'icont_noks',
        'nongkrong-id' => 'NongkrongId',
        'logo' => 'NongkrongId',
        'Star-ic' => 'star',
        'star-ic' => 'star',
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

