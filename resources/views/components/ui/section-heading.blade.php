@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'headingId' => null,
])

<div {{ $attributes }}>
    @if ($eyebrow)
        <p class="type-eyebrow text-accent">
            {{ $eyebrow }}
        </p>
    @endif

    <h2
        @if ($headingId) id="{{ $headingId }}" @endif
        class="@if ($eyebrow) mt-4 @endif type-section-title max-w-4xl text-ink"
    >
        {{ $title }}
    </h2>

    @if ($description)
        <p class="type-body mt-5 max-w-[72ch] text-ink">
            {{ $description }}
        </p>
    @endif
</div>
