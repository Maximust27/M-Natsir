# Admin CRUD — Artikel, Library, Media

Branch: `feat/admin-content-crud` (berbasis `feat/admin-content-entry-media-curation`; PR draft #16). **Belum di-merge.**

## Alur kerja admin

1. Login pada `/admin/login` lalu buka `/admin`.
2. Pilih salah satu dari tiga kartu: **Artikel**, **Library**, **Media**.
3. **Form Tambah** yang sesuai muncul di bawah kartu. Isi field wajib, pilih **Draf** (default) atau **Publikasikan langsung**, lalu simpan.
4. Di bawah form ada **Daftar Konten**. Cari judul, sumber, atau ringkasan; gunakan tab **Semua / Draf / Terbit / Sampah** dan tombol Sebelumnya/Berikutnya.
5. Setiap item aktif menyediakan **Detail** (pratinjau metadata khusus admin), **Edit** (form di atas berubah menjadi form penyuntingan), **Publikasikan/Jadikan Draf** (tanpa edit isi), dan **Hapus**.
6. Tombol **Hapus** membuka **konfirmasi kedua**. Jika disetujui, data pindah ke **Sampah**, bukan langsung hilang permanen.
7. Pada tab **Sampah** tekan **Pulihkan**. Data kembali **sebagai Draf** agar tidak mendadak muncul ke publik. Untuk menerbitkan, buka tab Draf lalu tekan Publikasikan.

Aksi disimpan ke tabel MySQL asli. **Halaman publik hanya membaca konten \`is_published=true\` dan \`deleted_at=null\`**. Daftar di admin menampilkan konten yang sudah ada, termasuk hasil ContentSeeder.

## Field yang bisa diubah

- **Artikel:** judul, kategori, sumber, ringkasan, tanggal, URL asli, status sumber primer, catatan sumber, status terbit.
- **Library:** judul, koleksi, jenis, institusi (termasuk nama baru), tahun/Arsip, topik jamak, penulis, peran, bahasa, ringkasan, URL, catatan sumber, status terbit.
- **Media:** judul, format (wawancara, diskusi, liputan kasus), tanggal, outlet, peran, ringkasan, topik, URL, thumbnail (upload/URL/hapus), catatan sumber, status terbit.

Field internal seperti `seed_key`, `sort_order`, `created_at` tidak diisi admin. **Edit tidak mengubah seed_key**; perubahan judul/URL aman tanpa menduplikasi seed. Edit juga menjaga tanggal label sumber asli jika kolom tanggal dibiarkan kosong, termasuk arsip yang hanya punya bulan dan tahun (mis. "Agustus 2026"). Label kategori/topik dan atribusi asli tetap dipertahankan jika tidak diubah.

Untuk thumbnail Media: pilih JPG, PNG, atau WebP, maksimal **2 MB**. URL gambar eksternal bisa dipakai sebagai alternatif; unggahan baru menggantikan gambar yang lama. Bila gambar milik aplikasi diganti/dihapus melalui Edit, hanya file gambar tersebut yang dibersihkan dari penyimpanan publik. **Hapus ke Sampah tidak menghapus file** agar data masih bisa dipulihkan.

## Migrasi aman untuk MySQL Laragon

Jalankan di Git Bash pada database lokal \`m_natsir\` yang sudah memiliki akun admin:

```bash
git status
git fetch origin
git switch --track origin/feat/admin-content-crud

php artisan config:clear
php artisan migrate
php artisan db:seed --class=ContentSeeder
php artisan storage:link
npm run build
php artisan test
```

Jika branch lokal sudah tersedia, gunakan `git switch feat/admin-content-crud` lalu `git pull --ff-only origin feat/admin-content-crud`. Pastikan tidak menimpa perubahan lokal. **Jangan** gunakan `git reset --hard`, `git clean -fd`, atau `php artisan migrate:fresh` pada data kerja. Biarkan file gambar WebP lokal yang belum tracked tetap aman.

Migration baru **menambah `deleted_at`** pada tiga tabel, tanpa menghapus data/akun. \`ContentSeeder\` hanya mengisi data awal yang belum ada dan menyembunyikan enam seed Media humas non-kasus yang masih identik dengan arsip lama. **Record yang masuk Sampah tetap memiliki seed_key**, jadi menjalankan seeder ulang tidak akan menghidupkannya kembali. Tidak perlu \`AdminSeeder\` jika akun admin sudah dibuat.

## Keamanan dan batas fitur

- Semua tindakan Livewire mengecek hak admin (berdasarkan `users.is_admin`). ID dalam state edit/detail/hapus dikunci; status dan kategori input divalidasi di server.
- Delete dapat dipulihkan; untuk penghapusan **permanen** perlu keputusan bisnis/retensi tersendiri. Ini mengurangi risiko kehilangan sumber/dokumen.
- Template dan warna tipografi tetap dari sistem global `type-*`; link/logout tetap pada layout admin.
- Editor **konten artikel penuh** (WYSIWYG), upload PDF Library, pengelolaan halaman About/Home/Contact, audit log versi per perubahan, dan multi-admin permission belum termasuk tahap ini.
- Saat deploy, wajib backup MySQL dan storage gambar; uji responsif 375px–1280px dan zoom 200%.

## Verifikasi

`tests/Feature/AdminContentCrudTest.php` meliputi daftar, filter, pencarian, pagination, edit tiga modul, pemeliharaan atribusi, status Draf/Terbit, penggantian/hapus gambar, konfirmasi hapus, pemulihan sebagai Draf, dan keselamatan seeder. Jalankan juga seluruh tes regresi: `php artisan test`.
