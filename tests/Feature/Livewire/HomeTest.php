<?php

use App\Livewire\Home;
use Livewire\Livewire;

it('renders the home page with verified featured writings', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('M. NATSIR KONGAH')
        ->assertSee('Featured Writings')
        ->assertSee('Kejahatan Kini Tak Lagi Memiliki Kotak')
        ->assertSee('Dimana Uang Penipu Berhenti')
        ->assertSee('Batas Negara atas Harta')
        ->assertSee('Campaign funding: Lesson from Watergate')
        ->assertDontSee('Menelusuri Aliran Dana Gelap')
        ->assertDontSee('★ Prof. Money LaunderingAML RegsCrypto/AIPenyidikan')
        ->assertSeeLivewire(Home::class);
});

it('links featured home content to real publication sources', function () {
    Livewire::test(Home::class)
        ->assertSee('https://majalaheditor.com/insight/kejahatan-kini-tak-lagi-memiliki-kotak/', false)
        ->assertSee('https://majalaheditor.com/dimana-uang-penipu-berhenti/', false)
        ->assertSee('https://kumparan.com/natsir-kongah/batas-negara-atas-harta-289MtKTiAEo', false)
        ->assertSee('https://www.thejakartapost.com/news/2009/07/27/campaign-funding-lesson-watergate', false);
});
