<div>
    <section class="site-container border-b border-line py-14 sm:py-18 lg:py-22" aria-labelledby="articles-title">
        <div class="max-w-5xl">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.1em] text-accent">
                Knowledge Archive
            </p>

            <h1
                id="articles-title"
                class="mt-4 font-serif text-4xl font-semibold leading-[1.02] tracking-[-0.04em] text-ink sm:text-5xl lg:text-[3.35rem]"
            >
                ARTIKEL &amp; ANALISIS
            </h1>

            <p class="mt-5 max-w-3xl text-base leading-8 text-slate-600 sm:text-lg">
                Tulisan, ringkasan opini media, dan catatan kritis seputar kejahatan keuangan.
            </p>
        </div>

        <div class="mt-10 grid gap-5 xl:grid-cols-[minmax(19rem,26rem)_minmax(0,1fr)] xl:items-end">
            <div>
                <label for="article-search" class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-[0.07em] text-slate-500">
                    Cari artikel
                </label>

                <div class="relative">
                    <svg
                        class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-slate-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        aria-hidden="true"
                    >
                        <circle cx="11" cy="11" r="6.5" />
                        <path d="m16 16 4 4" />
                    </svg>

                    <input
                        id="article-search"
                        type="search"
                        wire:model.live.debounce.350ms="search"
                        placeholder="Cari judul, topik, atau media…"
                        autocomplete="off"
                        class="min-h-13 w-full rounded-sm border border-line bg-white py-3 pl-12 pr-4 text-[15px] text-ink outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >
                </div>
            </div>

            <div class="min-w-0">
                <span class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-[0.07em] text-slate-500">
                    Filter topik
                </span>

                <div class="scrollbar-none flex gap-2.5 overflow-x-auto pb-1">
                    @foreach ($categories as $key => $category)
                        <x-ui.filter-chip
                            wire:key="article-category-{{ $key }}"
                            wire:click="selectCategory('{{ $key }}')"
                            :active="$activeCategory === $key"
                            :inactive-classes="$category['classes']"
                        >
                            <span>{{ $category['label'] }}</span>
                            <span
                                class="ml-1.5 rounded-full bg-black/5 px-1.5 py-0.5 text-[10px] leading-none {{ $activeCategory === $key ? 'bg-white/15 text-white' : '' }}"
                            >
                                {{ $category['count'] }}
                            </span>
                        </x-ui.filter-chip>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="site-container py-10 sm:py-12 lg:py-16" aria-label="Daftar artikel">
        <div class="flex flex-col gap-2 border-b border-line pb-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-medium text-ink" aria-live="polite">
                @if ($resultCount > 0)
                    Menampilkan {{ $resultFrom }}–{{ $resultTo }} dari {{ $resultCount }} artikel
                @else
                    Tidak ada artikel yang cocok
                @endif
            </p>

            <p class="text-xs leading-6 text-slate-500">
                Arsip disusun dari publikasi asli dan sumber media terbuka.
            </p>
        </div>

        <div
            class="mt-7 grid gap-5"
            wire:loading.class="opacity-60"
            wire:target="search,selectCategory,goToPage,nextPage,previousPage"
        >
            @forelse ($articles as $index => $article)
                <x-article.card
                    wire:key="article-{{ $page }}-{{ $index }}-{{ md5($article['title']) }}"
                    :article="$article"
                />
            @empty
                <div class="border border-dashed border-slate-300 bg-white px-6 py-14 text-center sm:px-10">
                    <div class="mx-auto flex size-11 items-center justify-center rounded-full bg-slate-100 text-slate-500" aria-hidden="true">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="11" cy="11" r="6.5" />
                            <path d="m16 16 4 4" />
                        </svg>
                    </div>

                    <h2 class="mt-4 font-serif text-2xl font-medium text-ink">
                        Artikel belum ditemukan
                    </h2>

                    <p class="mx-auto mt-2 max-w-xl text-sm leading-7 text-slate-600">
                        Coba gunakan kata kunci yang lebih umum atau kembali ke seluruh kategori.
                    </p>

                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        @if ($search !== '')
                            <x-ui.button wire:click="$set('search', '')" variant="secondary" size="sm">
                                Hapus pencarian
                            </x-ui.button>
                        @endif

                        @if ($activeCategory !== 'all')
                            <x-ui.button wire:click="selectCategory('all')" variant="secondary" size="sm">
                                Lihat semua kategori
                            </x-ui.button>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if ($totalPages > 1)
            <nav class="mt-10 flex items-center justify-center gap-2 sm:mt-12" aria-label="Navigasi halaman artikel">
                <button
                    type="button"
                    wire:click="previousPage"
                    @disabled($page === 1)
                    class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-sm border border-line bg-white px-3 text-sm font-semibold text-ink transition hover:border-slate-400 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Halaman sebelumnya"
                >
                    <span aria-hidden="true">←</span>
                </button>

                @for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++)
                    <button
                        type="button"
                        wire:key="article-page-{{ $pageNumber }}"
                        wire:click="goToPage({{ $pageNumber }})"
                        class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-sm border px-3 text-sm font-semibold transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink {{ $page === $pageNumber ? 'border-ink bg-ink text-white' : 'border-line bg-white text-ink hover:border-slate-400 hover:bg-slate-50' }}"
                        aria-label="Halaman {{ $pageNumber }}"
                        aria-current="{{ $page === $pageNumber ? 'page' : 'false' }}"
                    >
                        {{ $pageNumber }}
                    </button>
                @endfor

                <button
                    type="button"
                    wire:click="nextPage"
                    @disabled($page === $totalPages)
                    class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-sm border border-line bg-white px-3 text-sm font-semibold text-ink transition hover:border-slate-400 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Halaman berikutnya"
                >
                    <span aria-hidden="true">→</span>
                </button>
            </nav>
        @endif
    </section>
</div>
