<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Contact | M. Natsir Kongah')]
class Contact extends Component
{
    public function render(): View
    {
        $accessKey = config('contact.web3forms.access_key');

        return view('livewire.contact', [
            'purposes' => $this->purposes(),
            'mailConfigured' => filled($accessKey),
            'web3formsAccessKey' => $accessKey,
            'web3formsEndpoint' => config('contact.web3forms.endpoint'),
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
}
