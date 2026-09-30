<?php

use App\Livewire\Media;
use Livewire\Livewire;

it('renders the media appearances archive', function () {
    $this->get(route('media'))
        ->assertOk()
        ->assertSee('MEDIA & APPEARANCES')
        ->assertSee('Public Record')
        ->assertSeeLivewire(Media::class);
});

it('filters interviews and broadcasts', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'interviews')
        ->assertSet('activeCategory', 'interviews')
        ->assertSee('PPATK: Mayoritas Pelaku Judi Online Berpenghasilan Rendah')
        ->assertSee('Gunakan QRIS, PPATK Bongkar Modus Baru Judi Online')
        ->assertDontSee('Sinergi PPATK dan Pikiran Rakyat');
});

it('filters talks panels and media visits', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'talks')
        ->assertSee('Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK')
        ->assertSee('Peringati Hari Anti Korupsi Sedunia, Semen Padang Gelar Seminar')
        ->assertDontSee('Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post')
        ->call('selectCategory', 'media-visits')
        ->assertSee('Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post')
        ->assertSee('Sinergi PPATK dan Pikiran Rakyat')
        ->assertDontSee('Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK');
});

it('keeps recognition appearances distinct', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'recognition')
        ->assertSee('PPATK Sabet Penghargaan Gatra Awards 2023')
        ->assertSee('PPATK Teguhkan Komitmen Sebagai Badan Publik Informatif')
        ->assertDontSee('Gunakan QRIS, PPATK Bongkar Modus Baru Judi Online');
});

it('searches titles outlets roles topics and summaries', function () {
    Livewire::test(Media::class)
        ->set('search', 'qris')
        ->assertSee('Gunakan QRIS, PPATK Bongkar Modus Baru Judi Online')
        ->assertDontSee('PPATK Sabet Penghargaan Gatra Awards 2023')
        ->set('search', 'keterbukaan informasi')
        ->assertSee('Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK');
});

it('ignores unknown media categories', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'unknown')
        ->assertSet('activeCategory', 'all');
});

it('clears media filters', function () {
    Livewire::test(Media::class)
        ->set('search', 'qris')
        ->call('selectCategory', 'interviews')
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('activeCategory', 'all')
        ->assertSee('PPATK Sabet Penghargaan Gatra Awards 2023');
});

it('shows a useful empty state', function () {
    Livewire::test(Media::class)
        ->set('search', 'zzzz-no-media-result')
        ->assertSee('Appearance belum ditemukan')
        ->assertSee('Reset filter');
});

it('does not treat press contact only mentions as appearances', function () {
    Livewire::test(Media::class)
        ->assertDontSee('NARAHUBUNG MEDIA');
});

it('links media navigation to the top level route', function () {
    $this->get(route('media'))
        ->assertOk()
        ->assertSee('href="'.route('media').'"', false)
        ->assertSee('Articles')
        ->assertSee('Library');
});

it('does not duplicate the featured appearance in the default archive grid', function () {
    $component = Livewire::test(Media::class);

    expect(substr_count($component->html(), 'Sinergi PPATK dan Pikiran Rakyat: Membangun Kesadaran Publik Akan Bahayanya Pencucian Uang'))
        ->toBe(1);
});

it('renders verified appearance thumbnails from source-linked media', function () {
    Livewire::test(Media::class)
        ->assertSee('https://cdn.antaranews.com/cache/1200x800/2024/06/15/natsir-kongah.jpeg', false)
        ->assertSee('https://static.gatra.com/foldershared/images/2023/iwan/11-Nov/IMG_20231118_123815.jpg', false)
        ->assertSee('https://ppid.ppatk.go.id/wp-content/uploads/2024/12/WhatsApp-Image-2024-12-27-at-14.23.36-scaled-e1735284758952.jpeg', false);
});
