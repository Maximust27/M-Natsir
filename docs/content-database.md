# Database Konten: Artikel, Library, Media

Cabang: `feat/admin-auth-foundation`, turunan dari sistem tipografi bersama `feat/typography-header-brand`.

## Apa yang berubah

Semua data publikasi yang sebelumnya ada dalam array PHP kini disimpan dalam **tiga tabel**. Data aslinya dipertahankan sebagai **snapshot seed saja**, bukan lagi sumber runtime:

| Tabel SQL | Model | File seed awal | Jumlah awal | Field khusus |
| --- | --- | --- | ---: | --- |
| `articles` | `App\\Models\\Article` | `database/seeders/data/articles.php` | 13 | kategori, badge, sumber, tanggal, excerpt, URL asli, status sumber primer, catatan atribusi |
| `library_resources` | `App\\Models\\LibraryResource` | `database/seeders/data/library_resources.php` | 22 | koleksi, jenis dokumen, institusi, tahun (termasuk nilai “Arsip”), topik dan label JSON, penulis/peran, bahasa, URL |
| `media_appearances` | `App\\Models\\MediaAppearance` | `database/seeders/data/media_appearances.php` | 10 | kategori penampilan, outlet, peran, tanggal/tahun, topik JSON, thumbnail, URL, catatan sumber |

Masing-masing tabel memiliki `seed_key` unik, `sort_order` untuk menjaga urutan editorial, `is_published` untuk memisahkan publikasi aktif/draf, timestamp, serta **`deleted_at`** (migration baru pada branch CRUD) agar hapus bisa dipulihkan. Data `users` dan akun admin **tidak dipindahkan atau dihapus**.

**Alur halaman nyata:** Home, Articles dan Topic Hubs menggunakan `ArticleCatalog` yang kini membaca `articles` dari database; Library membaca `library_resources`; Media membaca `media_appearances`. Bentuk array `toCardArray()` mempertahankan API kartu yang sudah ada supaya layout dan filter tetap konsisten. Status dan kategori yang dipilih bisa berubah di database; label pilihan filter tetap didefinisikan sebagai aturan UI, bukan data publikasi.

Dashboard menampilkan **jumlah terbit/draf** dan membuka form tambah/edit sesuai modul. Branch `feat/admin-content-crud` juga menyediakan daftar, filter, status publikasi, Sampah, dan pemulihan dari tabel SQL yang sama.

## Langkah aman di MySQL Laragon

Jalankan pada branch `feat/admin-content-crud` setelah memastikan perubahan lokal tidak tertimpa:

```bash
git fetch origin
git switch --track origin/feat/admin-content-crud
php artisan config:clear
php artisan migrate
php artisan db:seed --class=ContentSeeder
php artisan storage:link
npm run build
php artisan test
```

Database `m_natsir` yang sudah berisi akun admin **tetap digunakan**. Gunakan `php artisan migrate` biasa (bukan `migrate:fresh`), lalu seed konten saja menggunakan `ContentSeeder`. **Tidak perlu menjalankan `AdminSeeder` lagi**.

Seeder menggunakan `insertOrIgnore`: jika dijalankan ulang, record berdasarkan `seed_key` tidak akan digandakan dan **isi record yang sudah pernah diedit tidak akan ditimpa**. Pada branch CRUD, record yang dihapus melalui Admin menggunakan soft delete sehingga **tidak terbit kembali ketika ContentSeeder diulang**. Jangan hapus seed langsung dengan SQL tanpa rencana retensi/backup. Untuk menyembunyikan konten lebih aman gunakan `is_published=false`. Seed baru Media menghapus enam liputan humas non-kasus; untuk database lama `ContentSeeder` akan **menonaktifkan publikasinya hanya jika record masih cocok persis dengan seed lama**, bukan menghapus SQL row atau mengubah data admin.

Jangan commit `.env` ataupun file WebP lokal yang belum dilacak Git tanpa ditinjau. Jangan pernah memakai perintah `migrate:fresh` atau `db:wipe` pada database dengan akun maupun konten asli.

## Skema/UI lintas halaman

- Tipografi **tetap dari `resources/css/app.css`**. Kartu memakai `type-card-title`, deskripsi `type-body`, metadata `type-meta`, tombol `type-action`. Tidak ada font tambahan khusus admin.
- Tombol **Keluar** hanya ada pada **layout admin bersama**. Di dashboard tidak lagi ada form logout ganda. Tautan **Kembali ke website** tetap berada di layout.
- Database hanya memuat informasi yang sebelumnya telah diverifikasi/ditampilkan sebagai tautan sumber. **Tidak ada artikel/publikasi baru yang dibuat-buat**, dan konten lengkap milik media eksternal tidak disalin.
- Form **tambah** Artikel, Library, dan Media sekarang memvalidasi field sesuai kartu, membuat field teknis otomatis, dan mengunggah thumbnail Media pada public storage. Konten baru masuk di **urutan teratas** tanpa mengubah urutan relatif konten lama. Fitur **edit, hapus ke Sampah, dan pulihkan** sudah tersedia pada `feat/admin-content-crud`. Audit log dan hubungan kategori lanjutan menyusul.

## Verifikasi

Jalankan `php artisan test` dan `npm run build`; cek Home, semua filter Articles/Library/Media, Topic Hubs, admin dashboard, dan login/logout. Uji layout 375px serta 1280px dan zoom browser 200%. Migration harus aman bagi data user sebelumnya.
