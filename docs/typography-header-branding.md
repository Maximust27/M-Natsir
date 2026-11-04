# Revisi tipografi dan identitas header

Perubahan ini dibuat **dari branch `feat/seo-topic-hubs`**, tanpa mengambil PR #12 lama atau perubahan fase lain pada `main`.

## Hierarki ukuran
- Keterangan, label kategori, meta, eyebrow: **14 px** (Tailwind `text-xs`).
- Navbar, tombol, filter, link kecil: **16 px** (`text-sm`).
- Teks narasi utama dan ringkasan kartu: **18 px** (`text-base`).
- Teks pengantar, kutipan, dan lead: **20–24 px**.
- Judul tetap memakai Newsreader, body dan navigasi Plus Jakarta Sans, sedangkan metadata JetBrains Mono.
- Teks dengan utilitas warna `text-slate-600`/`text-slate-700` di view diganti menjadi `text-ink`; warna teks sekunder `text-slate-500` dipergelap sehingga label tetap jelas pada latar terang.

## Header
- Logo memakai file vektor asli `public/favicon.svg` melalui `<img>`, bukan PNG 16×16 yang diperbesar; logo tetap tajam di layar retina.
- Identitas utama: **Financial Intelligence Hub**.
- Identitas sekunder: **M. Natsir Kongah**.
- Menu desktop aktif mulai lebar `xl` (1280 px) untuk mencegah tabrakan ketika teks navigasi diperbesar. Menu mobile tetap tersedia di bawah lebar itu.

## Cek manual
1. Buka Home, About, Articles, Library, Media, Contact dan halaman topik pada lebar 320, 375, 768, 1024, 1280 px.
2. Uji zoom browser 200%, tombol menu mobile, teks yang panjang, card, badges, footer, dan filter agar tidak terpotong.
3. Cek logo SVG dengan zoom 200–300%: harus tetap tajam.
4. Jalankan `npm run build` dan `php artisan test`.
5. Periksa kontras teks pada latar putih maupun kartu gelap.

## Revisi tambahan pada branch yang sama
- Kartu **Peta Kajian** diselaraskan dengan hierarki halaman lain: label 16 px, judul kartu 24–30 px, ringkasan 18 px, tautan 16 px dengan bidang sentuh minimal 44 px.
- Hero menampilkan H1 **MEMBONGKAR PENCUCIAN UANG DAN KEJAHATAN KERAH PUTIH**, menghapus kutipan lama, dan mengganti deskripsi menjadi ajakan belajar melalui analisis dan rujukan asli bersama **M. Natsir Kongah**.
- Layout hero dipisahkan antara **foto di atas dan teks di bawah untuk HP/tablet**. Pada desktop, teks dibatasi pada kolom kiri dengan gradasi putih agar **tidak menutupi wajah** di sisi kanan. Periksa pemotongan gambar di layar nyata.
- Link **Contact di footer** diganti **Admin Page**, sementara diarahkan ke `/#`; **belum ada login/admin route**. Contact di header dan route lama belum diubah.

Hanya memperbaiki copy dan layout homepage serta tautan footer. Fitur admin/database masih menunggu tahap berikutnya.

## Penyempurnaan lebar judul hero
- Pada desktop, lebar judul diperluas bertahap menjadi 38rem (laptop), 49rem (desktop) dan 58rem (layar lebar). Pembatas lama 15ch dihapus agar headline tidak menumpuk pada sisi kiri.
- Ukuran H1 desktop disesuaikan sekitar 47–54px dengan lebar yang baru agar pemenggalan kata menjadi lebih seimbang.
- Deskripsi tetap lebih sempit, maksimal 34–39rem, untuk menjaga keterbacaan dan menghindari area wajah dalam foto.
- Pada layar mobile/tablet, foto tetap berada di atas teks. Periksa ulang pada browser dengan ukuran layar dan zoom berbeda sebelum merge.
