<div>
    <section
        class="mx-auto w-full border-b border-line"
        aria-labelledby="home-hero-title"
        data-home-hero
    >
        {{-- On phones and tablets the portrait stays above the copy.
             On desktop the wide headline reaches toward the center, while narrower body copy
             and the right-side portrait preserve a clear reading/portrait separation. --}}
        <div class="relative overflow-hidden bg-paper lg:min-h-[calc(100svh-5rem)]">
            @php($homeWebpSrcset = \App\Support\Media\HeroImages::webpSrcset('home-hero'))
            <picture class="block">
                @if ($homeWebpSrcset !== '')
                    <source type="image/webp" srcset="{{ $homeWebpSrcset }}" sizes="100vw">
                @endif
                <img
                    src="{{ asset('images/home-hero.jpeg') }}"
                    alt="Potret M. Natsir Kongah berdiri di ruang kerja"
                    width="2730"
                    height="1536"
                    loading="eager"
                    decoding="async"
                    fetchpriority="high"
                    class="block aspect-[16/9] w-full object-cover object-[70%_center] sm:aspect-[2/1] lg:absolute lg:inset-0 lg:h-full lg:aspect-auto lg:object-[72%_center]"
                    data-home-hero-image
                >
            </picture>
            <div class="pointer-events-none absolute inset-0 hidden bg-gradient-to-r from-white/95 via-white/70 to-transparent lg:block xl:from-white/90 xl:via-white/45"></div>

            <div class="site-container relative z-10 flex items-center lg:min-h-[calc(100svh-5rem)]">
                <div class="w-full py-10 sm:py-12 lg:max-w-[38rem] lg:py-16 xl:max-w-[49rem] 2xl:max-w-[58rem]">
                    <h1
                        id="home-hero-title"
                        class="max-w-[22ch] font-serif text-[2.5rem] font-semibold leading-[1.07] tracking-[-0.035em] text-ink sm:max-w-[26ch] sm:text-[3.2rem] lg:max-w-none lg:text-[2.95rem] xl:text-[3.15rem] 2xl:text-[3.35rem]"
                        data-home-hero-item
                    >
                        MEMBONGKAR PENCUCIAN UANG DAN KEJAHATAN KERAH PUTIH
                    </h1>

                    <p
                        class="type-lead mt-6 max-w-[62ch] text-ink lg:max-w-[34rem] xl:max-w-[39rem]"
                        data-home-hero-item
                    >
                        Analisis terkurasi dan perpustakaan intelijen keuangan sebagai ruang belajar bersama M. Natsir Kongah. Pelajari kasus pencucian uang profesional, jejak transaksi, kerangka regulasi, dan tantangan kejahatan finansial melalui artikel serta rujukan sumber asli yang dapat ditelusuri.
                    </p>

                    <div class="mt-9 flex flex-wrap gap-3 sm:gap-4" data-home-hero-item>
                        <x-ui.button href="#featured-writings" variant="primary" size="lg">
                            Jelajahi Artikel Terbaru
                            <span aria-hidden="true">→</span>
                        </x-ui.button>

                        <x-ui.button :href="route('library')" variant="secondary" size="lg" wire:navigate>
                            Buka Knowledge Library
                            <span aria-hidden="true">→</span>
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="site-container border-b border-line py-14 lg:py-18" aria-labelledby="knowledge-pillars-heading">
        <p class="type-eyebrow text-accent">Peta Kajian</p>
        <h2 id="knowledge-pillars-heading" class="type-section-title mt-3 text-ink">
            Mulai dari topik utama
        </h2>
        <p class="type-body mt-4 max-w-4xl text-ink">
            Pilih bidang kajian untuk membaca konteks, pertanyaan kunci, dan rujukan publikasi asli yang saling terkait.
        </p>
        <nav class="mt-9 grid gap-5 md:grid-cols-3" aria-label="Topik kajian utama">
            @foreach (\App\Support\Content\TopicCatalog::all() as $topic)
                <a
                    href="{{ route('topics.show', ['slug' => $topic['slug']]) }}"
                    wire:navigate
                    class="group flex h-full flex-col border border-line bg-white p-6 transition hover:border-slate-400 hover:shadow-sm sm:p-7"
                >
                    <h3 class="type-card-title text-ink group-hover:text-accent">{{ $topic['heading'] }}</h3>
                    <p class="type-body mt-4 text-ink">{{ $topic['teaser'] }}</p>
                    <span class="type-action mt-auto inline-flex min-h-11 items-center pt-5 font-bold text-ink group-hover:text-accent">Jelajahi topik →</span>
                </a>
            @endforeach
        </nav>
    </section>

    <section id="featured-writings" class="site-container border-b border-line py-16 lg:py-24" aria-labelledby="featured-heading">
        <x-ui.section-heading
            heading-id="featured-heading"
            eyebrow="Tulisan Terbaru"
            title="Featured Writings"
            description="Tiga publikasi primer terbaru M. Natsir Kongah yang juga menjadi bagian dari arsip Articles. Data Home dan Articles menggunakan katalog yang sama agar tetap konsisten."
        />

        <div class="mt-10 grid gap-6 md:grid-cols-3 lg:mt-12 lg:gap-8">
            @foreach ($featuredWritings as $index => $writing)
                <x-ui.card wire:key="featured-writing-{{ md5($writing['title']) }}" class="group flex min-h-[25rem] flex-col">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <x-ui.badge :class="$writing['badgeClasses']">
                                {{ $writing['categoryLabel'] }}
                            </x-ui.badge>
                        </div>

                        <p class="type-meta mt-4 font-mono font-medium uppercase tracking-[0.045em] text-slate-500">
                            {{ $writing['source'] }}
                            <span class="mx-1 text-slate-300" aria-hidden="true">•</span>
                            {{ $writing['date'] }}
                        </p>

                        <h3 class="type-card-title mt-5 text-black">
                            <a
                                href="{{ $writing['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="rounded-sm transition group-hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
                            >
                                {{ $writing['title'] }}
                            </a>
                        </h3>

                        <p class="type-body mt-5 line-clamp-5 text-ink">
                            {{ $writing['excerpt'] }}
                        </p>
                    </div>

                    <a
                        href="{{ $writing['url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="type-action mt-auto inline-flex min-h-11 items-center gap-2 self-start rounded-sm pt-7 font-semibold text-ink transition hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"
                    >
                        {{ $writing['accessLabel'] }}
                        <span aria-hidden="true">→</span>
                    </a>
                </x-ui.card>
            @endforeach
        </div>
    </section>

    @if ($spotlight !== null)
    <section id="spotlight" class="site-container border-b border-line py-16 lg:py-24" aria-labelledby="spotlight-heading">
        <div class="grid items-center gap-12 lg:grid-cols-[22rem_1fr] lg:gap-20 xl:gap-28">
            <x-ui.card :interactive="false" class="mx-auto flex aspect-[0.72] w-full max-w-72 flex-col justify-between px-8 py-9 sm:max-w-80">
                <div>
                    <span class="type-meta font-mono font-semibold uppercase tracking-[0.12em] text-slate-500">
                        Primary Source
                    </span>
                    <h3 class="type-card-title mt-8 text-black">
                        {{ $spotlight['title'] }}
                    </h3>
                </div>

                <div class="border-t border-line pt-6">
                    <span class="type-support block font-semibold text-ink">{{ $spotlight['source'] }}</span>
                    <span class="type-meta mt-1 block font-mono uppercase tracking-[0.05em] text-slate-500">{{ $spotlight['date'] }}</span>
                </div>
            </x-ui.card>

            <div>
                <x-ui.section-heading
                    eyebrow="Archive Spotlight"
                    heading-id="spotlight-heading"
                    :title="$spotlight['title']"
                    :description="$spotlight['excerpt']"
                />

                <p class="type-body mt-6 max-w-[72ch] text-slate-500">
                    Spotlight ini menggunakan publikasi primer yang dapat dibuka langsung pada sumber aslinya—bukan judul buku atau riset placeholder.
                </p>

                <div class="mt-9 flex flex-wrap gap-3 sm:gap-4">
                    <x-ui.button
                        :href="$spotlight['url']"
                        target="_blank"
                        rel="noopener noreferrer"
                        variant="primary"
                        size="lg"
                    >
                        Baca Publikasi Asli
                        <span aria-hidden="true">→</span>
                    </x-ui.button>

                    <x-ui.button :href="route('articles')" variant="secondary" size="lg" wire:navigate>
                        Lihat Semua Artikel
                        <span aria-hidden="true">→</span>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section id="about" class="site-container py-16 lg:py-24" aria-label="About and contact">
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <article class="border border-line border-l-2 border-l-amber-600 bg-stone-50 p-8 sm:p-10 lg:min-h-[22rem]">
                <span class="font-serif text-5xl leading-none text-amber-700/40" aria-hidden="true">“</span>

                <blockquote class="type-quote mt-3 max-w-[58ch] text-ink">
                    “Two decades in financial intelligence have taught me that the complexity of the crime is always mirrored by the sophistication of its concealment. True oversight requires not just vigilance, but architectural understanding.”
                </blockquote>

                <a
                    href="{{ route('about') }}"
                    wire:navigate
                    class="type-action mt-8 inline-flex min-h-11 items-center gap-2 rounded-sm font-semibold text-ink transition hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"
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
                    <x-ui.button :href="route('contact')" variant="outline" size="lg" wire:navigate>
                        Ajukan Pertanyaan / Undangan Media
                        <span aria-hidden="true">→</span>
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </section>
</div>
