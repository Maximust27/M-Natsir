<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Artikel & Analisis | M. Natsir Kongah')]
class Articles extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'category', except: 'all')]
    public string $activeCategory = 'all';

    #[Url(as: 'page', except: 1)]
    public int $page = 1;

    private int $perPage = 5;

    public function updatedSearch(): void
    {
        $this->page = 1;
    }

    public function selectCategory(string $category): void
    {
        if (! array_key_exists($category, $this->categories())) {
            return;
        }

        $this->activeCategory = $category;
        $this->page = 1;
    }

    public function goToPage(int $page): void
    {
        $this->page = max(1, min($page, $this->totalPages()));
    }

    public function nextPage(): void
    {
        $this->goToPage($this->page + 1);
    }

    public function previousPage(): void
    {
        $this->goToPage($this->page - 1);
    }

    public function render(): View
    {
        $filteredArticles = $this->filteredArticles();
        $total = count($filteredArticles);
        $totalPages = max(1, (int) ceil($total / $this->perPage));

        $this->page = max(1, min($this->page, $totalPages));

        $offset = ($this->page - 1) * $this->perPage;
        $visibleArticles = array_slice($filteredArticles, $offset, $this->perPage);

        return view('livewire.articles', [
            'categories' => $this->categories(),
            'articles' => $visibleArticles,
            'resultCount' => $total,
            'totalPages' => $totalPages,
            'resultFrom' => $total === 0 ? 0 : $offset + 1,
            'resultTo' => min($offset + $this->perPage, $total),
        ]);
    }

    /**
     * @return array<string, array{label: string, classes: string, count: int}>
     */
    private function categories(): array
    {
        $articles = $this->articles();

        $definitions = [
            'all' => [
                'label' => 'Semua',
                'classes' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ],
            'pml' => [
                'label' => '★ Professional Money Laundering',
                'classes' => 'bg-amber-50 text-amber-900 hover:bg-amber-100',
            ],
            'aml' => [
                'label' => 'AML Regs',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
            'financial-intelligence' => [
                'label' => 'Financial Intelligence',
                'classes' => 'bg-teal-50 text-teal-800 hover:bg-teal-100',
            ],
            'white-collar' => [
                'label' => 'White-Collar Crime',
                'classes' => 'bg-rose-50 text-rose-800 hover:bg-rose-100',
            ],
            'future-crime' => [
                'label' => 'Future Financial Crime',
                'classes' => 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100',
            ],
        ];

        foreach ($definitions as $key => &$definition) {
            $definition['count'] = $key === 'all'
                ? count($articles)
                : count(array_filter($articles, fn (array $article): bool => $article['category'] === $key));
        }

        unset($definition);

        return $definitions;
    }

    /**
     * @return array<int, array{
     *     category: string,
     *     categoryLabel: string,
     *     badgeClasses: string,
     *     source: string,
     *     date: string,
     *     title: string,
     *     excerpt: string,
     *     url: string
     * }>
     */
    private function filteredArticles(): array
    {
        $articles = $this->articles();
        $category = array_key_exists($this->activeCategory, $this->categories())
            ? $this->activeCategory
            : 'all';

        if ($category !== 'all') {
            $articles = array_values(array_filter(
                $articles,
                fn (array $article): bool => $article['category'] === $category,
            ));
        }

        $search = mb_strtolower(trim($this->search));

        if ($search === '') {
            return $articles;
        }

        return array_values(array_filter($articles, function (array $article) use ($search): bool {
            $haystack = mb_strtolower(implode(' ', [
                $article['title'],
                $article['excerpt'],
                $article['source'],
                $article['categoryLabel'],
            ]));

            return str_contains($haystack, $search);
        }));
    }

    private function totalPages(): int
    {
        return max(1, (int) ceil(count($this->filteredArticles()) / $this->perPage));
    }

    /**
     * @return array<int, array{
     *     category: string,
     *     categoryLabel: string,
     *     badgeClasses: string,
     *     source: string,
     *     date: string,
     *     title: string,
     *     excerpt: string,
     *     url: string
     * }>
     */
    private function articles(): array
    {
        return [
            [
                'category' => 'future-crime',
                'categoryLabel' => 'Future Financial Crime',
                'badgeClasses' => 'bg-emerald-100 text-emerald-900',
                'source' => 'Majalah Editor · Insight',
                'date' => '23 September 2026',
                'title' => 'Kejahatan Kini Tak Lagi Memiliki Kotak',
                'excerpt' => 'Membaca bagaimana kejahatan digital, aliran uang, pelaku, dan infrastruktur kini dapat tersebar lintas negara lalu dirakit kembali sebagai satu operasi keuangan yang sulit dipetakan secara sektoral.',
                'url' => 'https://majalaheditor.com/insight/kejahatan-kini-tak-lagi-memiliki-kotak/',
            ],
            [
                'category' => 'pml',
                'categoryLabel' => '★ Professional Money Laundering',
                'badgeClasses' => 'bg-amber-100 text-amber-900',
                'source' => 'Majalah Editor',
                'date' => '17 September 2026',
                'title' => 'Dimana Uang Penipu Berhenti',
                'excerpt' => 'Menelusuri scam laundering melampaui rekening penampung: dari perpindahan kendali hingga pertanyaan tentang siapa yang akhirnya menguasai dan menikmati hasil kejahatan.',
                'url' => 'https://majalaheditor.com/dimana-uang-penipu-berhenti/',
            ],
            [
                'category' => 'aml',
                'categoryLabel' => 'AML Regs',
                'badgeClasses' => 'bg-indigo-100 text-indigo-900',
                'source' => 'Kumparan',
                'date' => '9 September 2026',
                'title' => 'Batas Negara atas Harta',
                'excerpt' => 'Catatan tentang asset recovery, non-conviction based confiscation, kontrol pengadilan, dan pentingnya perlindungan bagi pihak ketiga yang beritikad baik dalam perampasan aset.',
                'url' => 'https://kumparan.com/natsir-kongah/batas-negara-atas-harta-289MtKTiAEo',
            ],
            [
                'category' => 'financial-intelligence',
                'categoryLabel' => 'Financial Intelligence',
                'badgeClasses' => 'bg-teal-100 text-teal-900',
                'source' => 'Majalah Editor',
                'date' => '8 September 2026',
                'title' => 'Nilai Bergerak, Informasi Tertinggal',
                'excerpt' => 'Mengembangkan prinsip follow the money menjadi follow the value agar asal-usul kekayaan tetap terbaca saat dana berubah menjadi emas, properti, badan usaha, atau bentuk nilai lain.',
                'url' => 'https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/',
            ],
            [
                'category' => 'financial-intelligence',
                'categoryLabel' => 'Financial Intelligence',
                'badgeClasses' => 'bg-teal-100 text-teal-900',
                'source' => 'Republika',
                'date' => '30 Agustus 2026',
                'title' => 'Bank Harus Mengenal Siapa di Balik Uang',
                'excerpt' => 'Beneficial ownership dan customer due diligence dibaca bukan sekadar sebagai administrasi identitas, tetapi sebagai upaya memahami siapa yang benar-benar mengendalikan dana dan struktur transaksi.',
                'url' => 'https://analisis.republika.co.id/berita/tkkjqf393/bank-harus-mengenal-siapa-di-balik-uang',
            ],
            [
                'category' => 'aml',
                'categoryLabel' => 'AML Regs',
                'badgeClasses' => 'bg-indigo-100 text-indigo-900',
                'source' => 'Majalah Editor',
                'date' => '29 Agustus 2026',
                'title' => 'Negeri dengan Peta Risiko, tetapi Siapa yang Menjaga Pintu?',
                'excerpt' => 'Menakar pengawasan berbasis risiko setelah NRA 2026, terutama pada profesi dan sektor non-keuangan yang dapat menjadi pintu masuk ketika kontrol di sektor lain semakin kuat.',
                'url' => 'https://majalaheditor.com/negeri-dengan-peta-risiko-tetapi-siapa-yang-menjaga-pintu/',
            ],
            [
                'category' => 'pml',
                'categoryLabel' => '★ Professional Money Laundering',
                'badgeClasses' => 'bg-amber-100 text-amber-900',
                'source' => 'Kumparan',
                'date' => '27 Agustus 2026',
                'title' => 'Ketika Kejahatan Memiliki Manajer Investasi',
                'excerpt' => 'Mengulas risiko ketika keahlian pengelolaan investasi disalahgunakan untuk menciptakan jarak antara hasil kejahatan, aset, dan pemilik manfaat sebenarnya.',
                'url' => 'https://kumparan.com/natsir-kongah/ketika-kejahatan-memiliki-manajer-investasi-284AUtvcBq0',
            ],
            [
                'category' => 'aml',
                'categoryLabel' => 'AML Regs',
                'badgeClasses' => 'bg-indigo-100 text-indigo-900',
                'source' => 'Majalah Editor',
                'date' => '25 Agustus 2026',
                'title' => 'Ketika Rekening Tak Bisa Digunakan',
                'excerpt' => 'Membedakan penundaan transaksi, penghentian sementara, dan pemblokiran agar kewenangan dalam penanganan TPPU dipahami secara lebih presisi oleh publik.',
                'url' => 'https://majalaheditor.com/ketika-rekening-tak-bisa-digunakan/',
            ],
            [
                'category' => 'white-collar',
                'categoryLabel' => 'White-Collar Crime',
                'badgeClasses' => 'bg-rose-100 text-rose-900',
                'source' => 'Kontan Insight',
                'date' => '21 Agustus 2026',
                'title' => 'Ketika Akta Menjadi Mesin Pencuci Uang',
                'excerpt' => 'Mengkaji bagaimana dokumen, badan usaha, dan keahlian profesional dapat disalahgunakan untuk memberi tampilan legal pada kekayaan yang berasal dari tindak pidana.',
                'url' => 'https://insight.kontan.co.id/news/ketika-akta-menjadi-mesin-pencuci-uang',
            ],
            [
                'category' => 'future-crime',
                'categoryLabel' => 'Future Financial Crime',
                'badgeClasses' => 'bg-emerald-100 text-emerald-900',
                'source' => 'detikNews · Kolom',
                'date' => '13 Mei 2026',
                'title' => 'Ketika Judi Online Bertemu Kripto: Ancaman Baru Pencucian Uang Digital',
                'excerpt' => 'Membahas pertemuan judi online, cryptocurrency, payment gateway, dan perpindahan dana lintas batas sebagai tantangan baru penelusuran aliran keuangan digital.',
                'url' => 'https://news.detik.com/kolom/d-8487238/ketika-judi-online-bertemu-kripto-ancaman-baru-pencucian-uang-digital',
            ],
        ];
    }
}
