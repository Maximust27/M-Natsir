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

Hanya memperbaiki tipografi dan logo/urutan branding header. **Headline hero tetap “M. NATSIR KONGAH”**; penggantian headline dan fitur admin bisa dikerjakan di revisi berikutnya setelah desain ini disetujui.
