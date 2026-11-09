# Admin — form tambah Artikel, Library, Media dan kurasi ulang Media

Branch: `feat/admin-content-entry-media-curation` (berdasarkan `feat/admin-auth-foundation`). Masih draft; tidak di-merge.

## Alur pengguna

1. Login lewat `/admin/login`.
2. Buka `/admin` dan pilih salah satu kartu: **Artikel**, **Library**, atau **Media**.
3. Form tambah yang sesuai muncul **tepat di bawah tiga kartu**. Beralih kartu akan mengosongkan form sebelumnya; data yang belum disimpan tidak dipertahankan.
4. Isi judul, metadata, ringkasan, dan URL asli. Field internal `id`, `seed_key`, `sort_order`, dan timestamp dibuat otomatis.
5. Status awal **Draf** agar konten tidak langsung tampil publik. Centang **Publikasikan langsung** hanya jika materi dan URL sudah dicek.
6. Setelah submit, ada pesan berhasil dan hitungan **terbit/draf** pada kartu diperbarui. Isi situs publik dibaca dari tabel yang sama.

Form sesuai modul:

- **Artikel:** judul, kategori, tanggal publikasi, media sumber, ringkasan, URL, status sumber primer, catatan sumber opsional.
- **Library:** judul, koleksi, jenis dokumen, institusi (bisa institusi baru), tahun atau "Arsip", topik jamak via checkbox, penulis/peran opsional, bahasa, ringkasan, URL, catatan sumber.
- **Media:** judul, jenis wawancara/diskusi/liputan kasus, tanggal, outlet, peran, ringkasan, topik dipisahkan koma, URL, foto unggahan opsional atau URL thumbnail, catatan sumber opsional. Tahun otomatis diambil dari tanggal; unggahan gambar maksimal 2 MB.

Tiga form menggunakan `type-*` dari sistem tipografi global (`resources/css/app.css`). Tombol "Kembali ke website" dan "Keluar" tetap berada di **layout admin bersama**.

**Keamanan:** Livewire memeriksa status admin saat render dan setiap aksi; URL divalidasi HTTP(S); kategori dan topik divalidasi; penulisan database memakai whitelist field eksplisit; foto menggunakan validasi gambar; CSRF/session Livewire aktif. CRUD **edit/hapus/list** belum dibuat — ini adalah **form tambah**.

## Kurasi ulang data seed Media

Enam catatan yang semata-mata tentang kegiatan pers/humas, kunjungan media, penghargaan atau laporan kehumasan dikeluarkan dari snapshot seed:

1. Sinergi PPATK dan Pikiran Rakyat (media visit)
2. PPATK Teguhkan Komitmen Sebagai Badan Publik Informatif (penghargaan)
3. PPATK Sabet Penghargaan Gatra Awards 2023 (penghargaan)
4. Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK (administrasi informasi)
5. Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post (media visit)
6. Festival Kreatif Generasi Anti Pencucian Uang 2019 (laporan kegiatan)

**10 entri tetap dipertahankan** karena membahas kejahatan keuangan, wawancara tentang judi online, modus QRIS/kripto, dana desa, transaksi mencurigakan, atau substansi anti pencucian uang. Atribusi jabatan "humas/jubir" pada narasumber **tidak otomatis menjadikan wawancaranya berita kehumasan**; yang menentukan ialah isi publikasi.

Pada database baru, 6 entri tersebut tidak di-seed. Pada database lama yang sudah memuat 16 entri, jalankan ulang `ContentSeeder` untuk menyembunyikan enam entri **yang masih cocok persis dengan seed lama** (`seed_key`, judul, URL). Operasi ini mengubah `is_published=false` **bukan DELETE**; entri tetap tersedia dalam tabel demi keamanan data. Data yang sudah diubah pengguna atau dibuat manual tidak tersentuh.

## Instalasi lokal Laragon (database MySQL m_natsir sudah ada)

Sebelum beralih branch, periksa `git status`; jangan gunakan `reset --hard` atau `clean`. File gambar lokal untracked tetap dibiarkan.

```bash
git fetch origin
git switch --track origin/feat/admin-content-entry-media-curation
php artisan config:clear
php artisan migrate
php artisan db:seed --class=ContentSeeder
php artisan storage:link
npm run build
php artisan test
```

Jika branch lokal sudah pernah dibuat: `git switch feat/admin-content-entry-media-curation && git pull --ff-only origin feat/admin-content-entry-media-curation`.

Tidak perlu menjalankan `AdminSeeder` lagi. **Jangan menjalankan `migrate:fresh`** pada database berisi akun admin atau konten yang sudah ada. Untuk upload gambar, `php artisan storage:link` membuat URL `/storage/...` dapat diakses. Saat deploy, atur volume/storage publik, backup, dan izin file.

## Batas fitur

Belum ada halaman editor, penghapusan, daftar pengelolaan per baris, bulk import, penyuntingan artikel lengkap, atau pengaturan Home/About/Contact melalui admin. Form ini menyimpan **metadata dan ringkasan serta tautan asli**, bukan membuat salinan penuh artikel milik outlet lain.
