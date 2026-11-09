@extends('layouts.admin')

@section('title', 'Masuk Admin | Financial Intelligence Hub')

@section('content')
    <section class="site-container grid items-center gap-12 py-14 sm:py-20 lg:grid-cols-[minmax(0,1fr)_minmax(24rem,0.85fr)] lg:gap-20 lg:py-28" aria-labelledby="admin-login-title">
        <div class="max-w-2xl">
            <p class="type-eyebrow text-accent">Akses Terbatas</p>
            <h1 id="admin-login-title" class="type-page-title mt-5 max-w-[15ch] text-ink">
                Selamat datang di ruang admin.
            </h1>
            <p class="type-lead mt-7 max-w-[54ch] text-ink">
                Kelola pengetahuan dan publikasi Financial Intelligence Hub melalui satu tempat yang aman, tertata, dan mudah digunakan.
            </p>
            <div class="mt-9 flex items-start gap-4 border-l-2 border-accent bg-white px-5 py-5 sm:px-6">
                <span class="font-serif text-[2rem] leading-none text-accent" aria-hidden="true">“</span>
                <p class="type-support text-ink">
                    Halaman ini khusus pengelola yang memiliki akses resmi. Tidak tersedia pendaftaran akun publik.
                </p>
            </div>
        </div>

        <div class="border border-line bg-white p-6 shadow-[0_24px_64px_-48px_rgba(14,28,47,0.4)] sm:p-9 lg:p-10">
            <div class="mb-8">
                <p class="type-eyebrow text-accent">Login Administrator</p>
                <h2 class="type-card-title mt-3 text-ink">Masuk ke panel</h2>
                <p class="type-support mt-3 text-slate-500">Gunakan email dan kata sandi akun admin.</p>
            </div>

            <form method="POST" action="{{ route('admin.authenticate') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="admin-email" class="type-support mb-2 block font-semibold text-ink">Alamat email</label>
                    <input
                        id="admin-email"
                        name="email"
                        type="email"
                        inputmode="email"
                        autocomplete="username"
                        value="{{ old('email') }}"
                        placeholder="admin@domain.com"
                        required
                        autofocus
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        @if ($errors->has('email')) aria-describedby="admin-login-error" @endif
                        class="type-form-control min-h-13 w-full rounded-sm border border-line bg-paper px-4 py-3 text-ink outline-none transition placeholder:text-slate-500 hover:border-slate-400 focus:border-ink focus:ring-2 focus:ring-ink/15"
                    >
                    @error('email')
                        <p id="admin-login-error" class="type-support mt-2 font-medium text-red-800" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="admin-password" class="type-support mb-2 block font-semibold text-ink">Kata sandi</label>
                    <input
                        id="admin-password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        placeholder="Masukkan kata sandi"
                        required
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        @if ($errors->has('password')) aria-describedby="admin-password-error" @endif
                        class="type-form-control min-h-13 w-full rounded-sm border border-line bg-paper px-4 py-3 text-ink outline-none transition placeholder:text-slate-500 hover:border-slate-400 focus:border-ink focus:ring-2 focus:ring-ink/15"
                    >
                    @error('password')
                        <p id="admin-password-error" class="type-support mt-2 font-medium text-red-800" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                    Masuk ke Admin
                    <span aria-hidden="true">→</span>
                </x-ui.button>

                <p class="type-meta text-center text-slate-500">Akses khusus administrator · Tidak ada registrasi</p>
            </form>
        </div>
    </section>
@endsection
