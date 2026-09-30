<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactMailer
{
    public function configured(): bool
    {
        return filled(config('contact.web3forms.access_key'));
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

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(10)
                ->post((string) config('contact.web3forms.endpoint'), [
                    'access_key' => (string) config('contact.web3forms.access_key'),
                    'subject' => '[Website Contact] '.$data['purpose_label'].' — '.$data['name'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'institution' => $data['institution'],
                    'purpose' => $data['purpose_label'],
                    'message' => filled($data['message'] ?? null)
                        ? trim((string) $data['message'])
                        : 'Tidak ada pesan tambahan atau tenggat waktu.',
                ]);

            if (! $response->successful() || $response->json('success') !== true) {
                Log::warning('Contact form provider rejected a request.', [
                    'status' => $response->status(),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $exception) {
            Log::warning('Contact form provider request failed.', [
                'exception' => $exception::class,
            ]);

            return false;
        }
    }
}
