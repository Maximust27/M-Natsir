@props([
    'interactive' => true,
])

<article
    {{ $attributes->class([
        'border border-line bg-white p-7 sm:p-8',
        'transition duration-200 hover:-translate-y-0.5 hover:border-slate-400 hover:shadow-sm focus-within:border-slate-400' => $interactive,
    ]) }}
>
    {{ $slot }}
</article>
