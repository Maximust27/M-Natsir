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

it('renders the new learning-focused hero headline without covering the portrait', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    preg_match('/<h1\b(?=[^>]*id="home-hero-title")[^>]*>(.*?)<\/h1>/s', $html, $match);
    $headline = trim(preg_replace('/\s+/', ' ', strip_tags($match[1] ?? '')));

    expect($headline)
        ->toBe('MEMBONGKAR PENCUCIAN UANG DAN KEJAHATAN KERAH PUTIH');

    expect($html)
        ->toContain('Analisis terkurasi dan perpustakaan intelijen keuangan')
        ->toContain('ruang belajar bersama M. Natsir Kongah')
        ->toContain('rujukan sumber asli yang dapat ditelusuri')
        ->toContain('sm:aspect-[2/1] lg:absolute')
        ->toContain('lg:max-w-[38rem]')
        ->toContain('xl:max-w-[49rem]')
        ->toContain('2xl:max-w-[58rem]')
        ->toContain('lg:max-w-none')
        ->toContain('lg:max-w-[34rem] xl:max-w-[39rem]')
        ->not->toContain('“Membaca jejak transaksi, menyingkap arsitektur kejahatan keuangan.”');
});

it('enlarges every topic hub card while preserving its crawlable link', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('id="knowledge-pillars-heading"')
        ->toContain('Peta Kajian')
        ->toContain('type-eyebrow text-accent')
        ->toContain('type-section-title mt-3 text-ink')
        ->toContain('type-card-title text-ink')
        ->toContain('type-body mt-4 text-ink')
        ->toContain('type-action mt-auto inline-flex min-h-11');

    foreach (\App\Support\Content\TopicCatalog::all() as $topic) {
        expect($html)->toContain('href="'.route('topics.show', ['slug' => $topic['slug']]).'"');
    }
});

it('replaces only the footer contact link with an admin placeholder', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    preg_match('/<footer\b.*?<\/footer>/s', $html, $match);
    $footer = $match[0] ?? '';

    expect($footer)
        ->toContain('href="'.route('admin.login').'"')
        ->toContain('Admin Page')
        ->not->toContain('>Contact</a>');

    // The existing Contact page stays available until the admin/auth revision.
    $this->get(route('contact'))->assertOk();
});

it('still renders the article library media and about pages', function (string $page) {
    $this->get(route($page))->assertOk();
})->with(['about', 'articles', 'library', 'media', 'contact']);
