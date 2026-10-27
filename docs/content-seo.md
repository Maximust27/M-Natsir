# SEO Konten & Internal Linking — Tahap 4

## Strategi: peta bacaan yang dapat diindeks

Tiga halaman topik ditambahkan berdasarkan tema dan sumber yang **sudah ada** pada `ArticleCatalog`:
- `/topik/anti-pencucian-uang` → kategori `aml`
- `/topik/pencucian-uang-profesional` → kategori `pml`
- `/topik/intelijen-keuangan` → kategori `financial-intelligence`

Halaman topik menjelaskan ruang lingkup dan pertanyaan yang relevan, menampilkan daftar publikasi dengan atribusi, serta menautkan ke Library dan topik lain. Teks pengantar merupakan kurasi/peta bacaan yang perlu ditinjau pemilik situs sebelum dipublikasikan sebagai tulisan yang mewakili pandangannya. Tidak menyalin isi lengkap karya media dan tidak mengklaim telah menulis atau menerbitkan artikel detail baru.

## Arsitektur

- `TopicCatalog` menyimpan slug, label, SEO title, meta description, pengantar, fokus bacaan, dan mapping ke kategori `ArticleCatalog` serta filter Library.
- `Topic` adalah Livewire full-page component menggunakan `->title()` dinamis dan menolak slug tak dikenal dengan 404.
- `resources/views/layouts/app.blade.php` mengambil description dari topik secara dinamis; canonical/OG URL/JSON-LD menggunakan URL bersih.
- `/sitemap.xml` memuat tiga URL topik self-canonical selain halaman publik dan pagination valid. Tidak memasukkan filter kategori.
- Homepage, halaman Articles, Library, footer, kartu artikel, dan setiap hub menyediakan tautan `<a href>` yang jelas ke halaman topik.
- Filter dan pencarian artikel tetap memakai Livewire. Halaman filter (`?category=`) tetap `noindex` sesuai kebijakan Tahap 2; topik hub menjadi landing page publik yang pantas diindeks.

## Kualitas dan kepercayaan

- Ringkasan dan daftar sumber selalu dipisahkan dari publikasi eksternal. Kartu menaut ke sumber media asli, dengan catatan khusus bila hanya tersedia sumber sekunder.
- Hindari mempresentasikan ringkasan AI atau materi kurasi sebagai tulisan asli M. Natsir Kongah tanpa tinjauan pemilik.
- Saat mengembangkan halaman artikel internal, pastikan konten benar-benar orisinal dan ada atribusi/tanggal yang valid sebelum menerapkan `Article` schema.
- Periksa perkembangan regulasi sebelum membuat pernyataan normatif atau mengklaim ketentuan terbaru; pengantar di sini tidak memberi nasihat hukum.
- Gunakan teks tautan yang jelas dan tautan HTML crawlable. Lihat [Google Search Central – Link Best Practices](https://developers.google.com/search/docs/crawling-indexing/links-crawlable) dan [People-First Content](https://developers.google.com/search/docs/fundamentals/creating-helpful-content).

## Testing di localhost

1. Checkout branch `feat/seo-topic-hubs`; jalankan `php artisan test` atau `php artisan test --filter=TopicHubsTest`.
2. Buka ketiga route `/topik/...` dan periksa link dari Home, Articles, footer dan Library.
3. Pastikan `/sitemap.xml` mencakup tiga URL baru, `/robots.txt` tetap `Disallow: /` di localhost.
4. Cek View Page Source: title, meta description, canonical, OG dan JSON-LD berbeda sesuai topik.
5. Uji navigasi `wire:navigate`, mobile menu, dan link asli publikasi. Jangan aktifkan `SEO_ALLOW_INDEXING` selama staging.

## Berikutnya

- Review editorial peta bacaan bersama pemilik situs, termasuk ketepatan istilah dan cakupan.
- Prioritaskan publikasi artikel internal **orisinal** yang benar-benar menjawab kebutuhan pembaca, baru tambahkan `Article` schema untuk detail halaman tersebut.
- Optimalkan gambar hero/typography dan audit performa (Tahap 5).
