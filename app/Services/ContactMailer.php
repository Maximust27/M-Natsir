<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactMailer
{
    public function configured(): bool
    {
        return filled(config('contact.email.to'))
            && filled(config('contact.email.from'))
            && filled(config('contact.resend.key'));
    }

    /**
     * @param  array{
     *     name: string,
     *     email: string,
     *     institution: string,
     *     purpose: string,
     *     purpose_label: string,
     *     message?: string|null
     * }  $data
     */
    public function send(array $data): bool
    {
        if (! $this->configured()) {
            return false;
        }

        $fromName = trim((string) config('contact.email.from_name', 'M. Natsir Kongah'));
        $fromEmail = trim((string) config('contact.email.from'));
        $to = trim((string) config('contact.email.to'));

        $text = implode("\n", array_filter([
            'Website Contact — M. Natsir Kongah',
            '',
            'Nama: '.$data['name'],
            'Email: '.$data['email'],
            'Institusi / Media: '.$data['institution'],
            'Tujuan Kontak: '.$data['purpose_label'],
            filled($data['message'] ?? null) ? 'Pesan / Tenggat: '.trim((string) $data['message']) : null,
            '',
            'Dikirim: '.now()->toIso8601String(),
        ], static fn ($line): bool => $line !== null));

        try {
            $response = Http::withToken((string) config('contact.resend.key'))
                ->acceptJson()
                ->asJson()
                ->timeout(10)
                ->post((string) config('contact.resend.endpoint'), [
                    'from' => $fromName.' <'.$fromEmail.'>',
                    'to' => [$to],
                    'reply_to' => $data['email'],
                    'subject' => '[Website Contact] '.$data['purpose_label'].' — '.$data['name'],
                    'text' => $text,
                ]);

            if (! $response->successful()) {
                Log::warning('Contact email provider rejected a request.', [
                    'status' => $response->status(),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $exception) {
            Log::warning('Contact email provider request failed.', [
                'exception' => $exception::class,
            ]);

            return false;
        }
    }
}
