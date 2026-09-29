<header class="sticky top-0 z-50 border-b border-line bg-paper/95 backdrop-blur" x-data="{ mobileOpen: false }">
    <div class="site-container flex min-h-24 items-center justify-between gap-6 py-4">
        <a href="{{ route('home') }}" wire:navigate class="shrink-0 text-ink" aria-label="M. Natsir Kongah - Home">
            <span class="block font-serif text-[1.35rem] font-semibold leading-none tracking-[-0.03em] sm:text-[1.45rem]">M. NATSIR KONGAH</span>
            <span class="mt-2 block max-w-32 font-mono text-[10px] font-medium leading-[1.3] tracking-[0.04em] text-slate-500 sm:max-w-none sm:text-[11px]">
                Financial Intelligence<br class="sm:hidden"> Hub
            </span>
        </a>

        <nav class="hidden items-center gap-8 lg:flex" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" wire:navigate class="font-mono text-[11px] font-semibold tracking-[0.04em] text-ink">Home</a>
            <a href="#about" class="font-mono text-[11px] font-medium tracking-[0.04em] text-slate-500 transition hover:text-ink">About</a>
            <a href="#featured-writings" class="font-mono text-[11px] font-medium tracking-[0.04em] text-slate-500 transition hover:text-ink">Articles</a>
            <a href="#spotlight" class="font-mono text-[11px] font-medium tracking-[0.04em] text-slate-500 transition hover:text-ink">Library</a>
            <a href="#featured-writings" class="font-mono text-[11px] font-medium tracking-[0.04em] text-slate-500 transition hover:text-ink">Cases</a>
            <a href="#contact" class="font-mono text-[11px] font-medium tracking-[0.04em] text-slate-500 transition hover:text-ink">Media</a>
            <a href="#contact" class="inline-flex min-h-10 items-center gap-2 bg-black px-5 py-2.5 font-mono text-[11px] font-semibold tracking-[0.04em] text-white transition hover:bg-ink">
                Contact
                <span aria-hidden="true">→</span>
            </a>
        </nav>

        <button
            type="button"
            class="inline-flex size-11 items-center justify-center border border-line bg-white text-ink lg:hidden"
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
        <div class="grid gap-1 font-mono text-xs">
            <a href="{{ route('home') }}" wire:navigate class="px-3 py-3 font-semibold text-ink" @click="mobileOpen = false">Home</a>
            <a href="#about" class="px-3 py-3 text-slate-600" @click="mobileOpen = false">About</a>
            <a href="#featured-writings" class="px-3 py-3 text-slate-600" @click="mobileOpen = false">Articles</a>
            <a href="#spotlight" class="px-3 py-3 text-slate-600" @click="mobileOpen = false">Library</a>
            <a href="#featured-writings" class="px-3 py-3 text-slate-600" @click="mobileOpen = false">Cases</a>
            <a href="#contact" class="px-3 py-3 text-slate-600" @click="mobileOpen = false">Media</a>
            <a href="#contact" class="mt-2 inline-flex min-h-11 items-center justify-between bg-black px-4 py-3 font-semibold text-white" @click="mobileOpen = false">
                Contact
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </nav>
</header>
