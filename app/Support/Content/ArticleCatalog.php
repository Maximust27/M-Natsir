<?php

namespace App\Support\Content;

final class ArticleCatalog
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
            ],
            [
                'category' => 'pml',
                'categoryLabel' => '★ Professional Money Laundering',
                'badgeClasses' => 'bg-amber-100 text-amber-900',
                'source' => 'Kompas.id · Opini',
                'date' => 'Agustus 2026',
                'title' => 'Pencucian Uang Profesional',
                'excerpt' => 'Opini mengenai aktor berkeahlian hukum, akuntansi, perbankan, struktur perusahaan, dan jaringan lintas negara yang menyediakan kemampuan profesional untuk menyamarkan hasil kejahatan.',
                'url' => 'https://www.youtube.com/watch?v=atBJgiil77Y',
                'accessLabel' => 'Lihat Referensi Harian Kompas',
                'isPrimarySource' => false,
                'sourceNote' => 'Harian Kompas mempublikasikan video yang secara eksplisit merujuk opini M. Natsir Kongah berjudul ini di Kompas.id. URL artikel primer tidak terindeks terbuka dalam audit.',
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
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
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
            ],
            [
                'category' => 'aml',
                'categoryLabel' => 'AML Regs',
                'badgeClasses' => 'bg-indigo-100 text-indigo-900',
                'source' => 'Media Indonesia · Opini',
                'date' => '27 Maret 2012',
                'title' => 'Penegakan Hukum Pencucian Uang',
                'excerpt' => 'Tulisan M. Natsir Kongah yang tercatat dalam bibliografi akademik sebagai opini Media Indonesia mengenai penegakan hukum pencucian uang dan tindak lanjut informasi PPATK.',
                'url' => 'https://ejournal.fhuki.id/index.php/tora/article/view/139',
                'accessLabel' => 'Jejak bibliografi',
                'isPrimarySource' => false,
                'sourceNote' => 'Halaman asli Media Indonesia belum ditemukan. Tautan mengarah ke artikel ilmiah yang mencatat Natsir Kongah (2012), “Penegakan Hukum Pencucian Uang”, Media Indonesia.',
            ],
            [
                'category' => 'campaign-finance',
                'categoryLabel' => 'Campaign Finance',
                'badgeClasses' => 'bg-violet-100 text-violet-900',
                'source' => 'The Jakarta Post · Opinion',
                'date' => '27 Juli 2009',
                'title' => 'Campaign funding: Lesson from Watergate',
                'excerpt' => 'Kolom mengenai pelajaran Watergate bagi transparansi pendanaan kampanye, hubungan uang dan politik, serta kebutuhan pengawasan sumber dan penggunaan dana kampanye.',
                'url' => 'https://www.thejakartapost.com/news/2009/07/27/campaign-funding-lesson-watergate',
                'accessLabel' => 'Baca Publikasi Asli',
                'isPrimarySource' => true,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function featured(int $limit = 3): array
    {
        return array_slice(
            array_values(array_filter(
                self::all(),
                fn (array $article): bool => ($article['isPrimarySource'] ?? true) === true,
            )),
            0,
            $limit,
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findByTitle(string $title): ?array
    {
        foreach (self::all() as $article) {
            if ($article['title'] === $title) {
                return $article;
            }
        }

        return null;
    }
}
