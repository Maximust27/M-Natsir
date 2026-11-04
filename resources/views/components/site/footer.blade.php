<footer class="border-t border-line bg-white">
    <div class="site-container grid gap-12 py-16 md:grid-cols-3 md:gap-16 lg:py-20">
        <div>
            <a
                href="{{ route('home') }}"
                wire:navigate
                class="inline-flex rounded-sm type-card-title text-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            >
                M. NATSIR KONGAH
            </a>
            <p class="mt-5 max-w-sm type-body text-ink">
                Pembelajar Anti Pencucian Uang &amp; Kejahatan Keuangan Terorganisir.
            </p>
            <p class="mt-7 max-w-lg type-support text-slate-500">
                © {{ now()->year }} M. Natsir Kongah. All rights reserved. Professional Financial Intelligence Disclaimer: The information provided herein is for scholarly and regulatory analysis purposes only.
            </p>
        </div>

        <div>
            <h2 class="type-eyebrow text-ink">
                Knowledge Pillars
            </h2>
            <div class="mt-5 grid gap-2 text-ink">
                <a href="{{ route('topics.show', ['slug' => 'anti-pencucian-uang']) }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Anti-Money Laundering</a>
                <a href="{{ route('topics.show', ['slug' => 'pencucian-uang-profesional']) }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Professional Money Laundering</a>
                <a href="{{ route('topics.show', ['slug' => 'intelijen-keuangan']) }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Financial Intelligence</a>
                <a href="{{ route('articles', ['category' => 'white-collar']) }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">White-Collar Crime</a>
                <a href="{{ route('articles', ['category' => 'future-crime']) }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Future Crime (AI &amp; Crypto)</a>
            </div>
        </div>

        <div>
            <h2 class="type-eyebrow text-ink">
                Navigasi Situs
            </h2>
            <div class="mt-5 grid gap-2 text-ink">
                <a href="{{ route('articles') }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Articles Archive</a>
                <a href="{{ route('library') }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Sources Library</a>
                <a href="{{ route('about') }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">About</a>
                <a href="{{ route('media') }}" wire:navigate class="type-action flex min-h-11 items-center transition hover:text-accent">Media &amp; Appearances</a>
                <a href="/#" class="type-action flex min-h-11 items-center transition hover:text-accent" aria-label="Admin Page (segera hadir)">Admin Page</a>
            </div>
        </div>
    </div>
</footer>
