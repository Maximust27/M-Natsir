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

    #[Url(as: 'type', except: 'all')]
    public string $resourceType = 'all';

    #[Url(as: 'institution', except: 'all')]
    public string $institution = 'all';

    #[Url(as: 'topic', except: 'all')]
    public string $topic = 'all';

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
        $this->resourceType = 'all';
        $this->institution = 'all';
        $this->topic = 'all';
    }

    public function render(): View
    {
        return view('livewire.library', [
            'resourceTypes' => $this->resourceTypes(),
            'institutions' => $this->institutions(),
            'topics' => $this->topics(),
            'resources' => $this->filteredResources(),
            'totalResources' => count($this->resources()),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function resourceTypes(): array
    {
        return [
            'all' => 'Semua jenis',
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
            'all' => 'Semua institusi',
            'fatf' => 'FATF',
            'ppatk' => 'PPATK',
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

            $haystack = mb_strtolower(implode(' ', [
                $resource['title'],
                $resource['summary'],
                $resource['institutionLabel'],
                $resource['typeLabel'],
                implode(' ', $resource['topicLabels']),
                (string) $resource['year'],
            ]));

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
                'title' => 'The FATF Recommendations',
                'type' => 'standard',
                'typeLabel' => 'International Standard',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2026,
                'topics' => ['aml-framework', 'risk-assessment'],
                'topicLabels' => ['AML Framework', 'Risk-Based Approach'],
                'summary' => 'Kerangka standar global FATF untuk pencegahan pencucian uang, pendanaan terorisme, dan pendanaan proliferasi. Halaman ini memuat Recommendations dan Interpretive Notes yang telah diperbarui hingga Juni 2026.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Fatfrecommendations/Fatf-recommendations.html',
                'language' => 'EN',
            ],
            [
                'title' => 'Professional Money Laundering',
                'type' => 'typology',
                'typeLabel' => 'Typology Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2018,
                'topics' => ['pml', 'beneficial-ownership'],
                'topicLabels' => ['Professional Money Laundering', 'Professional Enablers'],
                'summary' => 'Laporan typology FATF mengenai karakteristik, layanan, struktur jaringan, dan metode professional money launderers yang menyediakan jasa pencucian uang kepada pelaku kejahatan.',
                'url' => 'https://www.fatf-gafi.org/content/dam/fatf/documents/Professional-Money-Laundering.pdf',
                'language' => 'EN',
            ],
            [
                'title' => 'Investigating Professional Money Laundering, Underground Banking, and the Use of Hawala and Other Similar Service Providers',
                'type' => 'typology',
                'typeLabel' => 'Typology Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2026,
                'topics' => ['pml', 'aml-framework'],
                'topicLabels' => ['Professional Money Laundering', 'Underground Banking'],
                'summary' => 'Laporan FATF 2026 tentang bagaimana underground banking, hawala, dan penyedia layanan serupa dapat dimanfaatkan dalam professional money laundering serta respons investigatif otoritas.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Methodsandtrends/pml-underground-banking-hawala-hossps.html',
                'language' => 'EN',
            ],
            [
                'title' => 'Guidance on Beneficial Ownership of Legal Persons',
                'type' => 'guidance',
                'typeLabel' => 'Guidance',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2023,
                'topics' => ['beneficial-ownership', 'aml-framework'],
                'topicLabels' => ['Beneficial Ownership', 'Recommendation 24'],
                'summary' => 'Panduan implementasi Recommendation 24 untuk membantu negara memastikan informasi pemilik manfaat badan hukum tersedia secara memadai, akurat, dan mutakhir.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Fatfrecommendations/Guidance-Beneficial-Ownership-Legal-Persons.html',
                'language' => 'EN',
            ],
            [
                'title' => 'Concealment of Beneficial Ownership',
                'type' => 'typology',
                'typeLabel' => 'Typology Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF / Egmont Group',
                'year' => 2018,
                'topics' => ['beneficial-ownership', 'pml'],
                'topicLabels' => ['Beneficial Ownership', 'Professional Service Providers'],
                'summary' => 'Studi FATF–Egmont mengenai teknik penyamaran kepemilikan dan pengendalian aset, termasuk penyalahgunaan badan hukum, nominee, dan penyedia jasa profesional.',
                'url' => 'https://www.fatf-gafi.org/content/dam/fatf/documents/reports/FATF-Egmont-Concealment-beneficial-ownership.pdf',
                'language' => 'EN',
            ],
            [
                'title' => 'Virtual Assets and VASPs: Targeted Update 2025',
                'type' => 'guidance',
                'typeLabel' => 'Targeted Update',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2025,
                'topics' => ['digital-finance', 'aml-framework'],
                'topicLabels' => ['Virtual Assets', 'VASPs', 'Recommendation 15'],
                'summary' => 'Pembaruan global FATF mengenai implementasi AML/CFT untuk virtual assets dan virtual asset service providers, termasuk kemajuan regulasi, pengawasan, dan enforcement.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Fatfrecommendations/targeted-update-virtual-assets-vasps-2025.html',
                'language' => 'EN',
            ],
            [
                'title' => 'Regulatory Challenges from DeFi',
                'type' => 'guidance',
                'typeLabel' => 'Targeted Report',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2026,
                'topics' => ['digital-finance', 'aml-framework'],
                'topicLabels' => ['DeFi', 'Virtual Assets', 'Illicit Finance'],
                'summary' => 'Laporan FATF mengenai tantangan penerapan standar AML/CFT pada decentralized finance dan risiko pemanfaatannya oleh pelaku fraud, ransomware, dan jaringan pencucian uang.',
                'url' => 'https://www.fatf-gafi.org/en/news/targeted-report-decentralised-finance-2026.html',
                'language' => 'EN',
            ],
            [
                'title' => 'Money Laundering National Risk Assessment Guidance',
                'type' => 'guidance',
                'typeLabel' => 'Guidance',
                'institution' => 'fatf',
                'institutionLabel' => 'FATF',
                'year' => 2024,
                'topics' => ['risk-assessment', 'aml-framework'],
                'topicLabels' => ['National Risk Assessment', 'Risk-Based Approach'],
                'summary' => 'Panduan FATF untuk penyusunan National Risk Assessment pencucian uang, mulai dari persiapan, pengumpulan data, analisis ancaman dan kerentanan, hingga komunikasi hasil.',
                'url' => 'https://www.fatf-gafi.org/en/publications/Methodsandtrends/Money-Laundering-National-Risk-Assessment-Guidance.html',
                'language' => 'EN',
            ],
            [
                'title' => 'Penilaian Risiko Indonesia Terhadap Tindak Pidana Pencucian Uang Tahun 2021',
                'type' => 'risk-assessment',
                'typeLabel' => 'National Risk Assessment',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2021,
                'topics' => ['risk-assessment', 'indonesia'],
                'topicLabels' => ['Indonesia', 'National Risk Assessment'],
                'summary' => 'Penilaian risiko nasional Indonesia untuk mengidentifikasi ancaman, kerentanan, dan risiko tindak pidana pencucian uang sebagai dasar pendekatan berbasis risiko.',
                'url' => 'https://www.ppatk.go.id/dalam_negeri/read/1397/publikasi-penilaian-risiko.html',
                'language' => 'ID / EN',
            ],
            [
                'title' => 'Penilaian Risiko Sektoral Tindak Pidana Siber Tahun 2024',
                'type' => 'risk-assessment',
                'typeLabel' => 'Sectoral Risk Assessment',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2024,
                'topics' => ['risk-assessment', 'digital-finance', 'indonesia'],
                'topicLabels' => ['Cybercrime', 'Indonesia', 'Sectoral Risk'],
                'summary' => 'Kajian sektoral PPATK mengenai risiko TPPU dan TPPT yang terkait dengan tindak pidana siber, termasuk faktor ancaman, kerentanan, dan mitigasi risiko.',
                'url' => 'https://www.ppatk.go.id/publikasi/read/239/penilaian-risiko-sektoral-tindak-pidana-pencucian-uang-dan-tindak-pidana-pendanaan-terorisme-pada-tindak-pidana-siber-tahun-2024.html',
                'language' => 'ID',
            ],
            [
                'title' => 'Penilaian Risiko Sektoral Teknologi Finansial Tahun 2023',
                'type' => 'risk-assessment',
                'typeLabel' => 'Sectoral Risk Assessment',
                'institution' => 'ppatk',
                'institutionLabel' => 'PPATK',
                'year' => 2023,
                'topics' => ['risk-assessment', 'digital-finance', 'indonesia'],
                'topicLabels' => ['Financial Technology', 'Indonesia', 'Sectoral Risk'],
                'summary' => 'Penilaian risiko sektoral PPATK terhadap teknologi finansial dengan melihat ancaman, kerentanan, dampak, tipologi, wilayah, dan profil risiko terkait TPPU dan TPPT.',
                'url' => 'https://www.ppatk.go.id/publikasi/read/214/penilaian-risiko-sektoral-tindak-pidana-pencucian-uang-dan-pendanaan-terorisme-pada-teknologi-finansial-tahun-2023.html',
                'language' => 'ID',
            ],
            [
                'title' => 'Asset Recovery Handbook: A Guide for Practitioners, Second Edition',
                'type' => 'handbook',
                'typeLabel' => 'Practitioner Handbook',
                'institution' => 'star',
                'institutionLabel' => 'StAR / World Bank',
                'year' => 2020,
                'topics' => ['asset-recovery', 'aml-framework'],
                'topicLabels' => ['Asset Recovery', 'International Cooperation'],
                'summary' => 'Handbook praktis mengenai strategi, investigasi, aspek hukum, dan kerja sama internasional dalam penelusuran serta pemulihan aset hasil korupsi dan kejahatan.',
                'url' => 'https://star.worldbank.org/publications/asset-recovery-handbook-guide-practitioners-second-edition',
                'language' => 'EN',
            ],
            [
                'title' => 'UU No. 8 Tahun 2010 tentang Pencegahan dan Pemberantasan Tindak Pidana Pencucian Uang',
                'type' => 'legislation',
                'typeLabel' => 'Regulasi Indonesia',
                'institution' => 'jdih-bpk',
                'institutionLabel' => 'JDIH BPK',
                'year' => 2010,
                'topics' => ['indonesia', 'aml-framework', 'asset-recovery'],
                'topicLabels' => ['Indonesia', 'TPPU', 'Legal Framework'],
                'summary' => 'Landasan hukum utama pencegahan dan pemberantasan tindak pidana pencucian uang di Indonesia, termasuk pelaporan, analisis transaksi, penyidikan, dan penanganan harta kekayaan.',
                'url' => 'https://peraturan.bpk.go.id/Details/38547/uu-no-8-tahun-2010',
                'language' => 'ID',
            ],
            [
                'title' => 'PP No. 43 Tahun 2015 tentang Pihak Pelapor dalam Pencegahan dan Pemberantasan TPPU',
                'type' => 'legislation',
                'typeLabel' => 'Regulasi Indonesia',
                'institution' => 'jdih-bpk',
                'institutionLabel' => 'JDIH BPK',
                'year' => 2015,
                'topics' => ['indonesia', 'aml-framework'],
                'topicLabels' => ['Indonesia', 'Reporting Parties', 'AML Framework'],
                'summary' => 'Peraturan Pemerintah mengenai perluasan dan pengaturan pihak pelapor dalam rezim anti pencucian uang Indonesia; statusnya telah diubah melalui PP No. 61 Tahun 2021.',
                'url' => 'https://peraturan.bpk.go.id/Details/5611/pp-no-43-tahun-2015',
                'language' => 'ID',
            ],
        ];
    }
}
