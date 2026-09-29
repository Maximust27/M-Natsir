@props([
    'resource',
])

<x-ui.card class="group h-full p-0" :interactive="false">
    <article class="flex h-full flex-col p-6 sm:p-7">
        <div class="flex flex-wrap items-center gap-2">
            <x-ui.badge class="bg-slate-100 text-slate-700">
                {{ $resource['typeLabel'] }}
            </x-ui.badge>

            <span class="font-mono text-[11px] font-semibold uppercase tracking-[0.05em] text-slate-500">
                {{ $resource['institutionLabel'] }}
            </span>

            <span class="text-slate-300" aria-hidden="true">•</span>

            <span class="font-mono text-[11px] font-medium text-slate-500">
                {{ $resource['year'] }}
            </span>

            <span class="text-slate-300" aria-hidden="true">•</span>

            <span class="font-mono text-[11px] font-medium text-slate-500">
                {{ $resource['language'] }}
            </span>
        </div>

        <h2 class="mt-5 font-serif text-[1.65rem] font-medium leading-[1.15] tracking-[-0.025em] text-ink">
            <a
                href="{{ $resource['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="rounded-sm transition group-hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            >
                {{ $resource['title'] }}
            </a>
        </h2>

        <p class="mt-4 text-[15px] leading-7 text-slate-600">
            {{ $resource['summary'] }}
        </p>

        <div class="mt-5 flex flex-wrap gap-2" aria-label="Topik">
            @foreach ($resource['topicLabels'] as $label)
                <span class="rounded-full border border-line bg-paper px-2.5 py-1 text-xs font-medium text-slate-600">
                    {{ $label }}
                </span>
            @endforeach
        </div>

        <div class="mt-auto pt-7">
            <x-ui.button
                :href="$resource['url']"
                target="_blank"
                rel="noopener noreferrer"
                variant="secondary"
                size="sm"
            >
                Buka Sumber
                <span aria-hidden="true">↗</span>
            </x-ui.button>
        </div>
    </article>
</x-ui.card>
