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
        ->assertSee('PPATK Temukan Penyelewengan Dana Desa Untuk Judi Online')
        ->assertDontSee('Sinergi PPATK dan Pikiran Rakyat');
});

it('filters talks and panels', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'talks')
        ->assertSet('activeCategory', 'talks')
        ->assertSee('Peringati Hari Anti Korupsi Sedunia, Semen Padang Gelar Seminar')
        ->assertDontSee('Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post');
});

it('does not publish PR visits, awards, or Humas administrative events', function () {
    Livewire::test(Media::class)
        ->assertDontSee('Sinergi PPATK dan Pikiran Rakyat')
        ->assertDontSee('PPATK Sabet Penghargaan Gatra Awards 2023')
        ->assertDontSee('Berbagi Pengalaman Keterbukaan Informasi Publik')
        ->assertDontSee('Festival Kreatif Generasi Anti Pencucian Uang 2019');
});

it('searches titles outlets roles topics and summaries', function () {
    Livewire::test(Media::class)
        ->set('search', 'qris')
        ->assertSee('Gunakan QRIS, PPATK Bongkar Modus Baru Judi Online')
        ->assertDontSee('PPATK Sabet Penghargaan Gatra Awards 2023')
        ->set('search', 'dana desa')
        ->assertSee('PPATK Temukan Penyelewengan Dana Desa Untuk Judi Online');
});

it('searches by financial intelligence topic', function () {
    Livewire::test(Media::class)
        ->set('search', 'financial intelligence')
        ->assertSee('PPATK Temukan Penyelewengan Dana Desa Untuk Judi Online')
        ->assertSee('Laporan dari Perbankan Sangat Membantu PPATK Lacak Transaksi Mencurigakan');
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
        ->assertSee('Gunakan QRIS, PPATK Bongkar Modus Baru Judi Online');
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

    expect(
        substr_count(
            $component->html(),
            'PPATK Ungkap 571 Ribu Penerima Bansos Aktif Main Judi Online, Kemensos Siap Evaluasi'
        )
    )->toBe(1);
});

it('renders verified appearance thumbnails', function () {
    Livewire::test(Media::class)
        ->assertSee(
            'https://rricoid-assets.obs.ap-southeast-4.myhuaweicloud.com/berita/Pusat_Pemberitaan/o/1739065236541-natsir/id33lq6ssmo2rkr.jpeg',
            false
        )
        ->assertSee(
            'https://rricoid-assets.obs.ap-southeast-4.myhuaweicloud.com/berita/Pusat_Pemberitaan/o/1737344507040-natsir/4w3o4szadcmcvnz.jpeg',
            false
        )

        ->assertSee(
            'https://cdn.antaranews.com/cache/1200x800/2024/06/15/natsir-kongah.jpeg',
            false
        )
        ->assertSee(
            'https://rricoid-assets.obs.ap-southeast-4.myhuaweicloud.com/berita/Pusat_Pemberitaan/o/1718278388869-natsir_kongah_PPATK/lie2qjmppm8tl7r.jpeg',
            false
        )

        ->assertSee(
            'https://i.ytimg.com/vi/4fzhn91AS9Q/hqdefault.jpg',
            false
        )
        ->assertSee(
            'https://i.ytimg.com/vi/BdxYgioGFxI/hqdefault.jpg',
            false
        )


        ->assertSee(
            'https://img.antaranews.com/cache/1200x800/2019/12/20/WhatsApp-Image-2019-12-20-at-06.04.35.jpeg.webp',
            false
        )
;
});