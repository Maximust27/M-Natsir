<?php

namespace App\Livewire;

use App\Services\ContactMailer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Contact | M. Natsir Kongah')]
class Contact extends Component
{
    public string $name = '';

    public string $email = '';

    public string $institution = '';

    public string $purpose = '';

    public string $message = '';

    public string $website = '';

    public ?string $statusMessage = null;

    public ?string $statusType = null;

    public function submit(): void
    {
        $this->statusMessage = null;
        $this->statusType = null;

        if (filled($this->website)) {
            $this->statusType = 'error';
            $this->statusMessage = 'Pesan belum dapat dikirim. Silakan coba kembali beberapa saat lagi.';

            return;
        }

        $validated = $this->validate($this->rules(), $this->messages());

        $mailer = app(ContactMailer::class);

        if (! $mailer->configured()) {
            $this->statusType = 'error';
            $this->statusMessage = 'Layanan pengiriman sedang disiapkan.';

            return;
        }

        $rateLimitKey = 'contact-form:'.sha1((string) (request()->ip() ?: 'unknown'));

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $this->statusType = 'error';
            $this->statusMessage = 'Terlalu banyak percobaan. Silakan coba kembali dalam beberapa menit.';

            return;
        }

        RateLimiter::hit($rateLimitKey, 600);

        $validated['purpose_label'] = $this->purposes()[$validated['purpose']];

        if (! $mailer->send($validated)) {
            $this->statusType = 'error';
            $this->statusMessage = 'Pesan belum dapat dikirim. Silakan coba kembali beberapa saat lagi.';

            return;
        }

        $this->reset([
            'name',
            'email',
            'institution',
            'purpose',
            'message',
            'website',
        ]);

        $this->statusType = 'success';
        $this->statusMessage = 'Pesan berhasil dikirim. Terima kasih — permintaan Anda akan ditinjau sesuai prioritas dan konteksnya.';
    }

    public function render(): View
    {
        return view('livewire.contact', [
            'purposes' => $this->purposes(),
            'mailConfigured' => app(ContactMailer::class)->configured(),
            'whatsappUrl' => config('contact.whatsapp_url'),
            'cvUrl' => config('contact.cv_url'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function purposes(): array
    {
        return [
            'media' => 'Media / Interview',
            'academic' => 'Academic / Research',
            'consultation' => 'Consultation / Compliance',
            'speaking' => 'Speaking / Event',
            'other' => 'Other',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'institution' => ['required', 'string', 'max:190'],
            'purpose' => ['required', Rule::in(array_keys($this->purposes()))],
            'message' => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email kerja atau institusi wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'institution.required' => 'Institusi atau media wajib diisi.',
            'purpose.required' => 'Pilih tujuan kontak.',
            'purpose.in' => 'Tujuan kontak tidak valid.',
            'message.max' => 'Pesan maksimal 3000 karakter.',
        ];
    }
}
