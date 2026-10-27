<!DOCTYPE html>
<html lang="id">
    <head>
        @php
            $seoSettings = config('seo');
            $seoPage = $seoSettings['pages'][request()->route()?->getName() ?? ''] ?? [];
            $seoTitle = $title ?? $seoSettings['default_title'];
            $seoDescription = $seoPage['description'] ?? $seoSettings['default_description'];
            $seoImage = asset($seoPage['image'] ?? $seoSettings['default_image']);
            $seoImageAlt = $seoPage['image_alt'] ?? $seoSettings['default_image_alt'];
            $seoCanonical = \App\Support\Seo\PageMetadata::canonical(request());
            $seoRobots = \App\Support\Seo\PageMetadata::robots(request());
            $seoUrl = $seoCanonical;
            $seoStructuredData = \App\Support\Seo\StructuredData::forPage(request(), $seoTitle, $seoDescription);
        @endphp

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $seoTitle }}</title>
        <meta name="description" content="{{ $seoDescription }}">
        <meta name="robots" content="{{ $seoRobots }}">
        <link rel="canonical" href="{{ $seoCanonical }}">

        <meta property="og:type" content="website">
        <meta property="og:locale" content="id_ID">
        <meta property="og:site_name" content="{{ $seoSettings['site_name'] }}">
        <meta property="og:title" content="{{ $seoTitle }}">
        <meta property="og:description" content="{{ $seoDescription }}">
        <meta property="og:url" content="{{ $seoUrl }}">
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:image:alt" content="{{ $seoImageAlt }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seoTitle }}">
        <meta name="twitter:description" content="{{ $seoDescription }}">
        <meta name="twitter:image" content="{{ $seoImage }}">
        <meta name="twitter:image:alt" content="{{ $seoImageAlt }}">

        <script type="application/ld+json">{!! json_encode($seoStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) !!}</script>

        <meta name="theme-color" content="#0e1c2f">

        <link rel="icon" href="{{ asset('favicon.ico') }}?v=mnk-1" sizes="any">
        <link rel="icon" href="{{ asset('favicon.svg') }}?v=mnk-1" type="image/svg+xml">
        <link rel="icon" href="{{ asset('favicon-32x32.png') }}?v=mnk-1" type="image/png" sizes="32x32">
        <link rel="icon" href="{{ asset('favicon-16x16.png') }}?v=mnk-1" type="image/png" sizes="16x16">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=mnk-1" sizes="180x180">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        <x-site.header />

        <main>
            {{ $slot }}
        </main>

        <x-site.footer />

        @livewireScripts
    </body>
</html>
