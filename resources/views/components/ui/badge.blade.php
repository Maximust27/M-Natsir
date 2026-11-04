@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-slate-100 text-ink',
        'amber' => 'bg-amber-100 text-amber-900',
        'teal' => 'bg-teal-100 text-teal-900',
        'indigo' => 'bg-indigo-100 text-indigo-900',
    ];
@endphp

<span
    {{ $attributes->class([
        'type-meta inline-flex items-center rounded-full px-3 py-1.5 font-mono',
        $variants[$variant] ?? $variants['neutral'],
    ]) }}
>
    {{ $slot }}
</span>
