@extends('layouts.admin')

@section('title', 'Dashboard Admin | Financial Intelligence Hub')

@section('content')
    <section class="site-container py-14 sm:py-18 lg:py-24" aria-labelledby="admin-dashboard-title">
        <div class="flex flex-wrap items-start justify-between gap-8 border-b border-line pb-10">
            <div class="max-w-3xl">
                <p class="type-eyebrow text-accent">Dashboard Administrator</p>
                <h1 id="admin-dashboard-title" class="type-page-title mt-4 text-ink">Ruang Pengelolaan Konten</h1>
                <p class="type-lead mt-5 text-ink">
                    Selamat datang, <span class="font-semibold">{{ $admin->name }}</span>. Pintu masuk untuk mengelola tulisan, referensi, dan dokumentasi media.
                </p>
            </div>
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-between gap-4">
            <h2 class="type-section-title text-ink">Pilih ruang pengelolaan</h2>
            <span class="type-meta rounded-full border border-line bg-white px-4 py-2 text-ink">Fondasi admin aktif</span>
        </div>
        <p class="type-body mt-4 max-w-[80ch] text-ink">
            Login dan perlindungan akses sudah tersedia. Data Artikel, Library, dan Media kini disimpan di database; fitur unggah, penyuntingan, dan penghapusan akan menyusul pada revisi berikutnya.
        </p>

        <div class="mt-9 grid gap-5 md:grid-cols-3 lg:gap-7">
            @foreach ([
                ['number' => '01', 'key' => 'articles', 'title' => 'Artikel', 'description' => 'Kelola arsip tulisan, kategori, dan sumber publikasi asli.'],
                ['number' => '02', 'key' => 'library', 'title' => 'Library', 'description' => 'Atur pustaka referensi, karya, dan dokumen pendukung pembelajaran.'],
                ['number' => '03', 'key' => 'media', 'title' => 'Media', 'description' => 'Susun liputan, wawancara, serta dokumentasi penampilan publik.'],
            ] as $module)
                <x-ui.card :interactive="false" class="flex h-full flex-col">
                    <span class="type-meta text-accent">{{ $module['number'] }} / MODUL</span>
                    <h3 class="type-card-title mt-4 text-ink">{{ $module['title'] }}</h3>
                    <p class="type-body mt-4 text-ink">{{ $module['description'] }}</p>
                    <p class="type-meta mt-auto border-t border-line pt-6 text-slate-500">{{ number_format($moduleCounts[$module['key']] ?? 0, 0, ',', '.') }} data tersimpan · Pengelolaan segera hadir</p>
                </x-ui.card>
            @endforeach
        </div>

        <p class="type-support mt-10 text-slate-500">
            Semua halaman administrasi berikutnya akan menggunakan sistem tipografi dan autentikasi yang sama.
        </p>
    </section>
@endsection
