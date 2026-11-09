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
            <span class="type-meta rounded-full border border-line bg-white px-4 py-2 text-ink">Form tambah konten</span>
        </div>
        <p class="type-body mt-4 max-w-[80ch] text-ink">
            Pilih salah satu dari tiga kartu di bawah. Form tambah akan muncul sesuai jenis konten, dengan pilihan menyimpan sebagai draf atau langsung memublikasikannya.
        </p>

        <livewire:admin.content-entry />

    </section>
@endsection
