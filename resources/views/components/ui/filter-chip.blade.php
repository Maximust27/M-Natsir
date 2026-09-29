@props([
    'active' => false,
    'inactiveClasses' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
])

<button
    {{ $attributes->class([
        'inline-flex min-h-10 shrink-0 items-center justify-center rounded-full px-4 py-2 font-sans text-sm font-semibold leading-none transition duration-200',
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink',
        'bg-ink text-white shadow-sm' => $active,
        $inactiveClasses => ! $active,
    ])->merge(['type' => 'button']) }}
    aria-pressed="{{ $active ? 'true' : 'false' }}"
>
    {{ $slot }}
</button>
