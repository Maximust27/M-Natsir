@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $variants = [
        'primary' => 'border border-ink bg-ink text-white hover:border-black hover:bg-black',
        'secondary' => 'border border-line bg-white text-ink hover:border-slate-300 hover:bg-slate-50',
        'outline' => 'border border-ink bg-transparent text-ink hover:bg-ink hover:text-white',
    ];

    $sizes = [
        'sm' => 'min-h-10 px-4 py-2 text-sm',
        'md' => 'min-h-11 px-5 py-2.5 text-sm',
        'lg' => 'min-h-12 px-6 py-3 text-sm sm:text-[15px]',
    ];

    $classes = [
        'inline-flex items-center justify-center gap-2 rounded-sm font-sans font-semibold leading-none transition duration-200',
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" data-ui-button {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button data-ui-button {{ $attributes->class($classes)->merge(['type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
