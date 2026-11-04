<div data-motion-static>
    <section class="border-b border-line bg-white">
        <div class="site-container py-14 sm:py-16 lg:py-20">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_30rem] lg:items-end">
                <div class="max-w-4xl">
                    <p class="type-eyebrow text-accent">
                        Public Record
                    </p>

                    <h1 class="type-page-title mt-4 text-ink">
                        MEDIA &amp; APPEARANCES
                    </h1>

                    <p class="type-lead mt-5 max-w-3xl text-ink">
                        Wawancara, forum publik, media visit, dan penampilan profesional M. Natsir Kongah yang dapat ditelusuri ke sumber terbuka.
                    </p>
                </div>

                <div class="grid grid-cols-2 divide-x divide-y divide-line border border-line bg-paper sm:grid-cols-4 sm:divide-y-0">
                    <div class="px-4 py-4 text-center">
                        <span class="type-stat block text-ink">{{ $totalAppearances }}</span>
                        <span class="type-meta mt-1 block font-medium uppercase tracking-[0.05em] text-slate-500">Appearance</span>
                    </div>
                    <div class="px-4 py-4 text-center">
                        <span class="type-stat block text-ink">{{ $interviewCount }}</span>
                        <span class="type-meta mt-1 block font-medium uppercase tracking-[0.05em] text-slate-500">Interview</span>
                    </div>
                    <div class="px-4 py-4 text-center">
                        <span class="type-stat block text-ink">{{ $publicEventCount }}</span>
                        <span class="type-meta mt-1 block font-medium uppercase tracking-[0.05em] text-slate-500">Public Event</span>
                    </div>
                    <div class="px-4 py-4 text-center">
                        <span class="type-stat block text-ink">{{ $yearSpan }}</span>
                        <span class="type-meta mt-1 block font-medium uppercase tracking-[0.05em] text-slate-500">Timeline</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($search === '' && $activeCategory === 'all')
        <section class="site-container py-8 sm:py-10" aria-labelledby="featured-media-title">
            <x-ui.card class="overflow-hidden p-0">
                <div class="grid lg:grid-cols-[16rem_minmax(0,1fr)]">
                    <div class="border-b border-line bg-paper p-6 sm:p-7 lg:border-b-0 lg:border-r">
                        <p class="type-eyebrow text-accent">
                            Featured Appearance
                        </p>

                        <div class="mt-5">
                            <x-ui.badge :variant="$featuredAppearance['badgeVariant']">
                                {{ $featuredAppearance['categoryLabel'] }}
                            </x-ui.badge>
                        </div>

                        <p class="type-meta mt-5 font-mono font-semibold uppercase tracking-[0.05em] text-slate-500">
                            {{ $featuredAppearance['date'] }}
                        </p>
                        <p class="type-body mt-2 font-semibold text-ink">
                            {{ $featuredAppearance['outlet'] }}
                        </p>
                    </div>

                    <div class="p-6 sm:p-8 lg:p-9">
                        <h2 id="featured-media-title" class="type-section-title max-w-4xl text-ink">
                            <a
                                href="{{ $featuredAppearance['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="rounded-sm transition hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
                            >
                                {{ $featuredAppearance['title'] }}
                            </a>
                        </h2>

                        <p class="type-body mt-4 font-semibold text-ink">
                            {{ $featuredAppearance['role'] }}
                        </p>

                        <p class="type-body mt-5 max-w-[90ch] text-ink">
                            {{ $featuredAppearance['summary'] }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-2">
                            @foreach ($featuredAppearance['topics'] as $topic)
                                <span class="type-meta rounded-full border border-line bg-white px-2.5 py-1 font-medium text-ink">
                                    {{ $topic }}
                                </span>
                            @endforeach
                        </div>

                        <div class="mt-7">
                            <x-ui.button
                                :href="$featuredAppearance['url']"
                                target="_blank"
                                rel="noopener noreferrer"
                                variant="primary"
                                size="md"
                            >
                                {{ $featuredAppearance['actionLabel'] }}
                                <span aria-hidden="true">→</span>
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </section>
    @endif

    <section class="site-container py-8 sm:py-10" aria-label="Filter media appearances">
        <div class="border border-line bg-white p-5 sm:p-6">
            <div class="grid gap-5 xl:grid-cols-[minmax(19rem,26rem)_minmax(0,1fr)] xl:items-end">
                <div>
                    <label for="media-search" class="type-meta mb-2 block font-mono font-semibold uppercase tracking-[0.07em] text-slate-500">
                        Cari appearance
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
                            id="media-search"
                            type="search"
                            wire:model.live.debounce.350ms="search"
                            placeholder="Judul, outlet, peran, atau topik…"
                            autocomplete="off"
                            class="min-h-13 w-full rounded-sm border border-line bg-paper py-3 pl-12 pr-4 type-form-control text-ink outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-slate-500 focus:bg-white focus:ring-2 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <div class="min-w-0">
                    <span class="type-meta mb-2 block font-mono font-semibold uppercase tracking-[0.07em] text-slate-500">
                        Format
                    </span>

                    <div class="scrollbar-none flex gap-2.5 overflow-x-auto pb-1">
                        @foreach ($categories as $key => $category)
                            <x-ui.filter-chip
                                wire:key="media-category-{{ $key }}"
                                wire:click="selectCategory('{{ $key }}')"
                                :active="$activeCategory === $key"
                                :inactive-classes="$category['classes']"
                            >
                                <span>{{ $category['label'] }}</span>
                                <span class="type-meta ml-1.5 rounded-full bg-black/5 px-1.5 py-0.5 {{ $activeCategory === $key ? 'bg-white/15 text-white' : '' }}">
                                    {{ $category['count'] }}
                                </span>
                            </x-ui.filter-chip>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="site-container pb-16 sm:pb-20 lg:pb-24" aria-label="Media archive">
        <div class="flex flex-col gap-3 border-b border-line pb-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="type-body font-semibold text-ink" aria-live="polite">
                    {{ $resultCount }} appearance ditemukan
                </p>
                <p class="type-support mt-1 text-slate-500">
                    Arsip hanya memuat penampilan dengan peran aktif yang dapat ditelusuri ke sumber publik.
                </p>
            </div>

            @if ($search !== '' || $activeCategory !== 'all')
                <x-ui.button wire:click="clearFilters" variant="secondary" size="sm">
                    Reset filter
                    <span aria-hidden="true">↺</span>
                </x-ui.button>
            @endif
        </div>

        <div
            class="mt-7 grid gap-5 md:grid-cols-2"
            wire:loading.class="opacity-60"
            wire:target="search,selectCategory,clearFilters"
        >
            @forelse ($appearances as $appearance)
                <x-media.appearance-card
                    wire:key="media-{{ md5($appearance['title']) }}"
                    :appearance="$appearance"
                />
            @empty
                <div class="border border-dashed border-slate-300 bg-white px-6 py-14 text-center md:col-span-2 sm:px-10">
                    <div class="mx-auto flex size-11 items-center justify-center rounded-full bg-slate-100 text-slate-500" aria-hidden="true">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="3.5" y="5.5" width="17" height="13" rx="1.5" />
                            <path d="m10 9 5 3-5 3V9Z" />
                        </svg>
                    </div>

                    <h2 class="type-card-title mt-4 text-ink">
                        Appearance belum ditemukan
                    </h2>

                    <p class="type-body mx-auto mt-2 max-w-xl text-ink">
                        Coba kata kunci yang lebih umum atau tampilkan kembali seluruh format media.
                    </p>

                    <div class="mt-6">
                        <x-ui.button wire:click="clearFilters" variant="secondary" size="sm">
                            Reset filter
                        </x-ui.button>
                    </div>
                </div>
            @endforelse
        </div>

        <aside class="mt-12 grid gap-5 border-t border-line pt-8 lg:grid-cols-[16rem_minmax(0,1fr)]" aria-labelledby="media-note-title">
            <div>
                <p class="type-eyebrow text-accent">
                    Kriteria Arsip
                </p>
                <h2 id="media-note-title" class="type-section-title mt-2 text-ink">
                    Appearance, bukan sekadar mention.
                </h2>
            </div>

            <p class="type-body max-w-4xl text-ink">
                Halaman ini hanya memuat wawancara, siaran, forum publik, media visit, dan recognition ketika M. Natsir Kongah memiliki peran aktif yang disebut jelas oleh sumber.
                Halaman yang hanya mencantumkan nama sebagai narahubung media tidak dimasukkan. Tulisan opini tetap berada di
                <a href="{{ route('articles') }}" wire:navigate class="font-semibold text-ink underline decoration-slate-300 underline-offset-4 transition hover:decoration-ink">Articles</a>,
                sedangkan paper dan sumber riset berada di
                <a href="{{ route('library') }}" wire:navigate class="font-semibold text-ink underline decoration-slate-300 underline-offset-4 transition hover:decoration-ink">Library</a>.
            </p>
        </aside>
    </section>
</div>
