<?php

use Illuminate\Support\Facades\File;

it('defines one reusable typography contract for all page types', function () {
    $css = File::get(resource_path('css/app.css'));

    foreach ([
        'type-page-title',
        'type-section-title',
        'type-card-title',
        'type-subheading',
        'type-lead',
        'type-quote',
        'type-body',
        'type-support',
        'type-eyebrow',
        'type-meta',
        'type-action',
        'type-stat',
        'type-form-control',
    ] as $role) {
        expect($css)->toContain('.'.$role.' {');
    }

    expect($css)
        ->toContain('.type-eyebrow {')
        ->toContain('font-size: 1rem;')
        ->toContain('font-size: 1.125rem;')
        ->toContain('font-size: 0.875rem;')
        ->toContain('--color-slate-500: #253044;')
        ->toContain('color: var(--color-ink);');
});

it('uses typography roles for semantic headings across every Blade page', function () {
    foreach (File::allFiles(resource_path('views')) as $view) {
        $contents = File::get($view->getPathname());

        preg_match_all('/<h[1-3]\b[^>]*>/s', $contents, $headings);

        foreach ($headings[0] as $heading) {
            // The homepage hero has a deliberate photo-specific headline layout.
            if (str_contains($heading, 'id="home-hero-title"')) {
                continue;
            }

            expect($heading)->toMatch('/\btype-(?:page-title|section-title|card-title|subheading|eyebrow)\b/');
        }
    }
});

it('uses the same title and body roles for every collection card', function (string $path) {
    $html = File::get(resource_path('views/'.$path));

    expect($html)
        ->toContain('type-card-title')
        ->toContain('type-body')
        ->toContain('type-meta')
        ->toContain('type-support');
})->with([
    'article/card.blade.php',
    'library/resource-card.blade.php',
    'media/appearance-card.blade.php',
]);

it('matches Peta Kajian and Featured Writings card headline typography', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('Peta Kajian')
        ->toContain('Featured Writings')
        ->toContain('type-section-title')
        ->toContain('type-eyebrow')
        ->toContain('type-body');

    preg_match_all('/<h3\b[^>]*class="[^"]*\btype-card-title\b[^"]*"[^>]*>/s', $html, $matches);

    expect(count($matches[0]))->toBeGreaterThanOrEqual(6);
});

it('normalizes visible form controls to the same readable input size', function (string $page) {
    $html = File::get(resource_path('views/livewire/'.$page.'.blade.php'));

    preg_match_all('/<(?:input|select|textarea)\b[^>]*class="([^"]*)"[^>]*>/s', $html, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $classes) {
        expect($classes)->toContain('type-form-control');
    }
})->with(['articles', 'library', 'media', 'contact']);

it('preserves the same editorial hierarchy when public pages are rendered', function (string $route) {
    $html = $this->get(route($route))->assertOk()->getContent();

    expect($html)
        ->toContain('type-section-title')
        ->toContain('type-card-title')
        ->toContain('type-body');
})->with(['home', 'about', 'articles', 'library', 'media', 'contact']);
