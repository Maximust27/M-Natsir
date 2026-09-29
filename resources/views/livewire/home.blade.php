<div>
    <section class="mx-auto w-full max-w-[108rem] border-b border-line" aria-labelledby="home-hero-title">
        <div class="relative h-[clamp(34rem,50vw,54rem)] overflow-hidden bg-slate-200">
            <img
                src="{{ asset('images/home-hero.webp') }}"
                alt="Potret M. Natsir Kongah berdiri di ruang kerja"
                width="1280"
                height="720"
                fetchpriority="high"
                class="absolute inset-0 h-full w-full object-cover object-[68%_center]"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/55 to-transparent sm:from-white/70 sm:via-white/20 lg:from-white/35 lg:via-white/5"></div>

            <div class="site-container relative z-10 flex h-full items-center">
                <div class="max-w-4xl py-12 lg:max-w-[58rem]">
                    <h1 id="home-hero-title" class="font-serif text-4xl font-semibold tracking-[-0.035em] text-black sm:text-5xl lg:text-[3.65rem] lg:leading-[1.05]">
                        M. NATSIR KONGAH
                    </h1>
                    <p class="mt-7 max-w-3xl font-serif text-lg italic leading-7 text-slate-500 sm:text-xl lg:text-[1.35rem]">
                        “Membaca jejak transaksi, menyingkap arsitektur kejahatan keuangan.”
                    </p>
                    <p class="mt-12 max-w-4xl text-sm leading-7 text-slate-700 sm:text-base lg:text-[1.02rem] lg:leading-8">
                        Analisis terkurasi dan perpustakaan intelijen yang didedikasikan untuk mengungkap jaringan pencucian uang profesional, menganalisis kerangka regulasi, dan mengantisipasi titik temu teknologi masa depan dan kejahatan finansial.
                    </p>
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="#featured-writings" class="inline-flex min-h-12 items-center justify-center bg-ink px-7 py-3 font-mono text-[11px] font-semibold tracking-[0.03em] text-white transition hover:bg-black sm:min-w-52">
                            Jelajahi Artikel Terbaru
                        </a>
                        <a href="#spotlight" class="inline-flex min-h-12 items-center justify-center border border-line bg-white px-7 py-3 font-mono text-[11px] font-semibold tracking-[0.03em] text-ink transition hover:bg-slate-50 sm:min-w-52">
                            AML Knowledge Library
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="knowledge-pillars" class="site-container border-b border-line py-7" aria-label="Knowledge pillars">
        <div class="scrollbar-none flex gap-3 overflow-x-auto pb-1">
            @foreach ($pillars as $key => $pillar)
                <button
                    type="button"
                    wire:key="pillar-{{ $key }}"
                    wire:click="selectPillar('{{ $key }}')"
                    @class([
                        'shrink-0 rounded-full px-5 py-2 font-mono text-[10px] font-semibold tracking-[0.025em] transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink',
                        'bg-ink text-white' => $activePillar === $key,
                        $pillar['classes'] => $activePillar !== $key,
                    ])
                    aria-pressed="{{ $activePillar === $key ? 'true' : 'false' }}"
                >
                    {{ $pillar['label'] }}
                </button>
            @endforeach
        </div>
    </section>

    <section id="featured-writings" class="site-container border-b border-line py-16 lg:py-20" aria-labelledby="featured-heading">
        <h2 id="featured-heading" class="font-serif text-3xl font-medium tracking-[-0.02em] text-black sm:text-4xl">Featured Writings</h2>

        <div class="mt-12 grid gap-6 md:grid-cols-3 lg:gap-8">
            @foreach ($featuredWritings as $index => $writing)
                <article wire:key="featured-writing-{{ $index }}" class="flex min-h-80 flex-col border border-line bg-white p-7 transition hover:border-slate-400 lg:min-h-[25rem] lg:p-8">
                    <div>
                        <span class="inline-flex rounded-full px-3 py-1 font-mono text-[9px] font-semibold tracking-[0.025em] {{ $writing['badgeClasses'] }}">
                            {{ $writing['badge'] }}
                        </span>
                        <h3 class="mt-4 text-xl font-semibold leading-[1.35] tracking-[-0.02em] text-black lg:text-[1.35rem]">
                            {{ $writing['title'] }}
                        </h3>
                        <p class="mt-5 line-clamp-4 text-sm leading-7 text-slate-500 lg:text-[0.95rem]">
                            {{ $writing['excerpt'] }}
                        </p>
                    </div>

                    <a href="#spotlight" class="mt-auto inline-flex items-center gap-2 pt-8 font-mono text-[10px] font-semibold tracking-[0.025em] text-ink hover:underline">
                        {{ $writing['action'] }}
                        <span aria-hidden="true">{{ $writing['external'] ? '↗' : '→' }}</span>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section id="spotlight" class="site-container border-b border-line py-16 lg:py-24" aria-labelledby="spotlight-heading">
        <div class="grid items-center gap-12 lg:grid-cols-[22rem_1fr] lg:gap-20 xl:gap-28">
            <div class="mx-auto flex aspect-[0.67] w-full max-w-72 flex-col justify-center border border-line bg-white px-10 text-center sm:max-w-80">
                <span class="font-mono text-[9px] font-semibold uppercase tracking-[0.12em] text-slate-500">Research Publication</span>
                <h3 class="mt-9 font-serif text-3xl font-medium leading-[1.05] tracking-[-0.025em] text-black sm:text-[2.2rem]">
                    Menelusuri<br>Aliran Dana<br>Gelap
                </h3>
                <span class="mt-8 font-mono text-[10px] font-medium tracking-[0.06em] text-slate-500">Teori &amp; Realitas</span>
            </div>

            <div>
                <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.06em] text-accent">Spotlight Buku / Riset Terbaru</p>
                <h2 id="spotlight-heading" class="mt-5 max-w-4xl font-serif text-3xl font-medium leading-tight tracking-[-0.025em] text-black sm:text-4xl lg:text-[2.55rem]">
                    Menelusuri Aliran Dana Gelap: Teori &amp; Realitas
                </h2>
                <p class="mt-7 max-w-4xl text-sm leading-7 text-slate-500 sm:text-base lg:leading-8">
                    A foundational text exploring the theoretical frameworks and empirical realities of modern money laundering. This comprehensive research delineates the evolution of illicit financial flows from rudimentary techniques to sophisticated, globally integrated typologies.
                </p>
                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#" class="inline-flex min-h-12 items-center justify-center bg-ink px-7 py-3 font-mono text-[10px] font-semibold tracking-[0.025em] text-white transition hover:bg-black">
                        Pelajari Struktur Buku
                    </a>
                    <a href="#" class="inline-flex min-h-12 items-center justify-center border border-line bg-white px-7 py-3 font-mono text-[10px] font-semibold tracking-[0.025em] text-ink transition hover:bg-slate-50">
                        Unduh Executive Summary
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="site-container py-16 lg:py-20" aria-label="About and contact">
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <article class="border-l-2 border-amber-600 bg-stone-50 p-8 sm:p-10 lg:min-h-80">
                <span class="font-serif text-4xl leading-none text-amber-600/50" aria-hidden="true">“</span>
                <blockquote class="mt-4 max-w-2xl font-serif text-lg italic leading-8 text-ink sm:text-xl">
                    “Two decades in financial intelligence have taught me that the complexity of the crime is always mirrored by the sophistication of its concealment. True oversight requires not just vigilance, but architectural understanding.”
                </blockquote>
                <a href="#" class="mt-9 inline-flex items-center gap-2 font-mono text-[10px] font-semibold tracking-[0.025em] text-ink hover:underline">
                    Baca Profil Lengkap
                    <span aria-hidden="true">→</span>
                </a>
            </article>

            <article id="contact" class="flex border border-line bg-white p-8 sm:p-10 lg:min-h-80 lg:flex-col lg:justify-center">
                <div>
                    <h2 class="text-xl font-semibold tracking-[-0.02em] text-black">Inquiries &amp; Consultations</h2>
                    <p class="mt-6 max-w-2xl text-sm leading-7 text-slate-500 sm:text-base">
                        Available for media interviews, regulatory consultations, and academic discourse regarding financial crime investigations and AML policy development.
                    </p>
                    <a href="#contact" class="mt-8 inline-flex min-h-12 items-center justify-center border border-black px-6 py-3 font-mono text-[10px] font-semibold tracking-[0.025em] text-black transition hover:bg-black hover:text-white">
                        Ajukan Pertanyaan / Undangan Media
                    </a>
                </div>
            </article>
        </div>
    </section>
</div>
