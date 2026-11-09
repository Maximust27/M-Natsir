# Admin authentication — tahap fondasi

Branch: `feat/admin-auth-foundation` (berdasarkan `feat/typography-header-brand`).

## Yang sudah tersedia

- **`/admin/login`**: halaman masuk dengan email, password, tombol, pesan error, CSRF dan `autocomplete` yang sesuai.
- **`/admin`**: dashboard awal hanya untuk akun admin. Menampilkan tiga modul **Artikel, Library, Media** dengan jumlah data yang dibaca langsung dari tabel SQL. Pada branch lanjutan `feat/admin-content-entry-media-curation`, kartu dapat dipilih untuk menampilkan **form tambah konten** dengan opsi simpan draf atau terbit dan upload foto Media.
- **`POST /admin/logout`**: logout melalui form dengan CSRF, menghapus session dan meregenerasi CSRF token. Kontrol logout serta **Kembali ke website** berada di layout admin; tombol logout tidak diduplikasi di dashboard.
- Tidak ada halaman / route registrasi.
- Middleware `auth` + `admin` melindungi dashboard dan semua route admin berikutnya. Akun biasa mendapat HTTP 403.
- **Perlindungan tambahan:** lima percobaan login per menit per pasangan email/IP; plafon umum per IP, noindex/nofollow, dan no-store HTTP response agar halaman admin tidak tercache.
- Password memakai hash bawaan Laravel, sesi diregenerasi setelah login. Tidak ada email/password default.
- Layout admin memakai **kelas tipografi reusable yang sama** dari `resources/css/app.css`: `type-page-title`, `type-card-title`, `type-lead`, `type-body`, `type-support`, `type-meta`, `type-action`, `type-form-control`. Logo SVG, warna, dan font sama dengan halaman publik.

## Setup lokal di Laragon

1. Pada Git Bash:
   ```bash
   git fetch origin
   git switch --track origin/feat/admin-auth-foundation
   composer install
   npm ci
   npm run build
   php artisan migrate
   php artisan db:seed --class=ContentSeeder
   ```
   Data awal **13 Artikel, 22 Library dan 10 Media yang relevan dengan kasus** diimpor ke MySQL tanpa menyentuh user/admin. Pada setup dengan akun admin yang sudah dibuat, **cukup jalankan migrasi dan `ContentSeeder`**, tidak perlu mengulang `AdminSeeder`.
2. Tambahkan variabel **sementara** di `.env` lokal (jangan pernah commit `.env`):
   ```dotenv
   ADMIN_SEED_NAME="Pengelola Website"
   ADMIN_SEED_EMAIL="alamat-email-yang-dipilih@example.com"
   ADMIN_SEED_PASSWORD="kata-sandi-acak-kuat-minimal-12-karakter"
   ```
   Ganti dengan email resmi admin dan password unik yang kuat. Jangan gunakan nilai contoh ini.
3. Jalankan:
   ```bash
   php artisan config:clear
   php artisan db:seed --class=AdminSeeder
   ```
   Seeder hanya membuat satu akun admin. Bila dijalankan ulang, **tidak** mengubah password yang sudah tersimpan. Seeder **tidak** akan membuat user jika email/password tidak dikonfigurasi. Tidak ada akun uji bawaan.
4. Setelah berhasil, **hapus `ADMIN_SEED_PASSWORD` dari `.env`** dan jalankan `php artisan config:clear` lagi. Password tetap tersimpan dalam database sebagai hash. Simpan password asli di password manager, jangan di dokumen proyek atau pesan chat.
5. Jalankan `php artisan serve` dan buka **http://127.0.0.1:8000/admin/login**. Setelah masuk, coba logout, lalu buka `/admin` tanpa login untuk melihat redirect. Bisa juga masuk melalui link **Admin Page** di footer.

Jika ingin memakai `localhost:8000`, gunakan host URL tersebut sesuai konfigurasi server kamu.

## Ketika hosting

- Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-situs`, `SESSION_SECURE_COOKIE=true`, HTTPS wajib.
- Gunakan database dan cache/session driver yang sudah disiapkan, jalankan `php artisan migrate --force`.
- Seeder dengan konfigurasi sementara yang privat: `php artisan db:seed --class=AdminSeeder --force`. Setelah sukses, hapus variabel seed password dan **bangun ulang config cache** jika dipakai.
- Jalankan `php artisan config:cache` sesudah variabel seed sensitif dihapus.
- Untuk fase produksi lanjutan, rencanakan **2FA**, audit log perubahan konten, backup terenkripsi, dan kontrol akses CRUD. Belum ada 2FA pada tahap ini.

## Verifikasi

```bash
npm run build
php artisan test
```

Uji tampilannya pada 375px, 768px, dan 1280px, serta zoom 200% untuk aksesibilitas. Pastikan email/password tidak muncul dalam log, HTML, dan commit Git.

## Batas lingkup

Dashboard kini membaca statistik dari **tiga tabel konten** yang sudah dibuat dan di-seed. Sumber data untuk halaman Home, Articles, Topic, Library dan Media juga sudah pindah ke SQL. **Form tambah metadata Artikel/Library/Media dan upload gambar Media** tersedia di branch `feat/admin-content-entry-media-curation`. **CRUD lengkap Artikel/Library/Media (daftar, cari, edit, status terbit, Sampah dan pulihkan)** tersedia pada branch lanjutan `feat/admin-content-crud` (PR #16, masih draft). **Upload PDF, editor artikel lengkap, audit perubahan, dan CV belum tersedia**. Panduan: `docs/admin-content-crud.md`.
