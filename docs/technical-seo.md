# Technical SEO dan crawling

Tahap ini melanjutkan fondasi metadata pada PR #6.

## Perilaku URL

- Semua halaman publik memiliki URL canonical tanpa parameter pelacakan.
- Halaman arsip `/articles?page=N` yang valid dan tidak difilter memakai canonical sendiri, termasuk halaman kedua dan berikutnya.
- Pencarian dan filter pada Articles, Library, dan Media tetap dapat dibagikan tetapi diberi `noindex, follow` saat indexing production aktif; canonical mengarah ke landing page utama.
- Pagination artikel memakai tautan HTML `href` yang tetap bekerja saat JavaScript dimatikan, serta `wire:navigate` untuk transisi Livewire.
- URL pagination mempertahankan parameter pencarian dan kategori yang aktif.

## Sitemap

Endpoint `/sitemap.xml` otomatis mencantumkan enam halaman publik dan halaman selanjutnya pada arsip artikel sesuai banyaknya konten dan ukuran pagination. Endpoint unduhan CV, URL pencarian, dan filter tidak ikut sitemap.

## Robots dan lingkungan

`public/robots.txt` yang statis dihapus supaya route dinamis `/robots.txt` tidak tertimpa web server.

**Default aman:** `SEO_ALLOW_INDEXING=false`. Saat false, semua halaman mendapat `noindex, follow` dan `robots.txt` memuat `Disallow: /`. Ini cocok untuk localhost, preview, dan staging.

Setelah website online dengan domain dan HTTPS final:

1. Atur `APP_ENV=production`, `APP_DEBUG=false` dan `APP_URL=https://domain-produksi.example`.
2. Uji seluruh halaman, sitemap, canonical, dan sertifikat HTTPS.
3. Atur `SEO_ALLOW_INDEXING=true` pada environment production, kemudian segarkan configuration cache (`php artisan config:cache` bila dipakai).
4. Pastikan `/robots.txt` mengizinkan crawling dan memuat lokasi sitemap HTTPS yang benar.
5. Verifikasi domain di Google Search Console dan submit `/sitemap.xml`.

**Jangan aktifkan indexing pada staging.** Robots.txt tidak menjamin URL rahasia tetap privat; staging yang sensitif sebaiknya juga dibatasi aksesnya.

## Pengujian

Jalankan `php artisan test` untuk menguji URL canonical, filter noindex, sitemap, robots, dan pagination. Tambahkan pemeriksaan manual Livewire navigation di browser setelah pull request lolos CI.
