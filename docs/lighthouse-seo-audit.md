# Tahap 6 — Lighthouse & QA SEO Menyeluruh

## Cakupan audit

Workflow GitHub Actions **Lighthouse & SEO Audit** melakukan dua macam pengujian pada build produksi **sementara**, bukan di website public:

1. **HTTP SEO audit:** menjalankan Laravel lokal dan mengambil seluruh URL yang tercantum di sitemap. Memeriksa HTTP 200, title, meta description, satu H1, bahasa dokumen, canonical, OG/Twitter, JSON-LD, status robots, internal link, dan image asset. Query filter harus tetap `noindex`, sedangkan halaman yang layak indeks harus `index`. Slug topik tidak ada harus 404.
2. **Lighthouse CI mobile:** menggunakan Chrome untuk mengukur homepage, Tentang, Articles, dan salah satu halaman topik, dua run per halaman. Memuat laporan Performance, Accessibility, Best Practices, dan SEO dalam format JSON/HTML.

Audit dijalankan pada pull request ke `main`, push ke `main`, atau secara manual melalui tab Actions. Laporan diunggah sebagai **GitHub Actions artifacts selama 14 hari**, bukan di penyimpanan publik.

## Indexing aman

Workflow mengatur `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=http://127.0.0.1:8000`, dan `SEO_ALLOW_INDEXING=true` **hanya di runner GitHub**, agar dapat memeriksa konfigurasi SEO yang seharusnya berlaku saat deployment. Pengaturan `SEO_ALLOW_INDEXING=false` pada `.env.example` serta localhost/staging milikmu **tidak diubah**.

Audit tidak berarti localhost dapat diindeks Google; ini simulasi output production melalui HTTP internal.

## Pemeriksaan hard-fail

Audit HTTP gagal jika ada canonical salah, status halaman bukan 200, metadata hilang/ganda, JSON-LD rusak, URL sitemap hilang/duplikat, halaman filter tanpa noindex, internal link rusak, atau asset gambar tidak dapat dimuat.

Lighthouse gagal untuk audit `document-title`, `meta-description`, `html-has-lang`, dan `is-crawlable`. Skor kategori dan LCP/CLS/TBT awalnya **warning** agar bisa membangun baseline sebelum memasang ambang batas ketat yang realistis dan terukur.

## Melihat hasil

1. Buka GitHub → Actions → **Lighthouse & SEO Audit**.
2. Klik run pada PR terkait.
3. Buka **Summary** untuk ringkasan SEO HTTP dan skor Lighthouse.
4. Pada bagian **Artifacts**, unduh `lighthouse-seo-audit-...` untuk semua laporan HTML dan JSON.
5. Buka file HTML Lighthouse di browser untuk mengidentifikasi peluang perbaikan konkret.

Rincian per halaman juga tersedia di `reports/seo-audit.json` dan `reports/lighthouse-summary.json` dalam artifact tersebut.

## Menjalankan secara lokal

Prasyarat: PHP 8.4 + GD, Composer dependencies, Node 22, Chrome.

- Jalankan `npm run build` dan `php artisan test`.
- Jalankan `php artisan serve --host=127.0.0.1 --port=8000`.
- `node scripts/audit-seo.mjs http://127.0.0.1:8000` **hanya untuk server audit sementara yang memang diatur `SEO_ALLOW_INDEXING=true`**; script ini mengharapkan `index, follow` pada halaman sitemap.
- Untuk Lighthouse, instal `npm install --global @lhci/cli@0.15.x`, lalu jalankan `lhci autorun --config=.lighthouserc.cjs` pada server dengan URL yang sama. Jika Chrome tidak terdeteksi, set `LHCI_COLLECT__CHROME_PATH` ke lokasi Chrome.
- Pada development normal, biarkan `SEO_ALLOW_INDEXING=false`. **Jangan mengaktifkan indexing hanya demi tes.**

## Interpretasi metrik

Hasil Lighthouse merupakan **lab data di mesin CI**. Angka LCP dan CLS tersebut bisa berbeda di perangkat dan jaringan pengguna; TBT hanya indikator potensi responsivitas, **bukan pengukuran INP**. Target Core Web Vitals pada data nyata: LCP ≤ 2,5 detik, INP ≤ 200 ms, CLS ≤ 0,1 pada persentil ke-75.

CI hijau tidak menjamin Google mengindeks halaman atau memberi peringkat tertentu. Validasi Search Console baru dapat dilakukan setelah situs memiliki domain production dan HTTPS final.

## Checklist menuju production

- [ ] Domain HTTPS final dan `APP_URL` konsisten dengan canonical, sitemap, OG, dan JSON-LD.
- [ ] `SEO_ALLOW_INDEXING=true` hanya pada production yang siap diluncurkan; staging tetap noindex.
- [ ] Semua gambar WebP dan file JS/CSS hasil build tersedia pada web server.
- [ ] Tidak ada broken internal links atau hasil filter yang masuk sitemap.
- [ ] Konten peta bacaan dan atribusi publikasi eksternal ditinjau pemilik situs.
- [ ] Audit Lighthouse nyata lewat domain publik dibanding baseline CI.
- [ ] Verifikasi situs di Google Search Console, kirim sitemap, dan periksa laporan indexing setelah deployment.

Dokumentasi Lighthouse CI: https://github.com/GoogleChrome/lighthouse-ci/blob/main/docs/configuration.md
