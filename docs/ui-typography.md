# Revisi 1 — Keterbacaan tipografi dan kontras

## Prinsip tipografi
- Identitas font tetap: **Plus Jakarta Sans** untuk isi, tombol, dan navigasi; **Newsreader** untuk judul; **JetBrains Mono** untuk label/kategori dan metadata.
- Skala ukuran: label/caption **14 px** (`text-xs`), navigasi/UI **16 px** (`text-sm`), isi utama **17 px** (`text-base`), pengantar **20 px** (`text-lg`); judul halaman lebih besar.
- Seluruh ukuran kustom lama `text-[10px]` sampai `text-[15px]` pada Blade diubah ke token baru, sehingga keterangan paling kecil tidak lagi 10–11 px.
- Warna sekunder dipergelap: slate-500 `#475569`, slate-600 `#334155`, slate-700 `#1e293b`; warna isi utama sekarang slate-700.
- Tinggi sentuh menu, tombol, filter dinaikkan menjadi minimal 44 px. Navbar desktop muncul mulai breakpoint `xl` agar tidak bertabrakan pada lebar tablet/laptop sedang.
- Google Fonts dimuat tanpa memblokir first paint melalui preload stylesheet dan `font-display: optional`, disertai fallback saat JavaScript dinonaktifkan. Pada jaringan lambat, browser mungkin mempertahankan font sistem sampai kunjungan selanjutnya demi mencegah layout shift; ini disengaja. Tidak menambahkan font atau package baru. Huruf, identitas brand, struktur konten, dan URL tetap sama pada revisi ini.

## Pemeriksaan visual sebelum merge
1. Buka Home, Tentang, Articles, Library, Media, dan Topik pada lebar 375, 768, 1024, dan 1280 px.
2. Periksa judul, kartu, metadata sumber, label form, footer, dan filter agar tidak terpotong atau menyebabkan horizontal scroll.
3. Coba browser zoom **200%**, gunakan menu mobile, dan pastikan tautan/tombol tetap dapat dipilih.
4. Periksa warna sekunder pada latar terang dan teks slate-300 di area gelap.
5. Jalankan `npm run build`, `php artisan test`; bandingkan Lighthouse sebelum/sesudah. Ukuran yang lebih besar dapat mengubah layout/lab LCP.

## Batas lingkup
Belum mengganti teks hero, logo/header, Contact, footer Admin Panel, autentikasi, database, seeder, maupun konten media. Semua itu masuk ke revisi 2–10 secara berurutan.

Perlu peninjauan tampilan langsung, terutama pada layar sempit dan mode zoom; tes otomatis tidak menjamin tidak ada teks yang terpotong.
