<div>
    <section class="mx-auto w-full max-w-[108rem] border-b border-line" aria-labelledby="home-hero-title">
        <div class="relative min-h-[38rem] overflow-hidden bg-slate-100 sm:min-h-[42rem] lg:min-h-[46rem]">
            <img
                src="{{ asset('images/home-hero.jpg') }}"
                alt="Potret M. Natsir Kongah berdiri di ruang kerja"
                width="1600"
                height="900"
                fetchpriority="high"
                class="absolute inset-0 h-full w-full object-cover object-[72%_center] sm:object-[70%_center]"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/75 to-white/15 sm:from-white/88 sm:via-white/55 sm:to-transparent lg:from-white/72 lg:via-white/30"></div>

            <div class="site-container relative z-10 flex min-h-[38rem] items-center sm:min-h-[42rem] lg:min-h-[46rem]">
                <div class="max-w-4xl py-14 lg:py-20">
                    <h1
                        id="home-hero-title"
                        class="max-w-3xl font-serif text-5xl font-semibold leading-[1.02] tracking-[-0.045em] text-black sm:text-6xl lg:text-[4.4rem]"
                    >
                        M. NATSIR KONGAH
                    </h1>

                    <p class="mt-6 max-w-[60ch] font-serif text-xl italic leading-8 text-slate-600 sm:text-2xl sm:leading-9">
                        “Membaca jejak transaksi, menyingkap arsitektur kejahatan keuangan.”
                    </p>

                    <p class="mt-9 max-w-[68ch] text-base leading-8 text-slate-700 sm:text-[17px]">
                        Analisis terkurasi dan perpustakaan intelijen yang didedikasikan untuk mengungkap jaringan pencucian uang profesional, menganalisis kerangka regulasi, dan mengantisipasi titik temu teknologi masa depan dan kejahatan finansial.
                    </p>

                    <div class="mt-9 flex flex-wrap gap-3 sm:gap-4">
                        <x-ui.button href="#featured-writings" variant="primary" size="lg">
                            Jelajahi Artikel Terbaru
                        </x-ui.button>

                        <x-ui.button href="#spotlight" variant="secondary" size="lg">
                            AML Knowledge Library
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="knowledge-pillars" class="site-container border-b border-line py-7" aria-label="Knowledge pillars">
        <div class="scrollbar-none flex gap-3 overflow-x-auto pb-1">
            @foreach ($pillars as $key => $pillar)
                <x-ui.filter-chip
                    wire:key="pillar-{{ $key }}"
                    wire:click="selectPillar('{{ $key }}')"
                    :active="$activePillar === $key"
                    :inactive-classes="$pillar['classes']"
                >
                    {{ $pillar['label'] }}
                </x-ui.filter-chip>
            @endforeach
        </div>
    </section>

    <section id="featured-writings" class="site-container border-b border-line py-16 lg:py-24" aria-labelledby="featured-heading">
        <x-ui.section-heading
            heading-id="featured-heading"
            title="Featured Writings"
            description="Pilihan tulisan dan analisis utama mengenai professional money laundering, aset digital, intelijen finansial, serta pola kejahatan keuangan yang terus berkembang."
        />

        <div class="mt-10 grid gap-6 md:grid-cols-3 lg:mt-12 lg:gap-8">
            @foreach ($featuredWritings as $index => $writing)
                <x-ui.card wire:key="featured-writing-{{ $index }}" class="flex min-h-[24rem] flex-col">
                    <div>
                        <x-ui.badge :class="$writing['badgeClasses']">
                            {{ $writing['badge'] }}
                        </x-ui.badge>

                        <h3 class="mt-5 text-xl font-semibold leading-[1.4] tracking-[-0.025em] text-black lg:text-[1.4rem]">
                            {{ $writing['title'] }}
                        </h3>

                        <p class="mt-5 line-clamp-4 text-base leading-7 text-slate-600">
                            {{ $writing['excerpt'] }}
                        </p>
                    </div>

                    <a
                        href="#spotlight"
                        class="mt-auto inline-flex min-h-11 items-center gap-2 self-start rounded-sm pt-7 text-sm font-semibold text-ink transition hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"
                    >
                        {{ $writing['action'] }}
                        <span aria-hidden="true">{{ $writing['external'] ? '↗' : '→' }}</span>
                    </a>
                </x-ui.card>
            @endforeach
        </div>
    </section>

    <section id="spotlight" class="site-container border-b border-line py-16 lg:py-24" aria-labelledby="spotlight-heading">
        <div class="grid items-center gap-12 lg:grid-cols-[22rem_1fr] lg:gap-20 xl:gap-28">
            <x-ui.card :interactive="false" class="mx-auto flex aspect-[0.67] w-full max-w-72 flex-col justify-center px-9 text-center sm:max-w-80">
                <span class="font-mono text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                    Research Publication
                </span>
                <h3 class="mt-8 font-serif text-3xl font-medium leading-[1.08] tracking-[-0.03em] text-black sm:text-[2.25rem]">
                    Menelusuri<br>Aliran Dana<br>Gelap
                </h3>
                <span class="mt-7 text-sm font-medium tracking-[0.02em] text-slate-500">
                    Teori &amp; Realitas
                </span>
            </x-ui.card>

            <div>
                <x-ui.section-heading
                    eyebrow="Spotlight Buku / Riset Terbaru"
                    heading-id="spotlight-heading"
                    title="Menelusuri Aliran Dana Gelap: Teori &amp; Realitas"
                    description="A foundational text exploring the theoretical frameworks and empirical realities of modern money laundering. This comprehensive research delineates the evolution of illicit financial flows from rudimentary techniques to sophisticated, globally integrated typologies."
                />

                <div class="mt-9 flex flex-wrap gap-3 sm:gap-4">
                    <x-ui.button href="#" variant="primary" size="lg">
                        Pelajari Struktur Buku
                    </x-ui.button>

                    <x-ui.button href="#" variant="secondary" size="lg">
                        Unduh Executive Summary
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="site-container py-16 lg:py-24" aria-label="About and contact">
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <article class="border border-line border-l-2 border-l-amber-600 bg-stone-50 p-8 sm:p-10 lg:min-h-[22rem]">
                <span class="font-serif text-5xl leading-none text-amber-700/40" aria-hidden="true">“</span>

                <blockquote class="mt-3 max-w-[58ch] font-serif text-xl italic leading-8 text-ink sm:text-[1.4rem] sm:leading-9">
                    “Two decades in financial intelligence have taught me that the complexity of the crime is always mirrored by the sophistication of its concealment. True oversight requires not just vigilance, but architectural understanding.”
                </blockquote>

                <a
                    href="#"
                    class="mt-8 inline-flex min-h-11 items-center gap-2 rounded-sm text-sm font-semibold text-ink hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"
                >
                    Baca Profil Lengkap
                    <span aria-hidden="true">→</span>
                </a>
            </article>

            <x-ui.card id="contact" :interactive="false" class="flex lg:min-h-[22rem] lg:flex-col lg:justify-center">
                <x-ui.section-heading
                    title="Inquiries & Consultations"
                    description="Available for media interviews, regulatory consultations, and academic discourse regarding financial crime investigations and AML policy development."
                />

                <div class="mt-8">
                    <x-ui.button href="#contact" variant="outline" size="lg">
                        Ajukan Pertanyaan / Undangan Media
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </section>
</div>
