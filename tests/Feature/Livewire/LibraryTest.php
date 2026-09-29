<?php

use App\Livewire\Library;
use Livewire\Livewire;

it('renders the research library', function () {
    $this->get(route('library'))
        ->assertOk()
        ->assertSee('LIBRARY & REFERENSI')
        ->assertSee('Karya M. Natsir Kongah, kontribusi institusional, dan sumber rujukan untuk riset kejahatan keuangan.')
        ->assertSeeLivewire(Library::class);
});

it('surfaces authored and coauthored works by M Natsir Kongah', function () {
    Livewire::test(Library::class)
        ->set('collection', 'natsir')
        ->assertSee('YAYASAN, SOEKARNO DAN PENCUCIAN UANG')
        ->assertSee('Membongkar Mafia dengan UU Pencucian Uang')
        ->assertSee('Menggunakan Undang Undang Anti Pencucian Uang untuk Mengatasi Kejahatan')
        ->assertDontSee('The FATF Recommendations');
});

it('keeps institutional contributions separate from authored works', function () {
    Livewire::test(Library::class)
        ->set('collection', 'institutional')
        ->assertSee('Laporan Semester I PPATK 2020')
        ->assertDontSee('YAYASAN, SOEKARNO DAN PENCUCIAN UANG')
        ->assertDontSee('The FATF Recommendations');
});

it('keeps external research references as a separate collection', function () {
    Livewire::test(Library::class)
        ->set('collection', 'reference')
        ->assertSee('The FATF Recommendations')
        ->assertSee('Asset Recovery Handbook')
        ->assertDontSee('Membongkar Mafia dengan UU Pencucian Uang');
});

it('filters resources by document type', function () {
    Livewire::test(Library::class)
        ->set('resourceType', 'risk-assessment')
        ->assertSee('Penilaian Risiko Indonesia Terhadap Tindak Pidana Pencucian Uang Tahun 2021')
        ->assertDontSee('Asset Recovery Handbook');
});

it('filters resources by institution', function () {
    Livewire::test(Library::class)
        ->set('institution', 'ppatk')
        ->assertSee('Penilaian Risiko Sektoral Tindak Pidana Siber Tahun 2024')
        ->assertSee('Laporan Semester I PPATK 2020')
        ->assertDontSee('The FATF Recommendations');
});

it('searches library metadata and summaries', function () {
    Livewire::test(Library::class)
        ->set('search', 'beneficial ownership')
        ->assertSee('Guidance on Beneficial Ownership of Legal Persons')
        ->assertSee('Concealment of Beneficial Ownership')
        ->assertDontSee('Asset Recovery Handbook');
});

it('combines topic and institution filters', function () {
    Livewire::test(Library::class)
        ->set('topic', 'digital-finance')
        ->set('institution', 'fatf')
        ->assertSee('Virtual Assets and VASPs: Targeted Update 2025')
        ->assertSee('Regulatory Challenges from DeFi')
        ->assertDontSee('Penilaian Risiko Sektoral Teknologi Finansial Tahun 2023');
});
