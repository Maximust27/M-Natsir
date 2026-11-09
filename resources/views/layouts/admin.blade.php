<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <meta name="theme-color" content="#0e1c2f">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Panel Admin | Financial Intelligence Hub')</title>

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-paper text-ink">
        <header class="border-b border-line bg-white">
            <div class="site-container flex min-h-20 flex-wrap items-center justify-between gap-x-8 gap-y-3 py-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 rounded-sm text-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink">
                    <img src="{{ asset('favicon.svg') }}" alt="" aria-hidden="true" width="48" height="48" class="size-12 shrink-0 rounded-md">
                    <span>
                        <span class="block font-serif text-[1.35rem] font-semibold leading-tight tracking-[-0.025em]">Financial Intelligence Hub</span>
                        <span class="type-meta mt-1 block text-slate-500">M. Natsir Kongah · Admin</span>
                    </span>
                </a>
                <nav class="flex flex-wrap items-center gap-x-5 gap-y-2" aria-label="Navigasi admin">
                    <a href="{{ route('home') }}" class="type-action inline-flex min-h-11 items-center gap-2 rounded-sm text-ink transition hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink">
                        <span aria-hidden="true">←</span> Kembali ke website
                    </a>
                    @if (auth()->user()?->is_admin === true)
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <x-ui.button type="submit" variant="outline" size="sm">Keluar</x-ui.button>
                        </form>
                    @endif
                </nav>
            </div>
        </header>

        <main id="admin-content" class="relative min-h-[calc(100svh-10rem)]">
            @yield('content')
        </main>

        <footer class="border-t border-line bg-white">
            <div class="site-container flex flex-wrap items-center justify-between gap-2 py-6">
                <p class="type-support text-slate-500">© {{ now()->year }} Financial Intelligence Hub</p>
                <p class="type-meta text-slate-500">Area pengelolaan privat</p>
            </div>
        </footer>
    </body>
</html>
