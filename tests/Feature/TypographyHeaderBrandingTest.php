<?php

use Illuminate\Support\Facades\File;

it('uses readable and consistent font sizing for captions navigation and body text', function () {
    $css = File::get(resource_path('css/app.css'));

    expect($css)
        ->toContain('--text-xs: 0.875rem;')  // 14 px metadata and captions
        ->toContain('--text-sm: 1rem;')      // 16 px navigation and controls
        ->toContain('--text-base: 1.125rem;') // 18 px body copy
        ->toContain('--text-lg: 1.25rem;')   // 20 px introduction
        ->toContain('color: var(--color-ink);')
        ->toContain('--color-slate-500: #253044;');
});

it('does not retain 10 to 15 pixel font utilities or light gray paragraph colors', function () {
    foreach (File::allFiles(resource_path('views')) as $view) {
        $contents = File::get($view->getPathname());

        expect($contents)
            ->not->toMatch('/\btext-\[(?:[1-9]|1[0-5])px\]/')
            ->not->toMatch('/\btext-slate-(?:600|700)\b/');
    }
});

it('renders an accessible header with the sharp SVG favicon and correct brand hierarchy', function () {
    $response = $this->get(route('home'))->assertOk();
    $html = $response->getContent();

    expect(File::exists(public_path('favicon.svg')))->toBeTrue();
    expect(File::get(public_path('favicon.svg')))
        ->toContain('<svg')
        ->toContain('viewBox="0 0 512 512"');

    preg_match('/<header\b.*?<\/header>/s', $html, $match);
    $header = $match[0] ?? '';

    expect($header)
        ->toContain('src="'.asset('favicon.svg').'"')
        ->toContain('alt=""')
        ->toContain('Financial Intelligence Hub')
        ->toContain('M. Natsir Kongah')
        ->toContain('aria-label="Navigasi utama"')
        ->toContain('aria-label="Navigasi seluler"')
        ->toContain('xl:flex')
        ->toContain('xl:hidden');

    $titleIndex = strpos($header, 'Financial Intelligence Hub', strpos($header, '<img'));
    $nameIndex = strpos($header, 'M. Natsir Kongah', strpos($header, '<img'));
    expect($titleIndex)->not->toBeFalse();
    expect($nameIndex)->toBeGreaterThan($titleIndex);
});

it('keeps the home hero content readable without changing its original messaging', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('M. NATSIR KONGAH')
        ->toContain('“Membaca jejak transaksi, menyingkap arsitektur kejahatan keuangan.”')
        ->toContain('Analisis terkurasi dan perpustakaan intelijen')
        ->toContain('font-serif text-xl italic')
        ->toContain('max-w-[62ch] text-base')
        ->toContain('sm:text-lg');
});

it('still renders the article library media and about pages', function (string $page) {
    $this->get(route($page))->assertOk();
})->with(['about', 'articles', 'library', 'media', 'contact']);
