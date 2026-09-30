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
        ->assertSee('Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK')
        ->assertSee('Peringati Hari Anti Korupsi Sedunia, Semen Padang Gelar Seminar')
        ->assertSee('Festival Kreatif Generasi Anti Pencucian Uang 2019')
        ->assertDontSee('Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post');
});

it('filters media visits', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'media-visits')
        ->assertSet('activeCategory', 'media-visits')
        ->assertSee('Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post')
        ->assertSee('Sinergi PPATK dan Pikiran Rakyat')
        ->assertDontSee('Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK');
});

it('keeps recognition appearances distinct', function () {
    Livewire::test(Media::class)
        ->call('selectCategory', 'recognition')
        ->assertSet('activeCategory', 'recognition')
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

    expect(
        substr_count(
            $component->html(),
            'Sinergi PPATK dan Pikiran Rakyat: Membangun Kesadaran Publik Akan Bahayanya Pencucian Uang'
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
            'https://ppid.ppatk.go.id/wp-content/uploads/2024/12/WhatsApp-Image-2024-12-27-at-14.23.36-scaled-e1735284758952.jpeg',
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
            'https://www.ppatk.go.id//backend/assets/images/berita_utama/1700468616_1314.JPG',
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
            'https://ppid.ppatk.go.id/wp-content/uploads/2022/09/WhatsApp-Image-2022-09-14-at-09.39.12-e1663123759938.jpeg',
            false
        )
        ->assertSee(
            'https://www.ppatk.go.id//backend/assets/images/berita_utama/1582256211_1032.png',
            false
        )
        ->assertSee(
            'https://img.antaranews.com/cache/1200x800/2019/12/20/WhatsApp-Image-2019-12-20-at-06.04.35.jpeg.webp',
            false
        )
        ->assertSee(
            'https://www.ppatk.go.id//backend/assets/images/berita_utama/1572313967_998.JPG',
            false
        );
});