@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700',
        'amber' => 'bg-amber-100 text-amber-900',
        'teal' => 'bg-teal-100 text-teal-900',
        'indigo' => 'bg-indigo-100 text-indigo-900',
    ];
@endphp

<span
    {{ $attributes->class([
        'inline-flex items-center rounded-full px-3 py-1.5 font-mono text-xs font-semibold leading-none tracking-[0.035em]',
        $variants[$variant] ?? $variants['neutral'],
    ]) }}
>
    {{ $slot }}
</span>
