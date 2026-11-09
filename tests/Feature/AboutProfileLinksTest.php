<?php

function aboutPersonalProfilesHtml(string $html): string
{
    preg_match('~<div[^>]*aria-label="Profil profesional"[^>]*>(.*?)</div>~s', $html, $match);

    return $match[1] ?? '';
}

it('shows curated professional profiles without contact actions', function () {
    $html = $this->get(route('about'))->assertOk()->getContent();
    $profiles = aboutPersonalProfilesHtml($html);

    expect($profiles)
        ->toContain('LinkedIn')
        ->toContain('Kumparan')
        ->toContain('Academia.edu')
        ->toContain('Artikel Majalah Editor')
        ->not->toContain('WhatsApp')
        ->not->toContain('href=""');
});

it('links the existing cv download from about and home', function () {
    $url = route('cv.download');

    $this->get(route('about'))->assertOk()->assertSee('href="'.$url.'"', false)->assertSee('Download CV');
    $this->get(route('home'))->assertOk()->assertSee('href="'.$url.'"', false)->assertDontSee('Inquiries & Consultations');
});

it('adds the Academia author profile to Person sameAs without declaring an article as a social identity', function () {
    $sameAs = config('seo.person.same_as');

    expect($sameAs)
        ->toContain('https://independent.academia.edu/NatsirKongah')
        ->not->toContain('https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/')
        ->not->toContain('https://ppid.ppatk.go.id/?p=15022');

    $about = $this->get(route('about'))->assertOk()->getContent();
    preg_match('~<script type="application/ld\\+json">(.*?)</script>~s', $about, $matches);
    $schema = json_decode($matches[1] ?? '', true, 512, JSON_THROW_ON_ERROR);
    $profile = collect($schema['@graph'])->firstWhere('@type', 'ProfilePage');

    expect($profile['mainEntity']['sameAs'])->toBe($sameAs);
});
