<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Media & Appearances | M. Natsir Kongah')]
class Media extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'category', except: 'all')]
    public string $activeCategory = 'all';

    public function selectCategory(string $category): void
    {
        if (! array_key_exists($category, $this->categories())) {
            return;
        }

        $this->activeCategory = $category;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->activeCategory = 'all';
    }

    public function render(): View
    {
        $allAppearances = $this->appearances();
        $appearances = $this->filteredAppearances();
        $featuredAppearance = $allAppearances[0];

        if (trim($this->search) === '' && $this->activeCategory === 'all') {
            $appearances = array_values(array_filter(
                $appearances,
                fn (array $appearance): bool => $appearance['title'] !== $featuredAppearance['title'],
            ));
        }

        return view('livewire.media', [
            'categories' => $this->categories(),
            'appearances' => $appearances,
            'featuredAppearance' => $featuredAppearance,
            'resultCount' => count($appearances),
            'totalAppearances' => count($allAppearances),
            'interviewCount' => count(array_filter(
                $allAppearances,
                fn (array $appearance): bool => $appearance['category'] === 'interviews',
            )),
            'publicEventCount' => count(array_filter(
                $allAppearances,
                fn (array $appearance): bool => in_array($appearance['category'], ['talks', 'media-visits', 'recognition'], true),
            )),
            'yearSpan' => '2010–2025',
        ]);
    }

    /**
     * @return array<string, array{label: string, classes: string, count: int}>
     */
    private function categories(): array
    {
        $appearances = $this->appearances();

        $definitions = [
            'all' => [
                'label' => 'Semua',
                'classes' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ],
            'interviews' => [
                'label' => 'Interviews & Broadcast',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
            'talks' => [
                'label' => 'Talks & Panels',
                'classes' => 'bg-teal-50 text-teal-800 hover:bg-teal-100',
            ],
            'media-visits' => [
                'label' => 'Media Visits',
                'classes' => 'bg-amber-50 text-amber-900 hover:bg-amber-100',
            ],
            'recognition' => [
                'label' => 'Recognition',
                'classes' => 'bg-rose-50 text-rose-800 hover:bg-rose-100',
            ],
        ];

        foreach ($definitions as $key => &$definition) {
            $definition['count'] = $key === 'all'
                ? count($appearances)
                : count(array_filter(
                    $appearances,
                    fn (array $appearance): bool => $appearance['category'] === $key,
                ));
        }

        unset($definition);

        return $definitions;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filteredAppearances(): array
    {
        $appearances = $this->appearances();
        $category = array_key_exists($this->activeCategory, $this->categories())
            ? $this->activeCategory
            : 'all';

        if ($category !== 'all') {
            $appearances = array_values(array_filter(
                $appearances,
                fn (array $appearance): bool => $appearance['category'] === $category,
            ));
        }

        $search = mb_strtolower(trim($this->search));

        if ($search === '') {
            return $appearances;
        }

        return array_values(array_filter($appearances, function (array $appearance) use ($search): bool {
            $haystack = mb_strtolower(implode(' ', array_filter([
                $appearance['title'],
                $appearance['outlet'],
                $appearance['role'],
                $appearance['summary'],
                $appearance['date'],
                (string) $appearance['year'],
                implode(' ', $appearance['topics']),
                $appearance['sourceNote'] ?? null,
                $appearance['actionLabel'],
            ])));

            return str_contains($haystack, $search);
        }));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function appearances(): array
    {
        return [
            [
                'category' => 'media-visits',
                'categoryLabel' => 'Media Visit',
                'badgeVariant' => 'amber',
                'date' => '30 September 2025',
                'year' => 2025,
                'outlet' => 'PPATK · Pikiran Rakyat',
                'role' => 'Ketua Kelompok Hubungan Masyarakat PPATK · peserta media visit',
                'title' => 'Sinergi PPATK dan Pikiran Rakyat: Membangun Kesadaran Publik Akan Bahayanya Pencucian Uang',
                'summary' => 'M. Natsir Kongah tercatat mengikuti kunjungan media PPATK ke kantor pusat Pikiran Rakyat di Bandung untuk memperkuat hubungan dengan pers serta edukasi publik mengenai pencucian uang.',
                'topics' => ['Pencucian Uang', 'Media Relations', 'Public Education'],
                'url' => 'https://www.ppatk.go.id/news/read/1534/sinergi-ppatk-dan-pikiran-rakyat-membangun-kesadaran-publik-akan-bahayanya-pencucian-uang.html',
                'actionLabel' => 'View Event',
                'thumbnail' => null,
                'sourceNote' => 'Sumber primer: berita resmi PPATK yang menyebut M. Natsir Kongah sebagai peserta media visit pada 30 September 2025.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '10 Juli 2025',
                'year' => 2025,
                'outlet' => 'Metro TV · Medcom',
                'role' => 'Ketua Tim Humas PPATK · narasumber',
                'title' => 'PPATK Ungkap 571 Ribu Penerima Bansos Aktif Main Judi Online, Kemensos Siap Evaluasi',
                'summary' => 'Dalam tayangan Selamat Pagi Indonesia, M. Natsir Kongah menjelaskan hasil pencocokan data PPATK terkait penerima bantuan sosial yang teridentifikasi melakukan transaksi judi online.',
                'topics' => ['Judi Online', 'Financial Intelligence', 'Data Matching'],
                'url' => 'https://www.metrotvnews.com/play/N4EC4731-ppatk-ungkap-571-ribu-penerima-bansos-aktif-main-judi-online-kemensos-siap-evaluasi',
                'actionLabel' => 'Watch',
                'thumbnail' => 'https://www.ppatk.go.id//backend/assets/images/berita_utama/1759453498_1534.jpeg',
                'sourceNote' => 'Liputan Metro TV/Medcom mengidentifikasi M. Natsir Kongah sebagai Ketua Tim Humas PPATK dan sumber utama dalam tayangan.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '17 April 2025',
                'year' => 2025,
                'outlet' => 'Pro 3 RRI',
                'role' => 'Humas PPATK · narasumber wawancara',
                'title' => 'Gunakan QRIS, PPATK Bongkar Modus Baru Judi Online',
                'summary' => 'Wawancara Pro 3 RRI mengenai penyalahgunaan QRIS pada merchant berprofil tidak wajar sebagai salah satu pola penghimpunan dana untuk aktivitas judi online.',
                'topics' => ['Judi Online', 'QRIS', 'Digital Payments'],
                'url' => 'https://rri.co.id/index.php/kriminalitas/1455997/gunakan-qris-ppatk-bongkar-modus-baru-judi-online',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => null,
                'sourceNote' => 'RRI mencatat pernyataan tersebut disampaikan M. Natsir Kongah dalam wawancara bersama Pro 3 RRI.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '9 Februari 2025',
                'year' => 2025,
                'outlet' => 'Pro 3 RRI',
                'role' => 'Koordinator Kelompok Hubungan Masyarakat PPATK · narasumber',
                'title' => 'PPATK: Hasil Judol Melalui Kripto Capai Rp28 Triliun',
                'summary' => 'Perbincangan Pro 3 RRI mengenai aliran dana judi online melalui aset kripto dan penggunaan teknik layering dalam penyamaran sumber dana.',
                'topics' => ['Judi Online', 'Crypto', 'Money Laundering'],
                'url' => 'https://rri.co.id/hukum/1312532/ppatk-hasil-judol-melalui-kripto-capai-rp28-triliun',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => 'https://rricoid-assets.obs.ap-southeast-4.myhuaweicloud.com/berita/Pusat_Pemberitaan/o/1739065236541-natsir/id33lq6ssmo2rkr.jpeg',
                'sourceNote' => 'RRI menyebut M. Natsir Kongah menyampaikan penjelasan tersebut dalam perbincangan Pro 3 RRI pada 9 Februari 2025.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '20 Januari 2025',
                'year' => 2025,
                'outlet' => 'Pro 3 RRI',
                'role' => 'Koordinator Kelompok Hubungan Masyarakat PPATK · narasumber',
                'title' => 'PPATK Temukan Penyelewengan Dana Desa Untuk Judi Online',
                'summary' => 'Dalam perbincangan Pro 3 RRI, M. Natsir Kongah menjelaskan temuan PPATK mengenai transaksi dana desa yang digunakan untuk aktivitas judi online.',
                'topics' => ['Judi Online', 'Dana Desa', 'Financial Intelligence'],
                'url' => 'https://rri.co.id/hukum/anti-korupsi/1266305/ppatk-temukan-penyelewengan-dana-desa-untuk-judi-online',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => 'https://rricoid-assets.obs.ap-southeast-4.myhuaweicloud.com/berita/Pusat_Pemberitaan/o/1737344507040-natsir/4w3o4szadcmcvnz.jpeg',
                'sourceNote' => 'RRI mengidentifikasi M. Natsir Kongah sebagai narasumber dalam perbincangan Pro 3 RRI pada 20 Januari 2025.',
            ],
            [
                'category' => 'recognition',
                'categoryLabel' => 'Recognition',
                'badgeVariant' => 'neutral',
                'date' => '17 Desember 2024',
                'year' => 2024,
                'outlet' => 'PPID PPATK · Komisi Informasi Pusat',
                'role' => 'Ketua Tim Kehumasan PPATK · penerima penghargaan mewakili PPATK',
                'title' => 'PPATK Teguhkan Komitmen Sebagai Badan Publik Informatif',
                'summary' => 'M. Natsir Kongah hadir menerima predikat Informatif untuk PPATK dalam Monitoring dan Evaluasi Keterbukaan Informasi Publik tahun 2024.',
                'topics' => ['Keterbukaan Informasi', 'Public Service', 'Recognition'],
                'url' => 'https://ppid.ppatk.go.id/?p=15022',
                'actionLabel' => 'View Event',
                'thumbnail' => 'https://ppid.ppatk.go.id/wp-content/uploads/2024/12/WhatsApp-Image-2024-12-27-at-14.23.36-scaled-e1735284758952.jpeg',
                'sourceNote' => 'Portal PPID PPATK mencatat penganugerahan berlangsung 17 Desember 2024 dan diterima oleh M. Natsir Kongah.',
            ],
            [
                'category' => 'talks',
                'categoryLabel' => 'Talk & Panel',
                'badgeVariant' => 'teal',
                'date' => '15 Juni 2024',
                'year' => 2024,
                'outlet' => 'Diskusi Daring · diliput detikFinance',
                'role' => 'Koordinator Kelompok Substansi Humas PPATK · narasumber diskusi',
                'title' => 'Diskusi Daring: Mati Melarat Karena Judi',
                'summary' => 'Dalam diskusi daring ini, M. Natsir Kongah membahas perkembangan transaksi judi online, pemblokiran rekening, serta modus yang digunakan pelaku untuk mempertahankan akses transaksi.',
                'topics' => ['Judi Online', 'Public Discussion', 'Financial Intelligence'],
                'url' => 'https://finance.detik.com/berita-ekonomi-bisnis/d-7394555/fakta-fakta-yang-jarang-orang-tahu-soal-judi-online-di-ri',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => 'https://cdn.antaranews.com/cache/1200x800/2024/06/15/natsir-kongah.jpeg',
                'sourceNote' => 'detikFinance mengutip paparan M. Natsir Kongah dari diskusi daring “Mati Melarat Karena Judi” pada 15 Juni 2024.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '13 Juni 2024',
                'year' => 2024,
                'outlet' => 'Pro 3 RRI',
                'role' => 'Humas PPATK · narasumber wawancara',
                'title' => 'PPATK: Mayoritas Pelaku Judi Online Berpenghasilan Rendah',
                'summary' => 'Wawancara RRI mengenai profil pemain judi online, nominal transaksi, dan keterkaitannya dengan pinjaman online ilegal.',
                'topics' => ['Judi Online', 'Pinjaman Online', 'Public Awareness'],
                'url' => 'https://rri.co.id/nasional/755379/faq.html',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => 'https://rricoid-assets.obs.ap-southeast-4.myhuaweicloud.com/berita/Pusat_Pemberitaan/o/1718278388869-natsir_kongah_PPATK/lie2qjmppm8tl7r.jpeg',
                'sourceNote' => 'RRI secara eksplisit menyebut tulisan ini sebagai wawancara bersama M. Natsir Kongah.',
            ],
            [
                'category' => 'recognition',
                'categoryLabel' => 'Recognition',
                'badgeVariant' => 'neutral',
                'date' => '17 November 2023',
                'year' => 2023,
                'outlet' => 'PPATK · Gatra Awards',
                'role' => 'Koordinator Kelompok Hubungan Masyarakat PPATK · penerima penghargaan mewakili PPATK',
                'title' => 'PPATK Sabet Penghargaan Gatra Awards 2023',
                'summary' => 'M. Natsir Kongah menerima Gatra Awards 2023 kategori hukum atas nama PPATK dan menyampaikan pernyataan saat penerimaan penghargaan.',
                'topics' => ['Recognition', 'PPATK', 'Public Appearance'],
                'url' => 'https://www.ppatk.go.id/news/read/1314/trees',
                'actionLabel' => 'View Event',
                'thumbnail' => 'https://www.ppatk.go.id//backend/assets/images/berita_utama/1700468616_1314.JPG',
                'sourceNote' => 'Berita resmi PPATK menyebut penghargaan diterima oleh M. Natsir Kongah pada 17 November 2023.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '9 Maret 2023',
                'year' => 2023,
                'outlet' => 'AKIS · tvOne',
                'role' => 'Juru Bicara PPATK · narasumber',
                'title' => 'Laporan dari Perbankan Sangat Membantu PPATK Lacak Transaksi Mencurigakan',
                'summary' => 'Dalam program AKIS tvOne, M. Natsir Kongah menjelaskan bagaimana indikator transaksi mencurigakan dan laporan perbankan membantu proses analisis transaksi keuangan.',
                'topics' => ['Financial Intelligence', 'Suspicious Transactions', 'Banking'],
                'url' => 'https://www.youtube.com/watch?v=4fzhn91AS9Q',
                'actionLabel' => 'Watch',
                'thumbnail' => 'https://i.ytimg.com/vi/4fzhn91AS9Q/hqdefault.jpg',
                'sourceNote' => 'Video resmi tvOneNews mengidentifikasi M. Natsir Kongah sebagai Juru Bicara PPATK.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '8 Maret 2023',
                'year' => 2023,
                'outlet' => 'Squawk Box · CNBC Indonesia',
                'role' => 'Narasumber PPATK',
                'title' => 'Dialog Squawk Box: Professional Money Laundering dan Aliran Transaksi',
                'summary' => 'Dialog CNBC Indonesia mengenai penelusuran aliran transaksi dan indikasi penggunaan professional money laundering dalam penyamaran hasil tindak pidana.',
                'topics' => ['Professional Money Laundering', 'Financial Intelligence', 'Suspicious Transactions'],
                'url' => 'https://www.youtube.com/watch?v=BdxYgioGFxI',
                'actionLabel' => 'Watch',
                'thumbnail' => 'https://i.ytimg.com/vi/BdxYgioGFxI/hqdefault.jpg',
                'sourceNote' => 'Video resmi CNBC Indonesia menampilkan M. Natsir Kongah sebagai narasumber PPATK dalam Squawk Box.',
            ],
            [
                'category' => 'talks',
                'categoryLabel' => 'Talk & Panel',
                'badgeVariant' => 'teal',
                'date' => '30 Juni 2022',
                'year' => 2022,
                'outlet' => 'PPID PPATK · KPK',
                'role' => 'Koordinator Bidang Pengaduan dan Penyelesaian Sengketa PPID PPATK · pembicara',
                'title' => 'Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK',
                'summary' => 'Sharing session di Gedung Merah Putih KPK mengenai layanan informasi publik, pengelolaan informasi yang dikecualikan, dan kewajiban badan publik.',
                'topics' => ['Keterbukaan Informasi', 'Public Service', 'Knowledge Sharing'],
                'url' => 'https://ppid.ppatk.go.id/?p=6548',
                'actionLabel' => 'View Event',
                'thumbnail' => 'https://ppid.ppatk.go.id/wp-content/uploads/2022/09/WhatsApp-Image-2022-09-14-at-09.39.12-e1663123759938.jpeg',
                'sourceNote' => 'Portal PPID PPATK mencatat M. Natsir Kongah melakukan sharing session bersama tim PIP KPK.',
            ],
            [
                'category' => 'media-visits',
                'categoryLabel' => 'Media Visit',
                'badgeVariant' => 'amber',
                'date' => '18 Februari 2020',
                'year' => 2020,
                'outlet' => 'PPATK · The Jakarta Post',
                'role' => 'Ketua Kelompok Humas PPATK · peserta media visit',
                'title' => 'Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post',
                'summary' => 'M. Natsir Kongah tercatat bersama pimpinan PPATK dalam kunjungan media ke The Jakarta Post untuk berbagi informasi mengenai rezim APUPPT dan agenda Mutual Evaluation Review.',
                'topics' => ['Media Relations', 'APUPPT', 'Public Education'],
                'url' => 'https://www.ppatk.go.id/news/read/1032/rangkul-pers-pimpinan-ppatk-sambang-the-jakarta-post.html',
                'actionLabel' => 'View Event',
                'thumbnail' => 'https://www.ppatk.go.id//backend/assets/images/berita_utama/1582256211_1032.png',
                'sourceNote' => 'Berita resmi PPATK menyebut M. Natsir Kongah sebagai Ketua Kelompok Humas PPATK yang mengikuti kunjungan pada 18 Februari 2020.',
            ],
            [
                'category' => 'talks',
                'categoryLabel' => 'Talk & Panel',
                'badgeVariant' => 'teal',
                'date' => '19 Desember 2019',
                'year' => 2019,
                'outlet' => 'PT Semen Padang · ANTARA Sumbar',
                'role' => 'Ketua Kelompok Kehumasan PPATK · pemateri seminar',
                'title' => 'Peringati Hari Anti Korupsi Sedunia, Semen Padang Gelar Seminar',
                'summary' => 'Dalam seminar bertema “Maju Lawan Korupsi”, M. Natsir Kongah menyampaikan materi mengenai hubungan kejahatan asal, hasil kejahatan, dan pencucian uang.',
                'topics' => ['Anti-Corruption', 'Money Laundering', 'Seminar'],
                'url' => 'https://sumbar.antaranews.com/berita/314376/peringati-hari-anti-korupsi-sedunia-semen-padang-gelar-seminar',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => 'https://img.antaranews.com/cache/1200x800/2019/12/20/WhatsApp-Image-2019-12-20-at-06.04.35.jpeg.webp',
                'sourceNote' => 'ANTARA Sumbar secara eksplisit menyebut M. Natsir Kongah menyampaikan materi dalam seminar.',
            ],
            [
                'category' => 'talks',
                'categoryLabel' => 'Talk & Panel',
                'badgeVariant' => 'teal',
                'date' => '28 Oktober 2019',
                'year' => 2019,
                'outlet' => 'PPATK · Festival Kreatif Generasi Anti Pencucian Uang',
                'role' => 'Ketua Kelompok Humas PPATK · penyampai laporan kegiatan',
                'title' => 'Festival Kreatif Generasi Anti Pencucian Uang 2019',
                'summary' => 'Pada acara penganugerahan Festival Kreatif Generasi Anti Pencucian Uang, M. Natsir Kongah menyampaikan laporan kegiatan dan tujuan pelibatan generasi muda serta industri kreatif dalam edukasi publik.',
                'topics' => ['Public Education', 'Anti-Money Laundering', 'Creative Outreach'],
                'url' => 'https://www.ppatk.go.id/siaran_pers/read/998/tingkatkan-kesadaran-anti-pencucian-uang-pada-milenial-dengan-kreatifitas-.html',
                'actionLabel' => 'View Event',
                'thumbnail' => 'https://www.ppatk.go.id//backend/assets/images/berita_utama/1572313967_998.JPG',
                'sourceNote' => 'Siaran pers PPATK mencatat laporan kegiatan disampaikan oleh M. Natsir Kongah sebagai Ketua Kelompok Humas.',
            ],
            [
                'category' => 'interviews',
                'categoryLabel' => 'Interview & Broadcast',
                'badgeVariant' => 'indigo',
                'date' => '6 Oktober 2010',
                'year' => 2010,
                'outlet' => 'The Jakarta Post',
                'role' => 'Juru Bicara PPATK · sumber media',
                'title' => 'New Law to Empower KPK, PPATK in Graft Fight',
                'summary' => 'The Jakarta Post mengutip M. Natsir Kongah mengenai implementasi perubahan kerangka hukum anti pencucian uang dan peningkatan efektivitas kerja PPATK.',
                'topics' => ['AML Framework', 'Financial Intelligence', 'Media Interview'],
                'url' => 'https://www.thejakartapost.com/news/2010/10/06/new-law-empower-kpk-ppatk-graft-fight.html',
                'actionLabel' => 'Read Coverage',
                'thumbnail' => null,
                'sourceNote' => 'The Jakarta Post mengidentifikasi Natsir Kongah sebagai spokesperson PPATK dan mengutip keterangannya secara langsung.',
            ],
        ];
    }
}
