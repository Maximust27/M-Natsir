<?php

use App\Support\Seo\StructuredData;

function aboutPersonalProfilesHtml(string $html): string
{
    preg_match('~<div[^>]*aria-label="Profil dan kontak personal"[^>]*>(.*?)</div>~s', $html, $match);

    return $match[1] ?? '';
}

it('shows curated personal profiles and a clearly disabled WhatsApp label when not configured', function () {
    config()->set('contact.whatsapp_url', null);

    $html = $this->get(route('about'))->assertOk()->getContent();
    $profiles = aboutPersonalProfilesHtml($html);

    expect($profiles)
        ->toContain('>LinkedIn')
        ->toContain('>Kumparan')
        ->toContain('>Academia.edu')
        ->toContain('>Artikel Majalah Editor')
        ->toContain('WhatsApp')
        ->toContain('aria-label="WhatsApp belum tersedia"')
        ->toContain('https://independent.academia.edu/NatsirKongah')
        ->toContain('https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/')
        ->not->toContain('PPATK / PPID')
        ->not->toContain('href=""');
});

it('links WhatsApp to the existing configured personal contact rather than an invented number', function () {
    $url = 'https://wa.me/620000000000';
    config()->set('contact.whatsapp_url', $url);

    $html = $this->get(route('about'))->assertOk()->getContent();
    $profiles = aboutPersonalProfilesHtml($html);

    expect($profiles)
        ->toContain('href="'.$url.'"')
        ->toContain('WhatsApp')
        ->toContain('rel="noopener noreferrer"')
        ->not->toContain('WhatsApp belum tersedia');

    // The WhatsApp setting is shared with the existing Contact component.
    $this->get(route('contact'))->assertSee('href="'.$url.'"', false);
});

it('rejects unsafe or unrelated URLs in the WhatsApp shortcut', function (string $url) {
    config()->set('contact.whatsapp_url', $url);

    $profiles = aboutPersonalProfilesHtml($this->get(route('about'))->assertOk()->getContent());

    expect($profiles)
        ->toContain('WhatsApp belum tersedia')
        ->not->toContain('href="'.$url.'"');
})->with([
    'non-whatsapp host' => ['https://example.com/whatsapp'],
    'insecure scheme' => ['http://wa.me/620000000000'],
    'host impersonation' => ['https://wa.me.evil.example/620000000000'],
]);

it('adds the Academia author profile to Person sameAs without declaring an article as a social identity', function () {
    $sameAs = config('seo.person.same_as');

    expect($sameAs)
        ->toContain('https://independent.academia.edu/NatsirKongah')
        ->not->toContain('https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/')
        ->not->toContain('https://ppid.ppatk.go.id/?p=15022');

    $about = $this->get(route('about'))->assertOk()->getContent();
    preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $about, $matches);
    $schema = json_decode($matches[1] ?? '', true, 512, JSON_THROW_ON_ERROR);
    $profile = collect($schema['@graph'])->firstWhere('@type', 'ProfilePage');

    expect($profile['mainEntity']['sameAs'])->toBe($sameAs);
});
