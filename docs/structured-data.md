# Structured Data JSON-LD — M-Natsir

Tahap 3 SEO: data terstruktur mengikuti **metadata dan konten publik yang sudah ada**, tidak membuat klaim atau publikasi baru.

## Struktur

- Homepage `/`: `WebSite`, `Person`, `WebPage` dalam satu `@graph`.
- Profil `/tentang`: `ProfilePage` dengan `mainEntity` bertipe `Person` yang berisi nama, profil publik, foto, fokus kajian, dan profil eksternal.
- `/articles`, `/library`, `/media`, `/contact`: `WebPage` untuk masing-masing halaman.
- Semua URL node mengambil route Laravel dan `PageMetadata::canonical()`, sehingga memakai domain `APP_URL` yang berlaku serta mengikuti aturan canonical untuk pagination/filter.
- ID stabil menggunakan fragmen `#website`, `#person`, dan `#webpage` tanpa menghasilkan identitas baru per request.
- Output JSON-LD di `resources/views/layouts/app.blade.php` memakai `json_encode` dengan flag `JSON_HEX_*` untuk menghindari injeksi tag script.

Konfigurasi informasi identitas ada di `config/seo.php`, bagian `person`. Tautan `sameAs` terbatas pada profil LinkedIn dan akun penulis Kumparan yang memang telah ditautkan dari halaman Tentang. Referensi institusi PPATK/PPID digunakan sebagai sumber informasi, **bukan** sebagai `sameAs` atau klaim bahwa website pribadi ini situs resmi PPATK. Tidak membuat `Organization`, `worksFor`, statistik pengikut, ataupun tanggal fiktif.

## Mengapa belum ada schema Article?

Katalog `/articles` saat ini mengarahkan pembaca ke publikasi eksternal (misalnya Kumparan dan media lain). Schema `Article` atau `NewsArticle` sebaiknya diterapkan ketika website memiliki **halaman detail tulisan orisinal sendiri**, dengan penulis, tanggal, URL dan isi yang benar-benar tersedia di halaman tersebut.

## Testing di localhost

1. Checkout branch dan jalankan `php artisan test --filter=StructuredDataTest` (atau `php artisan test`).
2. Jalankan web app (`php artisan serve` dan `npm run dev`).
3. Di `http://localhost:8000` dan `http://localhost:8000/tentang`, buka View Page Source, cari `application/ld+json`. Pastikan JSON memiliki `@context` dan `@graph`, dengan `WebSite` hanya di homepage dan `ProfilePage` hanya di Tentang.
4. `SEO_ALLOW_INDEXING=false` tetap digunakan untuk localhost; jangan mengaktifkannya hanya untuk menguji JSON-LD.

Setelah domain HTTPS production tersedia dan indexing memang siap diaktifkan, gunakan [Schema Markup Validator](https://validator.schema.org/) untuk validasi bentuk schema, [Rich Results Test](https://search.google.com/test/rich-results) untuk fitur yang didukung, dan Search Console untuk inspeksi halaman. Tidak semua jenis schema menghasilkan rich result. Google menyarankan `WebSite` di homepage untuk penamaan situs; `ProfilePage` sesuai untuk halaman "About Me" yang memang membahas satu individu.
