@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'headingId' => null,
])

<div {{ $attributes }}>
    @if ($eyebrow)
        <p class="font-mono text-xs font-semibold uppercase tracking-[0.08em] text-accent">
            {{ $eyebrow }}
        </p>
    @endif

    <h2
        @if ($headingId) id="{{ $headingId }}" @endif
        class="@if ($eyebrow) mt-4 @endif max-w-4xl font-serif text-[1.9rem] font-medium leading-tight tracking-[-0.025em] text-black sm:text-[2.2rem] lg:text-[2.35rem]"
    >
        {{ $title }}
    </h2>

    @if ($description)
        <p class="mt-5 max-w-[72ch] text-base leading-8 text-slate-600">
            {{ $description }}
        </p>
    @endif
</div>
