# Tahap 5 — Performa dan Core Web Vitals

Website M-Natsir saat ini menggunakan dua gambar hero besar (Home: JPEG, Tentang: PNG) di `public/images` (sekitar 1,8 MB masing-masing). Perubahan ini menyiapkan **WebP responsif secara otomatis** saat build dan mempertahankan file sumber (JPEG/PNG) sebagai fallback.

## Mekanisme gambar

- `scripts/optimize-hero-images.php` membuat WebP dengan PHP GD pada kualitas 76, tanpa mengubah konten gambar asli.
- Home: kandidat 480, 960, dan 1440 piksel (hanya yang lebih kecil dari lebar gambar asli) dan satu varian di resolusi asli.
- Tentang: kandidat 320 dan 640 piksel serta satu varian di resolusi asli.
- `npm run dev` dan `npm run build` otomatis menjalankan script langsung di dalam perintah `build` / `dev` (bukan lifecycle hook, karena `.npmrc` proyek memakai `ignore-scripts=true`).
- File `home-hero-*.webp` dan `about-hero-*.webp` adalah hasil build dan di-ignore oleh Git, **sehingga deployment perlu menjalankan build sebelum menayangkan site**.
- Blade menggunakan `<picture><source type="image/webp" srcset="..." sizes="..."><img src="...jpeg"></picture>`. Source WebP hanya muncul bila file tersebut benar-benar ada.
- Gambar LCP pada homepage dan halaman Tentang menggunakan `loading="eager"`, `fetchpriority="high"`, dan `decoding="async"`. Jangan menambah `loading="lazy"` pada gambar utama di atas fold.
- Frame gambar Tentang memakai `aspect-[3/4]` agar ukuran stabil sebelum gambar dimuat.

Jika GD dengan JPEG/WebP tidak terpasang, script memberikan pesan peringatan dan **build tetap jalan memakai gambar asal**. Pada Laragon, aktifkan ekstensi `gd` pada PHP aktif untuk mendapatkan WebP. Untuk memverifikasi, jalankan:

```bash
php -r "var_dump(function_exists('imagewebp'));"
npm run build
```

Jika tersedia, `public/images` akan berisi varian seperti `home-hero-480.webp` dan `about-hero-320.webp`. Periksa sumber halaman: tag `<source type="image/webp">` hanya akan ada jika varian WebP benar-benar terbentuk.

## Font dan hero

- Vite sebelumnya mengkonfigurasi Instrument Sans dari Bunny Fonts, meskipun stylesheet situs menggunakan Newsreader, Plus Jakarta Sans, dan JetBrains Mono dari Google Fonts. Konfigurasi font yang tidak terpakai dihapus tanpa mengubah tipografi situs.
- Hero utama kini tidak menyembunyikan judul/konten awal di balik animasi JavaScript. Animasi reveal konten di bagian halaman lain tetap dipertahankan.
- Gambar utama sudah dapat ditemukan crawler pada HTML awal; tidak menambah preload kedua yang berisiko mengunduh gambar dua kali.

## Pengujian

1. Checkout branch `feat/performance-core-web-vitals`.
2. Jalankan `npm run dev` (untuk membuat varian WebP sebelum Vite start) dan `php artisan serve`; periksa Home dan Tentang.
3. Jalankan `php artisan test`, khususnya `php artisan test --filter=PerformanceImagesTest`.
4. Buka DevTools Network, filter `Img`: Chrome yang mendukung WebP semestinya memuat satu varian WebP alih-alih JPEG besar. Pastikan tidak ada 404, gambar tidak berubah crop, dan ukuran portrait tetap stabil.
5. Jalankan Lighthouse **dengan `npm run build`** agar hasil lebih mendekati production. Catat LCP, INP (field data), CLS, dan jumlah transfer gambar dibanding branch `main` sebelum merge.

Target Core Web Vitals untuk 75% kunjungan: LCP ≤ 2,5 detik, INP ≤ 200 ms, CLS ≤ 0,1. Ini target, bukan skor yang diklaim sudah tercapai dari hasil tes kode. Pengukuran nyata setelah deploy tetap diperlukan.

## Deployment

Hasil WebP tidak dikomit. Pastikan proses CI/CD atau deployment menjalankan `npm ci && npm run build` dengan PHP GD (JPEG dan WebP) yang aktif, lalu menerbitkan **seluruh folder `public` termasuk `public/images/*webp`**. Jika hanya menyalin source Git tanpa build, website tetap berjalan memakai JPEG lama tetapi peningkatan ukuran gambar tidak ikut terbawa.

Referensi:
- https://web.dev/articles/preload-responsive-images
- https://developers.google.com/search/docs/appearance/google-images
- https://developers.google.com/search/docs/appearance/core-web-vitals
