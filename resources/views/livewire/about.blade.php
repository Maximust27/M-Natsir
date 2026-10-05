<div>
    <section class="site-container py-16 sm:py-20 lg:py-28" aria-labelledby="about-title">
        <div class="mx-auto grid w-full max-w-5xl items-center gap-12 lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-20">
            <figure class="mx-auto w-full max-w-80 lg:mx-0">
                <div class="overflow-hidden border border-line bg-slate-100">
                    <img
                        src="{{ asset('images/about-hero.jpeg') }}"
                        alt="Potret M. Natsir Kongah"
                        width="1200"
                        height="675"
                        class="aspect-[3/4] h-auto w-full object-cover object-[82%_center]"
                    >
                </div>
            </figure>

            <div class="max-w-4xl">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.1em] text-accent">Tentang</p>

                <h1
                    id="about-title"
                    class="mt-4 font-serif text-4xl font-semibold leading-[1.05] tracking-[-0.04em] text-ink sm:text-5xl lg:text-[3.5rem]"
                >
                    M. NATSIR KONGAH
                </h1>

                <p class="mt-5 max-w-3xl text-lg font-semibold leading-8 text-slate-700 sm:text-xl">
                    Pembelajar Anti Pencucian Uang &amp; Pengamat Kejahatan Keuangan
                </p>

                <div class="mt-5 inline-flex items-center gap-2 text-sm text-slate-500">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                    Jakarta, Indonesia
                </div>

                <div class="mt-7 flex flex-wrap gap-x-6 gap-y-3">
                    @foreach ($profiles as $profile)
                        <a
                            href="{{ $profile['url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-10 items-center gap-2 rounded-sm text-sm font-semibold text-ink transition hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"
                        >
                            {{ $profile['label'] }}
                            <span aria-hidden="true">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="site-container">
        <div class="border border-line bg-slate-50 px-6 py-5 sm:px-7">
            <div class="flex gap-4">
                <div class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full bg-ink text-sm font-semibold text-white" aria-hidden="true">i</div>
                <div>
                    <h2 class="font-mono text-xs font-semibold uppercase tracking-[0.08em] text-ink">
                        Catatan afiliasi &amp; kredibilitas
                    </h2>
                    <p class="mt-2 max-w-[95ch] text-sm leading-7 text-slate-600">
                        Halaman ini adalah profil personal berbasis sumber publik. Riwayat afiliasi dan aktivitas profesional diringkas secara konservatif dari profil LinkedIn, dokumen PPATK/PPID, rekam pengajaran, serta tulisan yang dipublikasikan di media. Analisis dan opini pada situs ini diposisikan sebagai pandangan personal, bukan pernyataan resmi institusi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="site-container py-16 lg:py-24" aria-labelledby="journey-heading">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1.75fr)_minmax(18rem,0.8fr)] lg:gap-16 xl:gap-24">
            <article>
                <h2 id="journey-heading" class="font-serif text-[2rem] font-medium leading-tight tracking-[-0.025em] text-ink sm:text-[2.35rem]">
                    Perjalanan Intelektual &amp; Advokasi Publik
                </h2>

                <div class="mt-7 max-w-[78ch] space-y-6 text-base leading-8 text-slate-600">
                    <p>
                        Rekam publik M. Natsir Kongah menunjukkan keterlibatan lebih dari dua dekade pada isu anti pencucian uang, intelijen keuangan, komunikasi publik, dan pendidikan. Profil profesionalnya mencatat aktivitas di Indonesian Financial Intelligence Unit sejak 2002, disusul peran kehumasan PPATK dan pengajaran anti pencucian uang sejak 2003.
                    </p>

                    <p>
                        Dalam lingkungan PPATK, jejak publiknya banyak beririsan dengan hubungan masyarakat dan keterbukaan informasi. Dokumen serta publikasi resmi menempatkannya dalam berbagai fungsi kehumasan, layanan informasi publik, komunikasi media, dan penyuntingan publikasi yang membantu menerjemahkan isu teknis kejahatan keuangan ke ruang publik.
                    </p>

                    <p>
                        Tulisan-tulisan publik pada 2026 memperlihatkan tema yang semakin luas: professional money laundering dan peran gatekeepers, kecepatan perpindahan nilai melalui sistem keuangan, perlindungan hak dalam asset recovery, pemanfaatan aset kripto, scam laundering, serta pentingnya pengawasan berbasis risiko.
                    </p>

                    <div class="border-l-2 border-accent bg-stone-50 px-6 py-5">
                        <p class="font-serif text-lg italic leading-8 text-ink">
                            Benang merah yang tampak dari tulisan-tulisannya: pencucian uang tidak cukup dibaca sebagai perpindahan dana antar-rekening, tetapi sebagai arsitektur yang melibatkan manusia, profesi, dokumen, badan usaha, teknologi, aset, dan yurisdiksi.
                        </p>
                        <p class="mt-3 font-mono text-[11px] font-semibold uppercase tracking-[0.06em] text-slate-500">
                            Ringkasan tema tulisan publik — bukan kutipan langsung
                        </p>
                    </div>

                    <p>
                        Situs ini dikembangkan sebagai repositori pembelajaran: ruang untuk mengarsipkan analisis, tipologi, sumber, dan catatan yang membantu pembaca memahami bagaimana kejahatan keuangan berubah—serta bagaimana kebijakan, profesi, teknologi, dan penegakan hukum perlu beradaptasi tanpa mengabaikan kepastian hukum.
                    </p>
                </div>
            </article>

            <aside class="space-y-10">
                <section aria-labelledby="focus-heading">
                    <h2 id="focus-heading" class="text-lg font-semibold tracking-[-0.02em] text-ink">
                        Fokus Kajian Utama
                    </h2>

                    <div class="mt-5 grid gap-4">
                        @foreach ($focusAreas as $focus)
                            <x-ui.card :interactive="false" class="p-5 sm:p-5">
                                <span class="inline-flex rounded-full px-3 py-1.5 font-mono text-[11px] font-semibold leading-none tracking-[0.03em] {{ $focus['classes'] }}">
                                    {{ $focus['label'] }}
                                </span>

                                <h3 class="mt-4 text-[15px] font-semibold leading-6 text-ink">
                                    {{ $focus['title'] }}
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    {{ $focus['description'] }}
                                </p>
                            </x-ui.card>
                        @endforeach
                    </div>
                </section>

                <section aria-labelledby="contribution-heading">
                    <h2 id="contribution-heading" class="text-lg font-semibold tracking-[-0.02em] text-ink">
                        Kontribusi Publik
                    </h2>

                    <div class="mt-5 grid gap-5">
                        @foreach ($publicContributions as $contribution)
                            <div class="border-l border-line pl-4">
                                <h3 class="text-sm font-semibold leading-6 text-ink">
                                    {{ $contribution['title'] }}
                                </h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    {{ $contribution['description'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </section>

    <section class="site-container border-t border-line py-14 lg:py-18" aria-labelledby="sources-heading">
        <div class="grid gap-8 lg:grid-cols-[18rem_minmax(0,1fr)] lg:gap-16">
            <div>
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.08em] text-accent">Transparansi sumber</p>
                <h2 id="sources-heading" class="mt-3 font-serif text-3xl font-medium tracking-[-0.025em] text-ink">
                    Rujukan profil publik
                </h2>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    Tautan berikut menjadi basis verifikasi utama untuk informasi yang ditampilkan pada halaman ini.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($sourceNotes as $source)
                    <a
                        href="{{ $source['url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group border border-line bg-white p-5 transition hover:border-slate-400 hover:shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"
                    >
                        <span class="font-mono text-[11px] font-semibold uppercase tracking-[0.06em] text-slate-500">
                            {{ $source['source'] }}
                        </span>
                        <span class="mt-2 block text-sm font-semibold leading-6 text-ink group-hover:text-accent">
                            {{ $source['title'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="site-container pb-16 lg:pb-24">
        <div class="flex flex-col gap-7 bg-ink px-7 py-9 text-white sm:px-10 lg:flex-row lg:items-center lg:justify-between lg:px-12">
            <div>
                <h2 class="font-serif text-3xl font-medium tracking-[-0.025em]">Keperluan Akademik &amp; Media?</h2>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                    Untuk kebutuhan wawancara, diskusi akademik, atau pengenalan narasumber, gunakan Media Hub agar permintaan dapat diarahkan dengan konteks yang tepat.
                </p>
            </div>

            <x-ui.button :href="route('home') . '#contact'" variant="secondary" size="lg" class="shrink-0">
                Buka Contact &amp; Media Hub
                <span aria-hidden="true">→</span>
            </x-ui.button>
        </div>
    </section>
</div>
