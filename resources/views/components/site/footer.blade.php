<footer class="border-t border-line bg-white">
    <div class="site-container grid gap-12 py-16 md:grid-cols-3 md:gap-16 lg:py-20">
        <div>
            <a
                href="{{ route('home') }}"
                wire:navigate
                class="inline-flex rounded-sm font-serif text-2xl font-semibold tracking-[-0.025em] text-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            >
                M. NATSIR KONGAH
            </a>
            <p class="mt-5 max-w-sm text-[15px] leading-7 text-slate-600">
                Pembelajar Anti Pencucian Uang &amp; Kejahatan Keuangan Terorganisir.
            </p>
            <p class="mt-7 max-w-lg text-xs leading-6 text-slate-500">
                © {{ now()->year }} M. Natsir Kongah. All rights reserved. Professional Financial Intelligence Disclaimer: The information provided herein is for scholarly and regulatory analysis purposes only.
            </p>
        </div>

        <div>
            <h2 class="font-mono text-xs font-semibold uppercase tracking-[0.08em] text-ink">
                Knowledge Pillars
            </h2>
            <div class="mt-5 grid gap-2 text-sm leading-6 text-slate-600">
                <a href="#knowledge-pillars" class="min-h-8 transition hover:text-ink">Anti-Money Laundering</a>
                <a href="#knowledge-pillars" class="min-h-8 transition hover:text-ink">Professional Money Laundering</a>
                <a href="#featured-writings" class="min-h-8 transition hover:text-ink">Financial Intelligence</a>
                <a href="#featured-writings" class="min-h-8 transition hover:text-ink">White-Collar Crime</a>
                <a href="#knowledge-pillars" class="min-h-8 transition hover:text-ink">Future Crime (AI &amp; Crypto)</a>
            </div>
        </div>

        <div>
            <h2 class="font-mono text-xs font-semibold uppercase tracking-[0.08em] text-ink">
                Navigasi Situs
            </h2>
            <div class="mt-5 grid gap-2 text-sm leading-6 text-slate-600">
                <a href="#featured-writings" class="min-h-8 transition hover:text-ink">Articles Archive</a>
                <a href="#spotlight" class="min-h-8 transition hover:text-ink">Sources Library</a>
                <a href="#about" class="min-h-8 font-semibold text-ink transition hover:text-black">About</a>
                <a href="#contact" class="min-h-8 transition hover:text-ink">Contact &amp; Media Hub</a>
            </div>
            <div class="mt-7 grid gap-2 text-sm leading-6 text-slate-600">
                <a href="#" class="min-h-8 transition hover:text-ink">Privacy Policy</a>
                <a href="#" class="min-h-8 transition hover:text-ink">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
