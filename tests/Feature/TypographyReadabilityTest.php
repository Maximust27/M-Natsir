<?php

use Illuminate\Support\Facades\File;

it('defines a readable minimum type scale and high contrast secondary text', function () {
    $css = File::get(resource_path('css/app.css'));

    expect($css)
        ->toContain('--text-xs: 0.875rem;')
        ->toContain('--text-sm: 1rem;')
        ->toContain('--text-base: 1.0625rem;')
        ->toContain('--text-lg: 1.25rem;')
        ->toContain('--color-slate-500: #475569;')
        ->toContain('--color-slate-600: #334155;')
        ->toContain('--color-slate-700: #1e293b;')
        ->toContain('font-size: var(--text-base);');
});

it('avoids tiny fixed font-size utilities throughout Blade templates', function () {
    foreach (File::allFiles(resource_path('views')) as $view) {
        $contents = File::get($view->getPathname());

        expect($contents)
            ->not->toMatch('/\btext-\[(?:[1-9]|1[0-3])px\]/');
    }
});

it('retains semantic navigation with space for enlarged navigation labels', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('aria-label="Navigasi utama"')
        ->toContain('aria-label="Navigasi seluler"')
        ->toContain('xl:hidden')
        ->toContain('xl:flex')
        ->toContain('M. NATSIR KONGAH')
        ->toContain('Financial Intelligence Hub');
});

it('keeps the public editorial pages and footer renderable', function (string $routeName) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee('<main>', false)
        ->assertSee('<footer', false)
        ->assertSee('M. NATSIR KONGAH');
})->with(['home', 'about', 'articles', 'library', 'media']);
