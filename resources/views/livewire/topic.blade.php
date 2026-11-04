<div data-motion-static>
    <section class="border-b border-line bg-white">
        <div class="site-container py-14 sm:py-18 lg:py-22">
            <nav class="mb-9 flex flex-wrap items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate class="font-semibold text-ink hover:underline">Beranda</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('articles') }}" wire:navigate class="font-semibold text-ink hover:underline">Artikel &amp; Analisis</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $topic['heading'] }}</span>
            </nav>

            <p class="type-eyebrow text-accent">
                Peta Bacaan &amp; Kajian
            </p>

            <h1 class="type-page-title mt-4 max-w-5xl text-ink">
                {{ $topic['heading'] }}
            </h1>

            <p class="type-lead mt-6 max-w-3xl text-ink">
                {{ $topic['teaser'] }}
            </p>

            <div class="mt-8 max-w-4xl space-y-5 text-base leading-8 text-ink">
                @foreach ($topic['introduction'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <p class="type-body mt-7 max-w-4xl border-l-2 border-amber-500 bg-stone-50 px-5 py-4 text-ink">
                Halaman ini adalah pengantar dan peta bacaan yang disusun dari tema publikasi yang tercatat di situs.
                Kutipan serta rincian tulisan tetap harus diperiksa melalui sumber aslinya; halaman ini bukan nasihat hukum.
                <a href="{{ route('about') }}" wire:navigate class="font-semibold text-ink underline underline-offset-4">Lihat profil dan rujukan penulis</a>.
            </p>
        </div>
    </section>

    <section class="site-container border-b border-line py-14 lg:py-20" aria-labelledby="topic-focus-heading">
        <div class="max-w-4xl">
            <p class="type-eyebrow text-accent">Cara Membaca Topik</p>
            <h2 id="topic-focus-heading" class="type-section-title mt-3 text-ink">Tiga pertanyaan untuk didalami</h2>
        </div>

        <div class="mt-9 grid gap-5 md:grid-cols-3">
            @foreach ($topic['readingFocus'] as $index => $focus)
                <article class="border border-line bg-white p-6 sm:p-7">
                    <span class="type-meta font-mono font-semibold text-accent">0{{ $index + 1 }}</span>
                    <h3 class="type-subheading mt-3 text-ink">{{ $focus['title'] }}</h3>
                    <p class="type-body mt-3 text-ink">{{ $focus['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="site-container border-b border-line py-14 lg:py-20" aria-labelledby="topic-articles-heading">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="type-eyebrow text-accent">Rujukan &amp; Publikasi</p>
                <h2 id="topic-articles-heading" class="type-section-title mt-3 text-ink">
                    Bacaan terkait {{ $topic['heading'] }}
                </h2>
                <p class="type-body mt-3 max-w-3xl text-ink">
                    {{ count($articles) }} rujukan dari katalog. Setiap kartu mencantumkan asal publikasi dan membuka sumber eksternal secara langsung.
                </p>
            </div>

            <a
                href="{{ route('articles', ['category' => $topic['category']]) }}"
                wire:navigate
                class="inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-ink underline underline-offset-4 hover:text-accent"
            >
                Filter arsip topik ini
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="mt-9 grid gap-5">
            @forelse ($articles as $article)
                <x-article.card :article="$article" />
            @empty
                <p class="type-body border border-line p-7 text-ink">
                    Belum ada publikasi yang tercatat dalam kategori ini.
                </p>
            @endforelse
        </div>
    </section>

    <section class="site-container py-14 lg:py-20" aria-labelledby="topic-next-heading">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 id="topic-next-heading" class="type-section-title text-ink">
                Lanjutkan penelusuran
            </h2>
            <a
                href="{{ route('library', ['topic' => $topic['libraryTopic']]) }}"
                wire:navigate
                class="text-sm font-semibold text-ink underline underline-offset-4 hover:text-accent"
            >
                Lihat referensi terkait di Library →
            </a>
        </div>

        <nav class="mt-8 grid gap-4 md:grid-cols-2" aria-label="Topik terkait">
            @foreach ($relatedTopics as $related)
                <a
                    href="{{ route('topics.show', ['slug' => $related['slug']]) }}"
                    wire:navigate
                    class="group block border border-line bg-white p-6 transition hover:border-slate-400 hover:shadow-sm"
                >
                    <h3 class="type-card-title text-ink group-hover:text-accent">{{ $related['heading'] }}</h3>
                    <p class="type-body mt-3 text-ink">{{ $related['teaser'] }}</p>
                    <span class="type-meta mt-4 inline-block font-semibold text-ink">Buka peta bacaan →</span>
                </a>
            @endforeach
        </nav>
    </section>
</div>
