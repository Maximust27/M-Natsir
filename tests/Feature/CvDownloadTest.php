<?php

use Illuminate\Support\Facades\File;

it('serves the existing CV as a PDF attachment', function () {
    $path = public_path('files/m-natsir-kongah-cv.pdf');

    expect(File::exists($path))->toBeTrue();

    $this->get(route('cv.download'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('M-Natsir-Kongah-CV.pdf');
});

it('removes the contact page and old download endpoint', function () {
    $this->get('/contact')->assertNotFound();
    $this->get('/contact/cv')->assertNotFound();

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('href="'.url('/contact').'"', false);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Unduh Curriculum Vitae')
        ->assertSee('href="'.route('cv.download').'"', false);
});
