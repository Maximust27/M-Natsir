<header class="sticky top-0 z-50 border-b border-line bg-paper/95 backdrop-blur" x-data="{ mobileOpen: false }">
    <div class="site-container flex min-h-20 items-center justify-between gap-6 py-3">
        <a
            href="{{ route('home') }}"
            wire:navigate
            class="shrink-0 rounded-sm text-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            aria-label="M. Natsir Kongah - Home"
        >
            <span class="block font-serif text-[1.45rem] font-semibold leading-none tracking-[-0.035em] sm:text-[1.65rem]">
                M. NATSIR KONGAH
            </span>
            <span class="mt-2 block font-mono text-[10px] font-medium leading-[1.45] tracking-[0.045em] text-slate-500 sm:text-[11px]">
                Financial Intelligence Hub
            </span>
        </a>

        <nav class="hidden items-center gap-6 lg:flex xl:gap-7" aria-label="Navigasi utama">
            <x-ui.nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                Home
            </x-ui.nav-link>
            <x-ui.nav-link href="#about">About</x-ui.nav-link>
            <x-ui.nav-link href="#featured-writings">Articles</x-ui.nav-link>
            <x-ui.nav-link href="#spotlight">Library</x-ui.nav-link>
            <x-ui.nav-link href="#featured-writings">Cases</x-ui.nav-link>
            <x-ui.nav-link href="#contact">Media</x-ui.nav-link>

            <x-ui.button href="#contact" variant="primary" size="md" class="ml-2">
                Contact
                <span aria-hidden="true">→</span>
            </x-ui.button>
        </nav>

        <button
            type="button"
            class="inline-flex size-12 items-center justify-center rounded-sm border border-line bg-white text-ink transition hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink lg:hidden"
            @click="mobileOpen = ! mobileOpen"
            :aria-expanded="mobileOpen"
            aria-controls="mobile-navigation"
            aria-label="Buka navigasi"
        >
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path x-show="! mobileOpen" d="M4 7h16M4 12h16M4 17h16" />
                <path x-show="mobileOpen" x-cloak d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <nav
        id="mobile-navigation"
        class="site-container border-t border-line py-4 lg:hidden"
        x-show="mobileOpen"
        x-cloak
        x-transition.opacity.duration.150ms
        @click.outside="mobileOpen = false"
        aria-label="Navigasi seluler"
    >
        <div class="grid gap-1">
            <x-ui.nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate class="w-full justify-start px-3" @click="mobileOpen = false">
                Home
            </x-ui.nav-link>
            <x-ui.nav-link href="#about" class="w-full justify-start px-3" @click="mobileOpen = false">About</x-ui.nav-link>
            <x-ui.nav-link href="#featured-writings" class="w-full justify-start px-3" @click="mobileOpen = false">Articles</x-ui.nav-link>
            <x-ui.nav-link href="#spotlight" class="w-full justify-start px-3" @click="mobileOpen = false">Library</x-ui.nav-link>
            <x-ui.nav-link href="#featured-writings" class="w-full justify-start px-3" @click="mobileOpen = false">Cases</x-ui.nav-link>
            <x-ui.nav-link href="#contact" class="w-full justify-start px-3" @click="mobileOpen = false">Media</x-ui.nav-link>

            <x-ui.button href="#contact" class="mt-2 w-full justify-between" @click="mobileOpen = false">
                Contact
                <span aria-hidden="true">→</span>
            </x-ui.button>
        </div>
    </nav>
</header>
