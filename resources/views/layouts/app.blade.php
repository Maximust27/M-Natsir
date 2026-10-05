<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Financial Intelligence Hub M. Natsir Kongah — analisis, riset, dan pembelajaran tentang anti pencucian uang serta kejahatan keuangan terorganisir.">

        <title>{{ $title ?? 'M. Natsir Kongah | Financial Intelligence Hub' }}</title>
        <meta name="theme-color" content="#0e1c2f">

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="icon" href="{{ asset('favicon-32x32.png') }}" type="image/png" sizes="32x32">
        <link rel="icon" href="{{ asset('favicon-16x16.png') }}" type="image/png" sizes="16x16">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">

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
