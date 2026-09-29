@props([
    'href' => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'inline-flex min-h-10 items-center rounded-sm text-[13px] tracking-[-0.01em] transition duration-200 lg:text-sm',
        'font-semibold text-ink' => $active,
        'font-medium text-slate-600 hover:text-ink' => ! $active,
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink',
    ]) }}
>
    {{ $slot }}
</a>
