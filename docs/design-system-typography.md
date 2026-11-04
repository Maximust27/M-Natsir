# Sistem tipografi M. Natsir — aturan lintas halaman

**Aturan tetap untuk setiap revisi UI berikutnya:** ukuran tipografi ditentukan berdasarkan **fungsi semantik teks**, bukan berdasarkan halaman atau kesukaan saat mengedit satu kartu. Jangan membuat ukuran terpisah untuk Peta Kajian, Featured Writings, Articles, Library atau Media jika fungsinya sama.

## Satu sumber kebenaran

Seluruh class **`type-*`** didefinisikan dan dipelihara di `resources/css/app.css`, dalam `@layer components`. Perbarui di satu tempat untuk mengubah semua view.

| Peran | Kelas reusable | Ukuran dasar | Dipakai di |
|---|---|---|---|
| Judul halaman | `type-page-title` | 42–56 px responsif | H1 About, Articles, Library, Media, Topik, Contact |
| Judul section | `type-section-title` | 36–42 px responsif | Peta Kajian, Featured Writings, heading utama semua halaman |
| Judul kartu | `type-card-title` | 26–30 px responsif | Kartu topik, tulisan terbaru, publikasi Articles, koleksi Library, liputan Media |
| Subjudul detail | `type-subheading` | 20 px | Subbagian, judul rincian, kontribusi, form dan informasi pendukung |
| Pengantar | `type-lead` | 20–22 px responsif | Intro halaman dan deskripsi utama hero |
| Teks isi | `type-body` | 18 px | Seluruh paragraf edukasi dan deskripsi kartu |
| Teks pendukung | `type-support` | 16 px | Sumber tambahan, keterangan, dan catatan |
| Label section | `type-eyebrow` | 16 px | “Peta Kajian”, “Tulisan Terbaru”, label utama setiap section |
| Metadata | `type-meta` | 14 px | Tanggal, outlet, badge, catatan sumber, label input |
| Link/tombol | `type-action` | 16 px | Navigasi, CTA, tombol, filter |
| Statistik | `type-stat` | 22–28 px responsif | Jumlah koleksi, media dan angka ringkasan |
| Quote | `type-quote` | 20 px | Kutipan editorial |
| Input | `type-form-control` | 18 px | Input, select, textarea |

**Minimum teks informatif: 14 px.** Perhatikan keterbacaan pada pengguna lanjut usia. Ukuran font bukan ukuran tap target; tautan/tombol interaktif memiliki tinggi target minimal 44 px.

## Tipografi dan kontras

- Font judul editorial: **Newsreader**. Teks naratif, tombol, dan navigasi: **Plus Jakarta Sans**. Label editorial dan metadata tertentu: **JetBrains Mono**.
- Warna isi utama `text-ink` (`#0e1c2f`, mendekati hitam), bukan abu-abu terang. Metadata sekunder `text-slate-500` menggunakan warna kuat `#253044`. Di latar gelap gunakan warna teks terang.
- Teks tidak boleh terpotong, bertumpuk, atau memaksa geser horizontal pada perangkat kecil. H1 hero adalah **pengecualian editorial** karena dibuat khusus mengikuti foto Pak Natsir; H1 halaman lainnya tetap memakai `type-page-title`.
- Jangan menambahkan ulang kelas `text-2xl`, `sm:text-[...]` atau `leading-* ` di elemen yang sudah memiliki `type-*`: aturan yang bertabrakan membuat visual tidak konsisten.
- Pengecualian logo/identitas brand dan karakter dekoratif boleh mempunyai ukuran tersendiri karena bukan paragraf, judul konten, atau metadata.

## Checklist wajib setiap revisi

1. Periksa Home (Peta Kajian dan Featured Writings), About, Articles, Library, Media, Topic, Contact, serta header dan footer.
2. Periksa layar 320 / 375 / 768 / 1024 / 1280 px dan zoom browser 200%. Teks panjang, judul kartu dan label tidak boleh terpotong.
3. Baca teks di latar putih, foto, kartu berwarna, dan panel gelap untuk mengecek kontras.
4. Jalankan `npm run build` dan `php artisan test`. Regression tests di `TypographySystemTest.php` menjaga peran kelas bersama tidak bergeser.
5. Jika ingin mengubah hierarki, **ubah kelas `type-*` di CSS dan seluruh instance semantik terkait**, bukan hanya satu section.

Setiap pengembangan admin panel, form baru, halaman baru, atau refactor database harus mengikuti kontrak ini sejak awal.
