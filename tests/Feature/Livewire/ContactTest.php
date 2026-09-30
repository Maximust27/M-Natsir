<?php

use App\Livewire\Contact;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

beforeEach(function () {
    config()->set('contact.web3forms.access_key', null);
    config()->set('contact.whatsapp_url', null);
    config()->set('contact.cv_url', null);

    RateLimiter::clear('contact-form:'.sha1('127.0.0.1'));
});

function configureContactMail(): void
{
    config()->set('contact.web3forms.access_key', 'web3forms_test_key');
}

function fillValidContactForm($component): mixed
{
    return $component
        ->set('name', 'Rina Pratama')
        ->set('email', 'rina@example.org')
        ->set('institution', 'Media Nusantara')
        ->set('purpose', 'media');
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

it('validates required fields and supported purposes', function () {
    configureContactMail();

    Livewire::test(Contact::class)
        ->call('submit')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'required',
            'institution' => 'required',
            'purpose' => 'required',
        ]);

    fillValidContactForm(Livewire::test(Contact::class))
        ->set('purpose', 'unsupported')
        ->call('submit')
        ->assertHasErrors(['purpose']);
});

it('shows a safe setup state while mail is unconfigured', function () {
    Http::fake();

    fillValidContactForm(Livewire::test(Contact::class))
        ->call('submit')
        ->assertSee('Layanan pengiriman sedang disiapkan.');

    Http::assertNothingSent();
});

it('sends a valid message through web3forms and resets the form', function () {
    configureContactMail();

    Http::fake([
        'https://api.web3forms.com/submit' => Http::response([
            'success' => true,
            'message' => 'Email sent successfully!',
        ], 200),
    ]);

    fillValidContactForm(Livewire::test(Contact::class))
        ->set('message', '')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('institution', '')
        ->assertSet('purpose', '')
        ->assertSet('message', '')
        ->assertSee('Pesan berhasil dikirim.');

    Http::assertSent(function (Request $request): bool {
        $data = $request->data();

        return $request->url() === 'https://api.web3forms.com/submit'
            && $data['access_key'] === 'web3forms_test_key'
            && $data['subject'] === '[Website Contact] Media / Interview — Rina Pratama'
            && $data['name'] === 'Rina Pratama'
            && $data['email'] === 'rina@example.org'
            && $data['institution'] === 'Media Nusantara'
            && $data['purpose'] === 'Media / Interview';
    });
});

it('preserves form values when the provider fails', function () {
    configureContactMail();

    Http::fake([
        'https://api.web3forms.com/submit' => Http::response([
            'success' => false,
            'message' => 'Invalid access key',
        ], 422),
    ]);

    fillValidContactForm(Livewire::test(Contact::class))
        ->set('message', 'Mohon wawancara sebelum Jumat.')
        ->call('submit')
        ->assertSet('name', 'Rina Pratama')
        ->assertSet('email', 'rina@example.org')
        ->assertSet('message', 'Mohon wawancara sebelum Jumat.')
        ->assertSee('Pesan belum dapat dikirim. Silakan coba kembali beberapa saat lagi.');
});

it('blocks honeypot submissions without contacting the provider', function () {
    configureContactMail();
    Http::fake();

    fillValidContactForm(Livewire::test(Contact::class))
        ->set('website', 'https://spam.example')
        ->call('submit');

    Http::assertNothingSent();
});

it('rate limits repeated contact submissions', function () {
    configureContactMail();

    Http::fake([
        'https://api.web3forms.com/submit' => Http::response([
            'success' => true,
            'message' => 'Email sent successfully!',
        ], 200),
    ]);

    $component = Livewire::test(Contact::class);

    foreach (range(1, 4) as $attempt) {
        fillValidContactForm($component)
            ->set('name', 'Rina Pratama '.$attempt)
            ->call('submit');
    }

    Http::assertSentCount(3);

    $component->assertSee('Terlalu banyak percobaan. Silakan coba kembali dalam beberapa menit.');
});

it('never renders the web3forms access key', function () {
    configureContactMail();

    Livewire::test(Contact::class)
        ->assertDontSee('web3forms_test_key');
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
        ->assertSee('/files/m-natsir-kongah-cv.pdf', false)
        ->assertSee('Mulai Chat')
        ->assertSee('Download CV');
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
