@props([
    'href' => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'inline-flex min-h-12 items-center rounded-sm text-sm tracking-[-0.01em] transition duration-200',
        'font-semibold text-ink' => $active,
        'font-medium text-ink hover:text-ink' => ! $active,
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink',
    ]) }}
>
    {{ $slot }}
</a>
