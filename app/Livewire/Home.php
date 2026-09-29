<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('M. Natsir Kongah | Financial Intelligence Hub')]
class Home extends Component
{
    public string $activePillar = 'all';

    public function selectPillar(string $pillar): void
    {
        if (! array_key_exists($pillar, $this->pillars())) {
            return;
        }

        $this->activePillar = $pillar;
    }

    public function render(): View
    {
        return view('livewire.home', [
            'pillars' => $this->pillars(),
            'featuredWritings' => $this->featuredWritings(),
        ]);
    }

    /**
     * @return array<string, array{label: string, classes: string}>
     */
    private function pillars(): array
    {
        return [
            'all' => [
                'label' => 'Semua',
                'classes' => 'bg-slate-100 text-slate-600 hover:bg-slate-200',
            ],
            'pml' => [
                'label' => '★ Prof. Money Laundering',
                'classes' => 'bg-amber-50 text-amber-800 hover:bg-amber-100',
            ],
            'aml' => [
                'label' => 'AML Regs',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
            'crypto-ai' => [
                'label' => 'Crypto/AI',
                'classes' => 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100',
            ],
            'investigation' => [
                'label' => 'Penyidikan',
                'classes' => 'bg-violet-50 text-violet-800 hover:bg-violet-100',
            ],
        ];
    }

    /**
     * @return array<int, array{
     *     badge: string,
     *     badgeClasses: string,
     *     title: string,
     *     excerpt: string,
     *     action: string,
     *     external: bool
     * }>
     */
    private function featuredWritings(): array
    {
        return [
            [
                'badge' => '★ PML FEATURED',
                'badgeClasses' => 'bg-amber-100 text-amber-800',
                'title' => 'Mengurai Anatomi Professional Money Launderers di RI',
                'excerpt' => 'An in-depth analysis of the structural mechanisms employed by professional enablers in facilitating large-scale money laundering operations within the Indonesian jurisdiction.',
                'action' => 'Baca Ringkasan',
                'external' => false,
            ],
            [
                'badge' => 'KOMPAS ANALISIS',
                'badgeClasses' => 'bg-slate-100 text-slate-700',
                'title' => 'Modus Baru Pencucian Uang Berbasis Aset Kripto & Shadow Bank',
                'excerpt' => 'Examining the convergence of decentralized digital assets and shadow banking systems as the new frontier for obscuring illicit financial flows.',
                'action' => 'Baca di Kompas',
                'external' => true,
            ],
            [
                'badge' => 'FINANCIAL INTEL',
                'badgeClasses' => 'bg-teal-100 text-teal-800',
                'title' => 'Peran Strategis Intel Finansial Menghadapi Trade-Based ML',
                'excerpt' => 'Evaluating the efficacy of current financial intelligence gathering techniques in detecting anomalies within complex cross-border trade transactions.',
                'action' => 'Baca Ringkasan',
                'external' => false,
            ],
        ];
    }
}
