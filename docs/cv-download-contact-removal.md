# Perubahan About: Unduh CV dan penghentian halaman Contact

## Perubahan antarmuka

- **About / Tentang**: bagian "Keperluan Akademik & Media?" sekarang menawarkan **Unduh CV (PDF)**, bukan "Buka Contact & Media Hub".
- **Home**: kartu "Inquiries & Consultations" diganti menjadi **Curriculum Vitae** dengan tautan PDF yang sama.
- **Navbar desktop dan mobile**: tombol **Contact** diganti dengan **Unduh CV**. File diunduh langsung, tanpa `wire:navigate`.
- **Footer**: tautan Admin Page dan tautan halaman lain tetap dipertahankan. Tidak ditambahkan tautan menuju Contact.

## Rute dan dokumen

| URL | Perilaku |
| --- | --- |
| `GET /cv` | Mengunduh `public/files/m-natsir-kongah-cv.pdf` sebagai `M-Natsir-Kongah-CV.pdf` |
| `GET /contact/cv` | Redirect permanen (301) ke `/cv` agar tautan CV lama tetap berfungsi |
| `GET /contact` | 404 — halaman Contact benar-benar tidak tersedia |

PDF sudah ada di repository. Perubahan ini **tidak membuat atau mengubah isi PDF**. Untuk mengganti CV, pemilik situs perlu meninjau dan mengganti dokumen yang ada pada `public/files/m-natsir-kongah-cv.pdf`.

Komponen `app/Livewire/Contact.php`, template `resources/views/livewire/contact.blade.php`, formulir Web3Forms, dan konfigurasi Web3Forms/CV URL yang tidak digunakan dihapus. **Pengaturan WhatsApp `CONTACT_WHATSAPP_URL` tetap tersedia** untuk link profil pribadi di About.

## SEO dan pengujian

- `contact` dikeluarkan dari `config/seo.php` dan sitemap. Endpoint PDF `/cv` **bukan halaman HTML yang dimasukkan ke sitemap**.
- Audit HTTP memastikan `/contact` mengembalikan 404 dan `/cv` mengirim MIME PDF serta header unduhan.
- `tests/Feature/CvDownloadTest.php` mencakup unduhan asli, header attachment, redirect URL lama, tombol About/Home, navbar desktop/mobile, dan sitemap.
- Tes metadata, structured data, tipografi, dan tes navigasi diadaptasi untuk **lima halaman publik utama** plus halaman topik.

## Menjalankan di Laragon

Pada branch `feat/about-cv-remove-contact`, setelah mengecek perubahan lokal:

```bash
git fetch origin
git switch --track origin/feat/about-cv-remove-contact
php artisan optimize:clear
npm run build
php artisan test
```

Tidak ada migration/seeder baru untuk perubahan ini. URL yang perlu dicek: `/tentang`, `/`, `/cv`, `/contact`, dan `/sitemap.xml`. Pastikan unduhan CV bekerja pada desktop serta mobile, dan tidak muncul menu Contact lagi.

Perubahan dikirim sebagai **draft PR** agar bisa direview sebelum merge ke `main`.
