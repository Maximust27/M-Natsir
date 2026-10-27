<?php

namespace App\Support\Content;

/**
 * Editorial reading paths, not reproductions of articles hosted by other outlets.
 */
final class TopicCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'anti-pencucian-uang' => [
                'slug' => 'anti-pencucian-uang',
                'category' => 'aml',
                'heading' => 'Anti Pencucian Uang',
                'seoTitle' => 'Anti Pencucian Uang: Panduan Kajian | M. Natsir Kongah',
                'description' => 'Peta bacaan anti pencucian uang: kewenangan transaksi, pengawasan berbasis risiko, dan pemulihan aset dari tulisan M. Natsir Kongah.',
                'teaser' => 'Membedakan kewenangan, pengawasan berbasis risiko, dan pembahasan pemulihan aset.',
                'libraryTopic' => 'aml-framework',
                'introduction' => [
                    'Anti pencucian uang tidak hanya menyangkut pelacakan perpindahan dana. Pembaca juga perlu memahami bagaimana risiko dikenali, bagaimana transaksi ditangani, serta apa batas kewenangan dan perlindungan hukum yang relevan dalam setiap proses. Istilah yang terdengar serupa bisa merujuk pada tindakan dengan dasar dan akibat berbeda.',
                    'Peta bacaan ini menghubungkan tulisan M. Natsir Kongah mengenai penundaan transaksi, pengawasan berbasis risiko, dan pemulihan aset. Tujuannya membantu pembaca membedakan pertanyaan kebijakan, pengawasan, dan penegakan hukum sebelum masuk ke publikasi aslinya. Untuk ketentuan yang berlaku, selalu periksa naskah regulasi resmi dan tanggal berlakunya.',
                ],
                'readingFocus' => [
                    [
                        'title' => 'Kewenangan atas transaksi',
                        'description' => 'Apa perbedaan penundaan transaksi, penghentian sementara, dan pemblokiran? Penjelasan harus menimbang pihak yang berwenang, dasar tindakan, serta hak pihak yang terdampak.',
                    ],
                    [
                        'title' => 'Pengawasan berbasis risiko',
                        'description' => 'Sektor dan profesi tidak memiliki tingkat kerentanan yang seragam. Kajian pengawasan memerlukan pemetaan risiko, titik masuk, dan kemampuan kontrol yang relevan bagi masing-masing kegiatan.',
                    ],
                    [
                        'title' => 'Pemulihan aset dan kepastian hukum',
                        'description' => 'Upaya menelusuri dan memulihkan hasil kejahatan perlu dibaca berdampingan dengan prosedur, pengawasan pengadilan, dan perlindungan bagi pihak ketiga yang beritikad baik.',
                    ],
                ],
            ],
            'pencucian-uang-profesional' => [
                'slug' => 'pencucian-uang-profesional',
                'category' => 'pml',
                'heading' => 'Pencucian Uang Profesional',
                'seoTitle' => 'Pencucian Uang Profesional | M. Natsir Kongah',
                'description' => 'Pahami isu professional money laundering, gatekeepers, pengendalian aset, dan penyamaran hasil kejahatan melalui peta publikasi pilihan.',
                'teaser' => 'Menelaah peran keahlian profesional, perantara, dan kendali atas hasil kejahatan.',
                'libraryTopic' => 'pml',
                'introduction' => [
                    'Professional money laundering mengarahkan perhatian pada kemungkinan penyalahgunaan keahlian, layanan, dan jaringan untuk memisahkan hasil tindak pidana dari asal-usul atau pemilik manfaatnya. Fokusnya bukan hanya rekening yang dilewati dana, melainkan pihak yang mengatur, mendokumentasikan, memindahkan, atau akhirnya mengendalikan nilai tersebut.',
                    'Penyebutan profesi tertentu tidak berarti semua pelakunya terlibat kejahatan. Pertanyaan penting justru terletak pada bukti penyalahgunaan jasa, hubungan antarpihak, dan siapa yang memiliki kendali. Kumpulan publikasi berikut mempertemukan pembahasan scam laundering, penggunaan keahlian investasi, serta catatan tentang pencucian uang profesional dari sumber media yang dapat ditelusuri.',
                ],
                'readingFocus' => [
                    [
                        'title' => 'Peran perantara dan gatekeepers',
                        'description' => 'Analisis perlu membedakan layanan profesional yang sah dari dugaan penyalahgunaan keahlian hukum, keuangan, akuntansi, atau struktur korporasi untuk menyamarkan asal-usul nilai.',
                    ],
                    [
                        'title' => 'Dari penampung ke pengendali',
                        'description' => 'Rekening yang menerima uang tidak selalu menjawab siapa pengarah dan penerima manfaat akhirnya. Pertanyaan tentang alur kendali dapat memberi konteks lebih jauh daripada perpindahan antar-rekening semata.',
                    ],
                    [
                        'title' => 'Menilai pola dengan hati-hati',
                        'description' => 'Kerumitan transaksi atau keberadaan banyak pihak bukan bukti tunggal tindak pidana. Telaah yang bertanggung jawab perlu memisahkan indikasi, dugaan, dan fakta yang telah diverifikasi.',
                    ],
                ],
            ],
            'intelijen-keuangan' => [
                'slug' => 'intelijen-keuangan',
                'category' => 'financial-intelligence',
                'heading' => 'Intelijen Keuangan',
                'seoTitle' => 'Intelijen Keuangan & Follow the Value | M. Natsir Kongah',
                'description' => 'Peta bacaan intelijen keuangan, follow the money, follow the value, beneficial ownership, dan penelusuran perpindahan nilai.',
                'teaser' => 'Membaca aliran transaksi, perubahan bentuk aset, dan pihak yang mengendalikan nilai.',
                'libraryTopic' => 'beneficial-ownership',
                'introduction' => [
                    'Intelijen keuangan membantu menyusun informasi tentang transaksi, hubungan pihak, dan pola perpindahan nilai agar risiko dan dugaan penyalahgunaan sistem keuangan dapat ditelaah lebih terarah. Informasi dan analisis bukan pengganti proses pembuktian hukum; keduanya memberi konteks awal untuk pertanyaan yang perlu didalami.',
                    'Ungkapan follow the money menekankan pentingnya menelusuri jejak dana. Dalam tulisan-tulisan M. Natsir Kongah, gagasan follow the value mengajak pembaca juga memperhatikan perubahan bentuk nilai: dana dapat beralih menjadi aset atau penguasaan ekonomi yang tidak selalu tampak sebagai saldo rekening. Kajian beneficial ownership melengkapi pertanyaan itu dengan melihat siapa yang benar-benar mengendalikan atau menikmati suatu struktur.',
                ],
                'readingFocus' => [
                    [
                        'title' => 'Follow the money dan follow the value',
                        'description' => 'Menelusuri rekening penting, tetapi tidak selalu cukup ketika nilai berubah menjadi properti, emas, badan usaha, atau aset lain. Jejak penguasaan harus dipertimbangkan bersama jejak transaksi.',
                    ],
                    [
                        'title' => 'Pemilik manfaat sebenarnya',
                        'description' => 'Dokumen kepemilikan formal dan kendali ekonomis dapat melibatkan pihak berbeda. Karena itu, pembahasan beneficial ownership perlu memeriksa hubungan kendali, bukan hanya nama pada dokumen.',
                    ],
                    [
                        'title' => 'Membaca informasi secara proporsional',
                        'description' => 'Pola yang tidak biasa dapat menjadi bahan analisis, tetapi tidak otomatis membuktikan pelanggaran. Validasi sumber, konteks transaksi, dan batas kesimpulan tetap penting.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function forCategory(string $category): ?array
    {
        foreach (self::all() as $topic) {
            if ($topic['category'] === $category) {
                return $topic;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function articlesFor(string $slug): array
    {
        $topic = self::find($slug);

        if ($topic === null) {
            return [];
        }

        return array_values(array_filter(
            ArticleCatalog::all(),
            static fn (array $article): bool => $article['category'] === $topic['category'],
        ));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function relatedTo(string $slug): array
    {
        return array_values(array_filter(
            self::all(),
            static fn (array $topic): bool => $topic['slug'] !== $slug,
        ));
    }
}
