@props([
    'article',
])

<x-ui.card class="group p-0">
    <div class="p-6 sm:p-8 lg:p-9">
        <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
            <x-ui.badge :class="$article['badgeClasses']">
                {{ $article['categoryLabel'] }}
            </x-ui.badge>

            <span class="font-mono text-[11px] font-medium uppercase tracking-[0.045em] text-slate-500">
                {{ $article['source'] }}
                <span class="mx-1 text-slate-300" aria-hidden="true">•</span>
                {{ $article['date'] }}
            </span>

            @if (($article['isPrimarySource'] ?? true) === false)
                <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-900">
                    Sumber sekunder
                </span>
            @endif
        </div>

        <h2 class="mt-5 max-w-5xl font-serif text-2xl font-medium leading-[1.15] tracking-[-0.025em] text-ink sm:text-[1.8rem] lg:text-[2rem]">
            <a
                href="{{ $article['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="rounded-sm transition group-hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            >
                {{ $article['title'] }}
            </a>
        </h2>

        <p class="mt-4 max-w-[94ch] text-[15px] leading-7 text-slate-600 sm:text-base sm:leading-8">
            {{ $article['excerpt'] }}
        </p>

        @if (! empty($article['sourceNote']))
            <p class="mt-5 max-w-[94ch] border-l-2 border-amber-200 pl-3 text-xs leading-6 text-slate-500">
                {{ $article['sourceNote'] }}
            </p>
        @endif

        <div class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-3">
            <x-ui.button
                :href="$article['url']"
                target="_blank"
                rel="noopener noreferrer"
                variant="primary"
                size="md"
            >
                {{ $article['accessLabel'] ?? 'Baca Publikasi Asli' }}
                <span aria-hidden="true">→</span>
            </x-ui.button>

            <span class="text-xs leading-6 text-slate-500">
                {{ ($article['isPrimarySource'] ?? true) ? 'Sumber publik:' : 'Referensi atribusi:' }}
                {{ $article['source'] }}
            </span>
        </div>
    </div>
</x-ui.card>
