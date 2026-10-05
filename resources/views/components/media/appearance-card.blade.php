@props([
    'appearance',
])

<x-ui.card class="group h-full overflow-hidden p-0">
    @if (! empty($appearance['thumbnail']))
        <a
            href="{{ $appearance['url'] }}"
            target="_blank"
            rel="noopener noreferrer"
            class="block overflow-hidden border-b border-line bg-slate-100 focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-ink"
            aria-label="{{ $appearance['title'] }}"
        >
            <img
                src="{{ $appearance['thumbnail'] }}"
                alt=""
                loading="lazy"
                class="aspect-video w-full object-cover transition duration-300 group-hover:scale-[1.015]"
            >
        </a>
    @endif

    <div class="flex h-full flex-col p-6 sm:p-7">
        <div class="flex flex-wrap items-center gap-2">
            <x-ui.badge :variant="$appearance['badgeVariant']">
                {{ $appearance['categoryLabel'] }}
            </x-ui.badge>

            <span class="font-mono text-[11px] font-medium uppercase tracking-[0.045em] text-slate-500">
                {{ $appearance['date'] }}
            </span>
        </div>

        <p class="mt-4 font-mono text-[11px] font-semibold uppercase tracking-[0.05em] text-slate-500">
            {{ $appearance['outlet'] }}
        </p>

        <h2 class="mt-3 font-serif text-[1.65rem] font-medium leading-[1.15] tracking-[-0.025em] text-ink">
            <a
                href="{{ $appearance['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="rounded-sm transition group-hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            >
                {{ $appearance['title'] }}
            </a>
        </h2>

        <p class="mt-3 text-sm font-semibold leading-6 text-slate-700">
            {{ $appearance['role'] }}
        </p>

        <p class="mt-4 text-[15px] leading-7 text-slate-600">
            {{ $appearance['summary'] }}
        </p>

        <div class="mt-5 flex flex-wrap gap-2" aria-label="Topik">
            @foreach ($appearance['topics'] as $topic)
                <span class="rounded-full border border-line bg-paper px-2.5 py-1 text-xs font-medium text-slate-600">
                    {{ $topic }}
                </span>
            @endforeach
        </div>

        @if (! empty($appearance['sourceNote']))
            <p class="mt-5 border-l-2 border-slate-200 pl-3 text-xs leading-6 text-slate-500">
                {{ $appearance['sourceNote'] }}
            </p>
        @endif

        <div class="mt-auto pt-7">
            <x-ui.button
                :href="$appearance['url']"
                target="_blank"
                rel="noopener noreferrer"
                variant="secondary"
                size="sm"
            >
                {{ $appearance['actionLabel'] }}
                <span aria-hidden="true">→</span>
            </x-ui.button>
        </div>
    </div>
</x-ui.card>
