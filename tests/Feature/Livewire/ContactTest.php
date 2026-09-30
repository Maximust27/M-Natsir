<?php

use App\Livewire\Contact;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

beforeEach(function () {
    config()->set('contact.web3forms.access_key', null);
    config()->set('contact.web3forms.endpoint', 'https://api.web3forms.com/submit');
    config()->set('contact.whatsapp_url', null);
    config()->set('contact.cv_url', null);
});

function configureContactForm(): void
{
    config()->set('contact.web3forms.access_key', 'web3forms_test_key');
}

it('renders the dedicated contact page', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('Hubungi M. Natsir Kongah')
        ->assertSee('Form Komunikasi')
        ->assertSee('Panduan Media')
        ->assertSee('WhatsApp')
        ->assertSee('Professional CV')
        ->assertSeeLivewire(Contact::class);
});

it('renders the configured form fields', function () {
    Livewire::test(Contact::class)
        ->assertSee('Nama Lengkap')
        ->assertSee('Email Kerja Institusi')
        ->assertSee('Institusi / Media')
        ->assertSee('Tujuan Kontak')
        ->assertSee('Pesan & Tenggat Waktu');
});

it('submits directly from the browser to web3forms when configured', function () {
    configureContactForm();

    Livewire::test(Contact::class)
        ->assertSee('name="access_key"', false)
        ->assertSee('value="web3forms_test_key"', false)
        ->assertSee('https://api.web3forms.com/submit', false)
        ->assertSee('name="botcheck"', false)
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="institution"', false)
        ->assertSee('name="purpose"', false)
        ->assertSee('name="message"', false)
        ->assertSee('Kirim Pesan Terverifikasi')
        ->assertDontSee('wire:submit', false);

    expect(method_exists(Contact::class, 'submit'))->toBeFalse();
});

it('keeps the form disabled while the web3forms key is missing', function () {
    Livewire::test(Contact::class)
        ->assertSee('Layanan pengiriman sedang disiapkan.')
        ->assertDontSee('name="access_key"', false)
        ->assertSee('configured: false', false)
        ->assertSee('x-bind:disabled="submitting || ! configured"', false)
        ->assertSee('disabled', false);
});

it('uses browser validation and the web3forms honeypot', function () {
    configureContactForm();

    Livewire::test(Contact::class)
        ->assertSee('required', false)
        ->assertSee('maxlength="120"', false)
        ->assertSee('maxlength="190"', false)
        ->assertSee('maxlength="3000"', false)
        ->assertSee('name="botcheck"', false);
});

it('renders client side success and failure feedback copy', function () {
    configureContactForm();

    Livewire::test(Contact::class)
        ->assertSee('Pesan berhasil dikirim.')
        ->assertSee('Pesan belum dapat dikirim. Silakan coba kembali beberapa saat lagi.');
});

it('renders graceful whatsapp and cv fallbacks', function () {
    Livewire::test(Contact::class)
        ->assertSee('Segera tersedia')
        ->assertSee('CV segera tersedia')
        ->assertDontSee('Mulai Chat')
        ->assertDontSee('Download CV');
});

it('renders configured whatsapp and cv actions', function () {
    config()->set('contact.whatsapp_url', 'https://wa.me/628123456789');
    config()->set('contact.cv_url', '/files/m-natsir-kongah-cv.pdf');

    Livewire::test(Contact::class)
        ->assertSee('https://wa.me/628123456789', false)
        ->assertSee('/contact/cv', false)
        ->assertSee('Mulai Chat')
        ->assertSee('Download CV');
});

it('serves the local cv as an attachment download', function () {
    $directory = public_path('files');
    $path = $directory.'/m-natsir-kongah-cv.pdf';
    $original = File::exists($path) ? File::get($path) : null;

    File::ensureDirectoryExists($directory);
    File::put($path, "%PDF-1.4\ncontact-test\n");

    try {
        $this->get('/contact/cv')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('M-Natsir-Kongah-CV.pdf');
    } finally {
        if ($original !== null) {
            File::put($path, $original);
        } else {
            File::delete($path);
        }
    }
});



it('streams the cv without byte range negotiation', function () {
    $directory = public_path('files');
    $path = $directory.'/m-natsir-kongah-cv.pdf';
    $original = File::exists($path) ? File::get($path) : null;

    File::ensureDirectoryExists($directory);
    File::put($path, "%PDF-1.4\ncontact-range-test\n");

    try {
        $response = $this
            ->withHeader('Range', 'bytes=0-')
            ->get('/contact/cv');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('M-Natsir-Kongah-CV.pdf');

        expect($response->headers->has('accept-ranges'))->toBeFalse();
    } finally {
        if ($original !== null) {
            File::put($path, $original);
        } else {
            File::delete($path);
        }
    }
});

it('integrates contact into shared navigation and home teaser', function () {
    $contactUrl = route('contact');

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('href="'.$contactUrl.'"', false);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="'.$contactUrl.'"', false)
        ->assertDontSee(
            'class="min-h-8 font-semibold text-ink transition hover:text-black">About</a>',
            false,
        );
});
