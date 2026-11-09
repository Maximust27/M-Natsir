# Pembaruan tautan profil About

Tautan identitas personal utama pada halaman **Tentang**:
1. **LinkedIn** — profil profesional yang sudah ada.
2. **WhatsApp** — dipakai jika `CONTACT_WHATSAPP_URL` dikonfigurasi.
3. **Kumparan** — halaman penulis yang sudah ada.
4. **Academia.edu** — `https://independent.academia.edu/NatsirKongah` (profil dengan karya bertema pencucian uang).
5. **Artikel Majalah Editor** — `https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/` (tautan publikasi penulis, **bukan** diklaim sebagai profil akun).

Shortcut **PPATK / PPID** diganti WhatsApp. Link PPID dalam area **Rujukan profil publik** tetap dipertahankan sebagai bukti sumber historis peran profesional, bukan saluran kontak atau afiliasi situs saat ini.

## Konfigurasi WhatsApp di hosting/Laragon

Atur `.env` secara privat, misalnya:
```env
CONTACT_WHATSAPP_URL=https://wa.me/62NOMOR_WHATSAPP_YANG_DISETUJUI
```

Ganti `62NOMOR_WHATSAPP_YANG_DISETUJUI` dengan nomor resmi yang pemilik website izinkan untuk ditampilkan (tanpa tanda `+`, spasi, atau tanda hubung).

Jalankan `php artisan config:clear` setelah mengubah konfigurasi lokal. Jika `.env` menggunakan config cache di production, lakukan `php artisan config:cache` saat deployment.

Shortcut WhatsApp menerima **HTTPS** dengan domain `wa.me`, `api.whatsapp.com` atau `web.whatsapp.com`. Jika kosong/salah, tampilkan **WhatsApp (segera tersedia)** sebagai teks tidak dapat diklik — tidak pernah menampilkan URL placeholder atau nomor rekaan.

Academia.edu ditambahkan pada `Person.sameAs` karena merupakan profil. Publikasi Majalah Editor tidak ditambahkan pada `sameAs` karena merupakan halaman artikel, bukan akun identitas.

Sumber eksternal dapat berubah atau membatasi akses sewaktu-waktu; periksa secara manual sesudah deployment.


## Download CV

Untuk kebutuhan akademik dan media, halaman About sekarang memiliki tombol **Unduh CV (PDF)** yang mengarah ke `/cv`. Dokumen lokal yang sudah tersedia di `public/files/m-natsir-kongah-cv.pdf` disajikan sebagai attachment. Halaman Contact tidak lagi digunakan; kontak pribadi yang terverifikasi (misalnya profil WhatsApp jika dikonfigurasi) tetap ditampilkan di About.
