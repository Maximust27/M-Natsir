# Audit SEO dasar — M-Natsir

Audit dilakukan terhadap source code pada branch `main` sebelum perubahan fondasi SEO. Situs masih dikembangkan di localhost.

## Peta halaman publik

| Route | Komponen | Tujuan pencarian |
| --- | --- | --- |
| `/` (`home`) | `Home` | Profil dan financial intelligence |
| `/tentang` (`about`) | `About` | Pengalaman dan profil profesional |
| `/articles` (`articles`) | `Articles` | Opini dan analisis anti pencucian uang |
| `/library` (`library`) | `Library` | Riset, referensi, dan regulasi |
| `/media` (`media`) | `Media` | Wawancara dan aktivitas media |
| `/contact` (`contact`) | `Contact` | Kontak profesional |

`/contact/cv` adalah endpoint unduhan berkas, bukan landing page SEO.

## Perubahan pada PR fondasi

- Mempertahankan judul per halaman dengan atribut `#[Title]` Livewire, sekaligus mempertajam kata kunci judul untuk lima halaman.
- Membuat `config/seo.php` sebagai sumber deskripsi per route dan pengaturan gambar pratinjau.
- Memakai fallback untuk route baru yang belum memiliki konfigurasi khusus.
- Menambahkan Open Graph dan Twitter Card ke head layout, termasuk URL absolut dan teks alternatif gambar.
- Mempertahankan markup visual, route, dan logika filter Livewire yang sudah ada.
- Menambahkan regression test Pest untuk enam halaman dan URL hasil filter.

## Pekerjaan lanjutan, tidak termasuk PR ini

1. Canonical URL dan kebijakan indexing untuk pencarian, filter serta pagination; ubah pagination ke link yang dapat dirayapi.
2. Sitemap XML dan pengelolaan robots.txt untuk production.
3. Schema JSON-LD (Person, WebSite, ProfilePage, Article bila sesuai).
4. Konten artikel orisinal, internal linking, dan pemeriksaan kualitas sumber.
5. Optimasi gambar/font, pengujian Core Web Vitals dan deployment.

## Verifikasi

Jalankan `php artisan test --filter=SeoMetadataTest` atau `php artisan test`. Setelah domain production tersedia, periksa hasil HTML dan pratinjau berbagi tautan dengan URL HTTPS publik. URL localhost tidak dapat diindeks Google.
