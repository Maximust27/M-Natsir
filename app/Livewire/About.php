<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Tentang | M. Natsir Kongah')]
class About extends Component
{
    public function render(): View
    {
        return view('livewire.about', [
            'profiles' => $this->profiles(),
            'focusAreas' => $this->focusAreas(),
            'publicContributions' => $this->publicContributions(),
            'sourceNotes' => $this->sourceNotes(),
        ]);
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    private function profiles(): array
    {
        return [
            [
                'label' => 'LinkedIn',
                'url' => 'https://id.linkedin.com/in/m-natsir-kongah-221615130',
            ],
            [
                'label' => 'PPATK / PPID',
                'url' => 'https://ppid.ppatk.go.id/?p=15022',
            ],
            [
                'label' => 'Kumparan',
                'url' => 'https://kumparan.com/natsir-kongah',
            ],
        ];
    }

    /**
     * @return array<int, array{label: string, title: string, description: string, classes: string}>
     */
    private function focusAreas(): array
    {
        return [
            [
                'label' => 'Professional Money Laundering',
                'title' => 'Pencuci uang profesional & gatekeepers',
                'description' => 'Membaca peran aktor berkeahlian hukum, akuntansi, perbankan, korporasi, dan jaringan lintas negara dalam menyamarkan hasil kejahatan.',
                'classes' => 'bg-amber-100 text-amber-900',
            ],
            [
                'label' => 'Financial Intelligence',
                'title' => 'Follow the money menuju follow the value',
                'description' => 'Menelusuri perpindahan nilai melalui rekening, properti, badan usaha, komoditas, aset virtual, dan instrumen lain yang dapat menyimpan atau memindahkan kekayaan.',
                'classes' => 'bg-slate-100 text-slate-800',
            ],
            [
                'label' => 'Asset Recovery',
                'title' => 'Pemulihan aset & perlindungan hak',
                'description' => 'Mengkaji efektivitas perampasan hasil kejahatan sekaligus pentingnya batas kewenangan, due process, dan perlindungan pihak ketiga yang beritikad baik.',
                'classes' => 'bg-indigo-100 text-indigo-900',
            ],
            [
                'label' => 'Digital Financial Crime',
                'title' => 'Kripto, judi online & kejahatan lintas batas',
                'description' => 'Mengamati bagaimana teknologi finansial mempercepat perpindahan nilai dan menciptakan tantangan baru bagi deteksi serta kerja sama lintas yurisdiksi.',
                'classes' => 'bg-teal-100 text-teal-900',
            ],
            [
                'label' => 'Public Information',
                'title' => 'Keterbukaan informasi & literasi publik',
                'description' => 'Menempatkan komunikasi publik sebagai bagian penting dari integritas lembaga, edukasi masyarakat, dan penguatan kepercayaan terhadap rezim anti pencucian uang.',
                'classes' => 'bg-violet-100 text-violet-900',
            ],
        ];
    }

    /**
     * @return array<int, array{title: string, description: string}>
     */
    private function publicContributions(): array
    {
        return [
            [
                'title' => 'Pengajaran Anti Pencucian Uang',
                'description' => 'Profil publik mencatat aktivitas mengajar dan pembelajaran APU sejak awal 2000-an, termasuk materi rezim anti pencucian uang.',
            ],
            [
                'title' => 'Kehumasan & Keterbukaan Informasi',
                'description' => 'Rekam publik PPATK menunjukkan peran berkelanjutan dalam hubungan masyarakat, layanan informasi publik, dan komunikasi isu kejahatan keuangan.',
            ],
            [
                'title' => 'Tulisan & Analisis Publik',
                'description' => 'Aktif menulis opini mengenai professional money laundering, aset kripto, scam laundering, pemblokiran transaksi, risk-based supervision, dan asset recovery.',
            ],
            [
                'title' => 'Dialog Media & Edukasi',
                'description' => 'Menjadi narasumber media dan forum publik untuk menjelaskan isu transaksi mencurigakan, modus pencucian uang, serta kewenangan dalam penanganan TPPU.',
            ],
        ];
    }

    /**
     * @return array<int, array{source: string, title: string, url: string}>
     */
    private function sourceNotes(): array
    {
        return [
            [
                'source' => 'LinkedIn',
                'title' => 'Riwayat profesional, pengajaran, lokasi, dan pendidikan pascasarjana.',
                'url' => 'https://id.linkedin.com/in/m-natsir-kongah-221615130',
            ],
            [
                'source' => 'PPID PPATK',
                'title' => 'Rekam peran kehumasan, keterbukaan informasi, dan layanan informasi publik.',
                'url' => 'https://ppid.ppatk.go.id/?p=15022',
            ],
            [
                'source' => 'Kumparan',
                'title' => 'Tulisan mengenai professional money laundering dan perlindungan hak dalam asset recovery.',
                'url' => 'https://kumparan.com/natsir-kongah',
            ],
            [
                'source' => 'Detik',
                'title' => 'Opini mengenai pertemuan judi online, aset kripto, dan pencucian uang digital.',
                'url' => 'https://news.detik.com/kolom/d-8487238/ketika-judi-online-bertemu-kripto-ancaman-baru-pencucian-uang-digital',
            ],
            [
                'source' => 'Majalah Editor',
                'title' => 'Tulisan tentang follow the value, scam laundering, dan pengawasan berbasis risiko.',
                'url' => 'https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/',
            ],
        ];
    }
}
