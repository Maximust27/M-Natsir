<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Library & Referensi | M. Natsir Kongah')]
class Library extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'collection', except: 'all')]
    public string $collection = 'all';

    #[Url(as: 'type', except: 'all')]
    public string $resourceType = 'all';

    #[Url(as: 'institution', except: 'all')]
    public string $institution = 'all';

    #[Url(as: 'topic', except: 'all')]
    public string $topic = 'all';

    public function updatedCollection(string $value): void
    {
        if (! array_key_exists($value, $this->collections())) {
            $this->collection = 'all';
        }
    }

    public function updatedResourceType(string $value): void
    {
        if (! array_key_exists($value, $this->resourceTypes())) {
            $this->resourceType = 'all';
        }
    }

    public function updatedInstitution(string $value): void
    {
        if (! array_key_exists($value, $this->institutions())) {
            $this->institution = 'all';
        }
    }

    public function updatedTopic(string $value): void
    {
        if (! array_key_exists($value, $this->topics())) {
            $this->topic = 'all';
        }
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->collection = 'all';
        $this->resourceType = 'all';
        $this->institution = 'all';
        $this->topic = 'all';
    }

    public function render(): View
    {
        $resources = $this->resources();

        return view('livewire.library', [
            'collections' => $this->collections(),
            'resourceTypes' => $this->resourceTypes(),
            'institutions' => $this->institutions(),
            'topics' => $this->topics(),
            'resources' => $this->filteredResources(),
            'totalResources' => count($resources),
            'natsirWorksCount' => count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === 'natsir')),
            'institutionalCount' => count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === 'institutional')),
            'referenceCount' => count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === 'reference')),
        ]);
    }

    /**
     * @return array<string, array{label: string, count: int, classes: string}>
     */
    private function collections(): array
    {
        $resources = $this->resources();

        $definitions = [
            'all' => [
                'label' => 'Semua koleksi',
                'classes' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ],
            'natsir' => [
                'label' => 'Karya M. Natsir Kongah',
                'classes' => 'bg-amber-50 text-amber-900 hover:bg-amber-100',
            ],
            'institutional' => [
                'label' => 'Kontribusi Institusional',
                'classes' => 'bg-teal-50 text-teal-800 hover:bg-teal-100',
            ],
            'reference' => [
                'label' => 'Reference Sources',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
        ];

        foreach ($definitions as $key => &$definition) {
            $definition['count'] = $key === 'all'
                ? count($resources)
                : count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === $key));
        }

        unset($definition);

        return $definitions;
    }

    /**
     * @return array<string, string>
     */
    private function resourceTypes(): array
    {
        return [
            'all' => 'Semua jenis',
            'archived-writing' => 'Tulisan Arsip',
            'paper' => 'Makalah / Paper',
            'bibliographic-record' => 'Jejak Bibliografi',
            'institutional-report' => 'Laporan Institusional',
            'standard' => 'Standar',
            'typology' => 'Typology & Methods',
            'guidance' => 'Guidance',
            'risk-assessment' => 'Risk Assessment',
            'handbook' => 'Handbook',
            'legislation' => 'Regulasi',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function institutions(): array
    {
        return [
            'all' => 'Semua institusi / sumber',
            'academia' => 'Academia.edu',
            'bumn-watch' => 'BUMN Watch',
            'forkamas' => 'Forkamas Gathering',
            'universitas-riau' => 'Universitas Riau',
            'ppatk' => 'PPATK',
            'fatf' => 'FATF',
            'star' => 'StAR / World Bank',
            'jdih-bpk' => 'JDIH BPK',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function topics(): array
    {
        return [
            'all' => 'Semua topik',
            'pml' => 'Professional Money Laundering',
            'beneficial-ownership' => 'Beneficial Ownership',
            'digital-finance' => 'Digital Finance & Crypto',
            'risk-assessment' => 'Risk Assessment',
            'asset-recovery' => 'Asset Recovery',
            'aml-framework' => 'AML Framework',
            'banking' => 'Perbankan',
            'forestry' => 'Kejahatan Kehutanan',
            'campaign-finance' => 'Campaign Finance',
            'indonesia' => 'Indonesia',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filteredResources(): array
    {
        $search = mb_strtolower(trim($this->search));

        return array_values(array_filter($this->resources(), function (array $resource) use ($search): bool {
            if ($this->collection !== 'all' && $resource['collection'] !== $this->collection) {
                return false;
            }

            if ($this->resourceType !== 'all' && $resource['type'] !== $this->resourceType) {
                return false;
            }

            if ($this->institution !== 'all' && $resource['institution'] !== $this->institution) {
                return false;
            }

            if ($this->topic !== 'all' && ! in_array($this->topic, $resource['topics'], true)) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', array_filter([
                $resource['title'],
                $resource['summary'],
                $resource['institutionLabel'],
                $resource['typeLabel'],
                $resource['authorLabel'] ?? null,
                $resource['roleLabel'] ?? null,
                $resource['sourceNote'] ?? null,
                implode(' ', $resource['topicLabels']),
                (string) $resource['year'],
            ])));

            return str_contains($haystack, $search);
        }));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function resources(): array
    {
        return [
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'YAYASAN, SOEKARNO DAN PENCUCIAN UANG',
                'type' => 'archived-writing',
                'typeLabel' => 'Tulisan Arsip',
                'institution' => 'bumn-watch',
                'institutionLabel' => 'BUMN Watch / Academia.edu',
                'year' => 2006,
                'topics' => ['aml-framework', 'indonesia'],
                'topicLabels' => ['Pencucian Uang', 'Indonesia'],
                'authorLabel' => 'M. Natsir Kongah',
                'summary' => 'Tulisan M. Natsir Kongah mengenai klaim pendanaan yayasan, risiko asal-usul dana, dan kewaspadaan terhadap pencucian uang. Arsip Academia mencatat tulisan ini pernah dimuat di Majalah BUMN Watch edisi Januari 2006.',
                'url' => 'https://www.academia.edu/27149355/YAYASAN_SOEKARNO_DAN_PENCUCIAN_UANG',
                'language' => 'ID',
                'accessLabel' => 'Buka Arsip',
                'sourceNote' => 'Arsip penulis di Academia.edu; riwayat publikasi disebut di dalam naskah.',
            ],
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'Gubernur BoI dan Pencucian Uang',
                'type' => 'archived-writing',
                'typeLabel' => 'Tulisan Arsip',
                'institution' => 'academia',
                'institutionLabel' => 'Academia.edu',
                'year' => 2006,
                'topics' => ['aml-framework', 'banking'],
                'topicLabels' => ['Pencucian Uang', 'Perbankan'],
                'authorLabel' => 'Natsir Kongah',
                'summary' => 'Tulisan arsip tentang skandal Bank of Italy dan kaitannya dengan isu integritas perbankan serta pencucian uang. Profil Academia menempatkannya sebagai paper karya Natsir Kongah.',
                'url' => 'https://www.academia.edu/27149344/Gubernur_BoI_dan_Pencucian_Uang',
                'language' => 'ID',
                'accessLabel' => 'Buka Arsip',
                'sourceNote' => 'Tahun disusun dari konteks peristiwa dan arsip; tanggal publikasi primer tidak ditemukan.',
            ],
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'BANK DAN PENCUCIAN UANG',
                'type' => 'archived-writing',
                'typeLabel' => 'Tulisan Arsip',
                'institution' => 'academia',
                'institutionLabel' => 'Academia.edu',
                'year' => 2005,
                'topics' => ['banking', 'aml-framework'],
                'topicLabels' => ['Perbankan', 'Pencucian Uang'],
                'authorLabel' => 'Natsir Kongah',
                'summary' => 'Tulisan arsip mengenai kepercayaan publik terhadap perbankan, risiko reputasi, dan hubungan sistem perbankan dengan pencegahan pencucian uang.',
                'url' => 'https://independent.academia.edu/NatsirKongah',
                'language' => 'ID',
                'accessLabel' => 'Lihat Koleksi Penulis',
                'sourceNote' => 'Judul tercantum pada profil Academia.edu Natsir Kongah.',
            ],
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'Membongkar Mafia dengan UU Pencucian Uang',
                'type' => 'archived-writing',
                'typeLabel' => 'Tulisan Arsip',
                'institution' => 'academia',
                'institutionLabel' => 'Academia.edu',
                'year' => 2012,
                'topics' => ['aml-framework', 'pml', 'indonesia'],
                'topicLabels' => ['UU TPPU', 'Penegakan Hukum', 'Indonesia'],
                'authorLabel' => 'Natsir Kongah',
                'summary' => 'Tulisan arsip yang membahas pemanfaatan instrumen anti pencucian uang untuk membongkar jaringan kejahatan dan tindak lanjut informasi intelijen keuangan.',
                'url' => 'https://independent.academia.edu/NatsirKongah',
                'language' => 'ID',
                'accessLabel' => 'Lihat Koleksi Penulis',
                'sourceNote' => 'Judul dan cuplikan tersedia pada profil Academia.edu Natsir Kongah.',
            ],
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'Campaign funding Lesson from Watergate',
                'type' => 'archived-writing',
                'typeLabel' => 'Tulisan Arsip',
                'institution' => 'academia',
                'institutionLabel' => 'Academia.edu',
                'year' => 2009,
                'topics' => ['campaign-finance', 'aml-framework'],
                'topicLabels' => ['Campaign Finance', 'Financial Transparency'],
                'authorLabel' => 'Natsir Kongah',
                'summary' => 'Salinan arsip dari tulisan mengenai pelajaran Watergate bagi transparansi dan pengawasan pendanaan kampanye. Versi media terverifikasi juga tersedia di halaman Articles.',
                'url' => 'https://www.academia.edu/27149252/Campaign_funding_Lesson_from_Watergate_docx',
                'language' => 'EN / ID',
                'accessLabel' => 'Buka Arsip',
                'sourceNote' => 'Versi media diterbitkan The Jakarta Post pada 27 Juli 2009.',
            ],
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'Tulisan Natsir Kongah terkait dengan Tindak Pidana Pencucian Uang',
                'type' => 'archived-writing',
                'typeLabel' => 'Kumpulan Tulisan',
                'institution' => 'academia',
                'institutionLabel' => 'Academia.edu',
                'year' => 'Arsip',
                'topics' => ['aml-framework', 'indonesia'],
                'topicLabels' => ['TPPU', 'Kumpulan Tulisan'],
                'authorLabel' => 'Natsir Kongah',
                'summary' => 'Entri koleksi pada profil Academia.edu Natsir Kongah yang menghimpun materi terkait tindak pidana pencucian uang.',
                'url' => 'https://independent.academia.edu/NatsirKongah',
                'language' => 'ID',
                'accessLabel' => 'Lihat Koleksi Penulis',
                'sourceNote' => 'Tanggal publikasi spesifik tidak tersedia pada hasil arsip yang ditemukan.',
            ],
            [
                'collection' => 'natsir',
                'collectionLabel' => 'Karya M. Natsir',
                'title' => 'Menggunakan Undang Undang Anti Pencucian Uang untuk Mengatasi Kejahatan',
                'type' => 'bibliographic-record',
                'typeLabel' => 'Jejak Bibliografi',
                'institution' => 'universitas-riau',
                'institutionLabel' => 'Universitas Riau',
                'year' => 2004,
                'topics' => ['aml-framework', 'forestry', 'indonesia'],
                'topicLabels' => ['UU Anti Pencucian Uang', 'Kejahatan Kehutanan', 'Indonesia'],
                'authorLabel' => 'Garda T. Paripurna & Natsir Kongah',
                'summary' => 'Makalah diskusi bersama Garda T. Paripurna dan Natsir Kongah di Universitas Riau, Pekanbaru, 9 Juni 2004. Karya ini berulang kali dirujuk dalam literatur hukum terkait kejahatan kehutanan.',
                'url' => 'https://ejournal.unsrat.ac.id/v3/index.php/administratum/article/download/11321/10910/22593',
                'language' => 'ID',
                'accessLabel' => 'Lihat Jejak Sitasi',
                'sourceNote' => 'Tautan menuju artikel ilmiah yang mencantumkan referensi makalah tersebut.',
            ],
            [
                'collection' => 'institutional',
                'collectionLabel' => 'Kontribusi Institusional',
                'title' => 'Laporan Semester I PPATK 2020',
                'type' => 'institutional-report',
                'typeLabel' => 'Laporan Institusional',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2020,
                'topics' => ['aml-framework', 'indonesia'],
                'topicLabels' => ['PPATK', 'Laporan Institusi', 'Indonesia'],
                'roleLabel' => 'Senior Editor: M. Natsir Kongah',
                'summary' => 'Laporan resmi PPATK Semester I 2020. Kredit publikasi mencantumkan M. Natsir Kongah sebagai Senior Editor; item ini dipisahkan dari karya personal agar atribusinya tetap presisi.',
                'url' => 'https://ppid.ppatk.go.id/wp-content/uploads/2020/09/1-cetak-laporan-semester-2020-jadi-finis_compressed.pdf',
                'language' => 'ID',
                'accessLabel' => 'Buka Laporan Resmi',
                'sourceNote' => 'Kontribusi editorial institusional, bukan karya tunggal.',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'The FATF Recommendations',
                'type' => 'standard',
                'typeLabel' => 'International Standard',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2026,
                'topics' => ['aml-framework', 'risk-assessment'],
                'topicLabels' => ['AML Framework', 'Risk-Based Approach'],
                'summary' => 'Kerangka standar global FATF untuk pencegahan pencucian uang, pendanaan terorisme, dan pendanaan proliferasi, termasuk Recommendations dan Interpretive Notes.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Fatfrecommendations/Fatf-recommendations.html',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Professional Money Laundering',
                'type' => 'typology',
                'typeLabel' => 'Typology Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2018,
                'topics' => ['pml', 'beneficial-ownership'],
                'topicLabels' => ['Professional Money Laundering', 'Professional Enablers'],
                'summary' => 'Laporan typology FATF mengenai karakteristik, layanan, struktur jaringan, dan metode professional money launderers.',
                'url' => 'https://www.fatf-gafi.org/content/dam/fatf/documents/Professional-Money-Laundering.pdf',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Investigating Professional Money Laundering, Underground Banking, and the Use of Hawala and Other Similar Service Providers',
                'type' => 'typology',
                'typeLabel' => 'Typology Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2026,
                'topics' => ['pml', 'aml-framework'],
                'topicLabels' => ['Professional Money Laundering', 'Underground Banking'],
                'summary' => 'Laporan FATF tentang underground banking, hawala, penyedia layanan serupa, dan respons investigatif terhadap professional money laundering.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Methodsandtrends/pml-underground-banking-hawala-hossps.html',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Guidance on Beneficial Ownership of Legal Persons',
                'type' => 'guidance',
                'typeLabel' => 'Guidance',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2023,
                'topics' => ['beneficial-ownership', 'aml-framework'],
                'topicLabels' => ['Beneficial Ownership', 'Recommendation 24'],
                'summary' => 'Panduan implementasi Recommendation 24 untuk memastikan informasi pemilik manfaat badan hukum memadai, akurat, dan mutakhir.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Fatfrecommendations/Guidance-Beneficial-Ownership-Legal-Persons.html',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Concealment of Beneficial Ownership',
                'type' => 'typology',
                'typeLabel' => 'Typology Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF / Egmont Group',
                'year' => 2018,
                'topics' => ['beneficial-ownership', 'pml'],
                'topicLabels' => ['Beneficial Ownership', 'Professional Service Providers'],
                'summary' => 'Studi FATF–Egmont mengenai teknik penyamaran kepemilikan dan pengendalian aset, termasuk penyalahgunaan badan hukum dan nominee.',
                'url' => 'https://www.fatf-gafi.org/content/dam/fatf/documents/reports/FATF-Egmont-Concealment-beneficial-ownership.pdf',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Virtual Assets and VASPs: Targeted Update 2025',
                'type' => 'guidance',
                'typeLabel' => 'Targeted Update',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2025,
                'topics' => ['digital-finance', 'aml-framework'],
                'topicLabels' => ['Virtual Assets', 'VASPs', 'Recommendation 15'],
                'summary' => 'Pembaruan FATF mengenai implementasi AML/CFT untuk virtual assets dan virtual asset service providers.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Fatfrecommendations/targeted-update-virtual-assets-vasps-2025.html',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Regulatory Challenges from DeFi',
                'type' => 'guidance',
                'typeLabel' => 'Targeted Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2026,
                'topics' => ['digital-finance', 'aml-framework'],
                'topicLabels' => ['DeFi', 'Virtual Assets', 'Illicit Finance'],
                'summary' => 'Laporan FATF mengenai tantangan penerapan standar AML/CFT pada decentralized finance dan risiko illicit finance.',
                'url' => 'https://www.fatf-gafi.org/en/news/targeted-report-decentralised-finance-2026.html',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Money Laundering National Risk Assessment Guidance',
                'type' => 'guidance',
                'typeLabel' => 'Guidance',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2024,
                'topics' => ['risk-assessment', 'aml-framework'],
                'topicLabels' => ['National Risk Assessment', 'Risk-Based Approach'],
                'summary' => 'Panduan FATF untuk penyusunan National Risk Assessment pencucian uang, dari persiapan hingga komunikasi hasil.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Methodsandtrends/Money-Laundering-National-Risk-Assessment-Guidance.html',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Penilaian Risiko Indonesia Terhadap Tindak Pidana Pencucian Uang Tahun 2021',
                'type' => 'risk-assessment',
                'typeLabel' => 'National Risk Assessment',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2021,
                'topics' => ['risk-assessment', 'indonesia'],
                'topicLabels' => ['Indonesia', 'National Risk Assessment'],
                'summary' => 'Penilaian risiko nasional Indonesia untuk mengidentifikasi ancaman, kerentanan, dan risiko tindak pidana pencucian uang.',
                'url' => 'https://www.ppatk.go.id/dalam_negeri/read/1397/publikasi-penilaian-risiko.html',
                'language' => 'ID / EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Penilaian Risiko Sektoral Tindak Pidana Siber Tahun 2024',
                'type' => 'risk-assessment',
                'typeLabel' => 'Sectoral Risk Assessment',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2024,
                'topics' => ['risk-assessment', 'digital-finance', 'indonesia'],
                'topicLabels' => ['Cybercrime', 'Indonesia', 'Sectoral Risk'],
                'summary' => 'Kajian sektoral PPATK mengenai risiko TPPU dan TPPT yang terkait dengan tindak pidana siber.',
                'url' => 'https://www.ppatk.go.id/publikasi/read/239/penilaian-risiko-sektoral-tindak-pidana-pencucian-uang-dan-tindak-pidana-pendanaan-terorisme-pada-tindak-pidana-siber-tahun-2024.html',
                'language' => 'ID',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Penilaian Risiko Sektoral Teknologi Finansial Tahun 2023',
                'type' => 'risk-assessment',
                'typeLabel' => 'Sectoral Risk Assessment',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2023,
                'topics' => ['risk-assessment', 'digital-finance', 'indonesia'],
                'topicLabels' => ['Financial Technology', 'Indonesia', 'Sectoral Risk'],
                'summary' => 'Penilaian risiko sektoral PPATK terhadap teknologi finansial dan risiko terkait TPPU serta TPPT.',
                'url' => 'https://www.ppatk.go.id/publikasi/read/214/penilaian-risiko-sektoral-tindak-pidana-pencucian-uang-dan-pendanaan-terorisme-pada-teknologi-finansial-tahun-2023.html',
                'language' => 'ID',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'Asset Recovery Handbook: A Guide for Practitioners, Second Edition',
                'type' => 'handbook',
                'typeLabel' => 'Practitioner Handbook',
                'institution' => 'star',
                'institutionLabel' => 'StAR / World Bank',
                'year' => 2020,
                'topics' => ['asset-recovery', 'aml-framework'],
                'topicLabels' => ['Asset Recovery', 'International Cooperation'],
                'summary' => 'Handbook praktis mengenai strategi, investigasi, aspek hukum, dan kerja sama internasional dalam pemulihan aset.',
                'url' => 'https://star.worldbank.org/publications/asset-recovery-handbook-guide-practitioners-second-edition',
                'language' => 'EN',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'UU No. 8 Tahun 2010 tentang Pencegahan dan Pemberantasan Tindak Pidana Pencucian Uang',
                'type' => 'legislation',
                'typeLabel' => 'Regulasi Indonesia',
                'institution' => 'jdih-bpk',
                'institutionLabel' => 'JDIH BPK',
                'year' => 2010,
                'topics' => ['indonesia', 'aml-framework', 'asset-recovery'],
                'topicLabels' => ['Indonesia', 'TPPU', 'Legal Framework'],
                'summary' => 'Landasan hukum utama pencegahan dan pemberantasan tindak pidana pencucian uang di Indonesia.',
                'url' => 'https://peraturan.bpk.go.id/Details/38547/uu-no-8-tahun-2010',
                'language' => 'ID',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
            [
                'collection' => 'reference',
                'collectionLabel' => 'Reference Source',
                'title' => 'PP No. 43 Tahun 2015 tentang Pihak Pelapor dalam Pencegahan dan Pemberantasan TPPU',
                'type' => 'legislation',
                'typeLabel' => 'Regulasi Indonesia',
                'institution' => 'jdih-bpk',
                'institutionLabel' => 'JDIH BPK',
                'year' => 2015,
                'topics' => ['indonesia', 'aml-framework'],
                'topicLabels' => ['Indonesia', 'Reporting Parties', 'AML Framework'],
                'summary' => 'Peraturan Pemerintah mengenai pihak pelapor dalam rezim anti pencucian uang Indonesia; statusnya telah diubah melalui PP No. 61 Tahun 2021.',
                'url' => 'https://peraturan.bpk.go.id/Details/5611/pp-no-43-tahun-2015',
                'language' => 'ID',
                'accessLabel' => 'Buka Sumber Resmi',
            ],
        ];
    }
}
