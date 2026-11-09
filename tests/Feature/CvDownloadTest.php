<?php

use Illuminate\Support\Facades\File;

it('provides the committed M. Natsir Kongah CV as a real PDF attachment', function () {
    $pdf = public_path('files/m-natsir-kongah-cv.pdf');

    expect(File::exists($pdf))->toBeTrue()
        ->and(file_get_contents($pdf, false, null, 0, 5))->toBe('%PDF-');

    $response = $this->get(route('cv.download'));

    $response->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('M-Natsir-Kongah-CV.pdf');

    // Symfony normalizes the directive order and may append "private".
    expect($response->headers->get('cache-control'))->toContain('no-store');
});

it('downloads the complete CV even when an HTTP Range header is supplied', function () {
    $this->withHeader('Range', 'bytes=0-')
        ->get(route('cv.download'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('M-Natsir-Kongah-CV.pdf');
});

it('retains an HTTP redirect for links to the old CV URL without restoring Contact', function () {
    $this->get('/contact/cv')
        ->assertStatus(301)
        ->assertRedirect('/cv');

    $this->get('/contact')->assertNotFound();
    expect(\Illuminate\Support\Facades\Route::has('contact'))->toBeFalse();
});

it('shows an Unduh CV CTA on About and Home with a working download link', function () {
    foreach (['about', 'home'] as $page) {
        $this->get(route($page))
            ->assertOk()
            ->assertSee('Unduh CV (PDF)')
            ->assertSee('href="'.route('cv.download').'"', false)
            ->assertSee('download="M-Natsir-Kongah-CV.pdf"', false)
            ->assertDontSee('Buka Contact &amp; Media Hub', false)
            ->assertDontSee('Ajukan Pertanyaan / Undangan Media');
    }
});

it('removes Contact from both desktop and mobile navbar and from public SEO metadata', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();
    preg_match('~<header\b.*?</header>~s', $html, $matches);
    $header = $matches[0] ?? '';

    expect($header)
        ->toContain('Navigasi utama')
        ->toContain('Navigasi seluler')
        ->toContain('Unduh CV')
        ->toContain('href="'.route('cv.download').'"')
        ->not->toContain('href="'.url('/contact').'"')
        ->not->toContain('>Contact<');

    expect(config('seo.sitemap_routes'))->not->toContain('contact');
    expect(config('seo.pages'))->not->toHaveKey('contact');

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertDontSee('<loc>'.url('/contact').'</loc>', false)
        ->assertDontSee('<loc>'.route('cv.download').'</loc>', false);
});
