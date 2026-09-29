<div>
    <section class="border-b border-line bg-white">
        <div class="site-container py-14 sm:py-16 lg:py-20">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-end">
                <div class="max-w-4xl">
                    <p class="font-mono text-xs font-semibold uppercase tracking-[0.1em] text-accent">
                        Research Desk
                    </p>

                    <h1 class="mt-4 font-serif text-4xl font-semibold leading-[1.02] tracking-[-0.04em] text-ink sm:text-5xl lg:text-[3.35rem]">
                        LIBRARY &amp; REFERENSI
                    </h1>

                    <p class="mt-5 max-w-3xl text-base leading-8 text-slate-600 sm:text-lg">
                        Koleksi sumber primer, pedoman, penilaian risiko, dan referensi untuk riset kejahatan keuangan.
                    </p>
                </div>

                <div class="grid grid-cols-3 divide-x divide-line border border-line bg-paper">
                    <div class="px-4 py-4 text-center">
                        <span class="block font-serif text-2xl font-semibold text-ink">{{ $totalResources }}</span>
                        <span class="mt-1 block text-[11px] font-medium uppercase tracking-[0.05em] text-slate-500">Sumber</span>
                    </div>
                    <div class="px-4 py-4 text-center">
                        <span class="block font-serif text-2xl font-semibold text-ink">{{ count($institutions) - 1 }}</span>
                        <span class="mt-1 block text-[11px] font-medium uppercase tracking-[0.05em] text-slate-500">Institusi</span>
                    </div>
                    <div class="px-4 py-4 text-center">
                        <span class="block font-serif text-2xl font-semibold text-ink">{{ count($resourceTypes) - 1 }}</span>
                        <span class="mt-1 block text-[11px] font-medium uppercase tracking-[0.05em] text-slate-500">Jenis</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="site-container py-8 sm:py-10" aria-label="Filter library">
        <div class="border border-line bg-white p-5 sm:p-6">
            <div class="grid gap-4 lg:grid-cols-[minmax(16rem,1.6fr)_repeat(3,minmax(10rem,1fr))]">
                <div>
                    <label for="library-search" class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-[0.07em] text-slate-500">
                        Cari referensi
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
                            id="library-search"
                            type="search"
                            wire:model.live.debounce.350ms="search"
                            placeholder="Judul, topik, institusi…"
                            autocomplete="off"
                            class="min-h-12 w-full rounded-sm border border-line bg-paper py-3 pl-12 pr-4 text-[15px] text-ink outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-slate-500 focus:bg-white focus:ring-2 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <div>
                    <label for="resource-type" class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-[0.07em] text-slate-500">
                        Jenis dokumen
                    </label>
                    <select
                        id="resource-type"
                        wire:model.live="resourceType"
                        class="min-h-12 w-full rounded-sm border border-line bg-paper px-3 text-[14px] font-medium text-ink outline-none transition hover:border-slate-300 focus:border-slate-500 focus:bg-white focus:ring-2 focus:ring-slate-200"
                    >
                        @foreach ($resourceTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="resource-institution" class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-[0.07em] text-slate-500">
                        Institusi
                    </label>
                    <select
                        id="resource-institution"
                        wire:model.live="institution"
                        class="min-h-12 w-full rounded-sm border border-line bg-paper px-3 text-[14px] font-medium text-ink outline-none transition hover:border-slate-300 focus:border-slate-500 focus:bg-white focus:ring-2 focus:ring-slate-200"
                    >
                        @foreach ($institutions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="resource-topic" class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-[0.07em] text-slate-500">
                        Topik
                    </label>
                    <select
                        id="resource-topic"
                        wire:model.live="topic"
                        class="min-h-12 w-full rounded-sm border border-line bg-paper px-3 text-[14px] font-medium text-ink outline-none transition hover:border-slate-300 focus:border-slate-500 focus:bg-white focus:ring-2 focus:ring-slate-200"
                    >
                        @foreach ($topics as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="site-container pb-16 sm:pb-20 lg:pb-24" aria-label="Katalog referensi">
        <div class="flex flex-col gap-3 border-b border-line pb-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-ink" aria-live="polite">
                    {{ count($resources) }} referensi ditemukan
                </p>
                <p class="mt-1 text-xs leading-6 text-slate-500">
                    Sumber diarahkan ke halaman publikasi atau dokumen resmi pemilik sumber.
                </p>
            </div>

            @if ($search !== '' || $resourceType !== 'all' || $institution !== 'all' || $topic !== 'all')
                <x-ui.button wire:click="clearFilters" variant="ghost" size="sm">
                    Reset filter
                    <span aria-hidden="true">↺</span>
                </x-ui.button>
            @endif
        </div>

        <div
            class="mt-7 grid gap-5 md:grid-cols-2"
            wire:loading.class="opacity-60"
            wire:target="search,resourceType,institution,topic,clearFilters"
        >
            @forelse ($resources as $resource)
                <x-library.resource-card
                    wire:key="resource-{{ md5($resource['title']) }}"
                    :resource="$resource"
                />
            @empty
                <div class="md:col-span-2 border border-dashed border-slate-300 bg-white px-6 py-14 text-center sm:px-10">
                    <div class="mx-auto flex size-11 items-center justify-center rounded-full bg-slate-100 text-slate-500" aria-hidden="true">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M5 4.5h10.5a2.5 2.5 0 0 1 2.5 2.5v12.5H7.5A2.5 2.5 0 0 1 5 17V4.5Z" />
                            <path d="M7.5 19.5A2.5 2.5 0 0 1 5 17c0-1.38 1.12-2.5 2.5-2.5H18" />
                        </svg>
                    </div>

                    <h2 class="mt-4 font-serif text-2xl font-medium text-ink">
                        Referensi belum ditemukan
                    </h2>

                    <p class="mx-auto mt-2 max-w-xl text-sm leading-7 text-slate-600">
                        Ubah kata kunci atau longgarkan salah satu filter untuk melihat sumber lain.
                    </p>

                    <div class="mt-6">
                        <x-ui.button wire:click="clearFilters" variant="secondary" size="sm">
                            Tampilkan semua referensi
                        </x-ui.button>
                    </div>
                </div>
            @endforelse
        </div>

        <aside class="mt-12 grid gap-5 border-t border-line pt-8 lg:grid-cols-[16rem_minmax(0,1fr)]" aria-labelledby="library-note-title">
            <div>
                <p class="font-mono text-[11px] font-semibold uppercase tracking-[0.08em] text-accent">
                    Cara membaca Library
                </p>
                <h2 id="library-note-title" class="mt-2 font-serif text-2xl font-medium tracking-[-0.02em] text-ink">
                    Evidence, bukan opini.
                </h2>
            </div>

            <p class="max-w-4xl text-[15px] leading-7 text-slate-600">
                Library berfungsi sebagai rak referensi: standar, regulasi, guidance, risk assessment, typology, dan handbook.
                Untuk tulisan opini atau analisis M. Natsir Kongah, gunakan halaman
                <a href="{{ route('articles') }}" wire:navigate class="font-semibold text-ink underline decoration-slate-300 underline-offset-4 transition hover:decoration-ink">
                    Articles
                </a>.
            </p>
        </aside>
    </section>
</div>
