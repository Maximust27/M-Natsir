<div>
    <section class="border-b border-line bg-white">
        <div class="site-container py-16 sm:py-20 lg:py-24">
            <div class="max-w-3xl">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.1em] text-accent">
                    Professional Inquiries
                </p>

                <h1 class="mt-4 font-serif text-4xl font-semibold leading-[1.03] tracking-[-0.04em] text-ink sm:text-5xl lg:text-[3.35rem]">
                    Hubungi M. Natsir Kongah
                </h1>

                <p class="mt-6 max-w-[58ch] font-serif text-lg leading-8 text-slate-600 sm:text-xl sm:leading-9">
                    Pusat pertanyaan profesional untuk jurnalis, akademisi, dan konsultan kepatuhan yang mencari keahlian dalam intelijen keuangan dan kejahatan kerah putih.
                </p>
            </div>
        </div>
    </section>

    <section class="site-container py-12 sm:py-16 lg:py-20" aria-label="Form dan informasi kontak">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.45fr)_minmax(20rem,1fr)] lg:items-start">
            <article class="border border-line bg-white p-6 sm:p-8 lg:p-10">
                <div class="flex items-center gap-3">
                    <svg class="size-6 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M3.5 6.5h17v11h-17z" />
                        <path d="m4 7 8 6 8-6" />
                    </svg>
                    <h2 class="font-serif text-3xl font-medium tracking-[-0.025em] text-ink">
                        Form Komunikasi
                    </h2>
                </div>

                <form
                    class="mt-9"
                    data-endpoint="{{ $web3formsEndpoint }}"
                    x-data="{
                        submitting: false,
                        configured: {{ $mailConfigured ? 'true' : 'false' }},
                        status: '',
                        statusType: '',
                        async submit(event) {
                            if (this.submitting) return;

                            const form = event.currentTarget;

                            if (! form.reportValidity()) return;

                            this.submitting = true;
                            this.status = '';
                            this.statusType = '';

                            try {
                                const payload = Object.fromEntries(new FormData(form));
                                const response = await fetch(form.dataset.endpoint, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify(payload),
                                });
                                const result = await response.json().catch(() => ({}));

                                if (! response.ok || result.success !== true) {
                                    throw new Error('Web3Forms submission failed');
                                }

                                this.statusType = 'success';
                                this.status = 'Pesan berhasil dikirim. Terima kasih — permintaan Anda akan ditinjau sesuai prioritas dan konteksnya.';
                                form.reset();
                            } catch (error) {
                                this.statusType = 'error';
                                this.status = 'Pesan belum dapat dikirim. Silakan coba kembali beberapa saat lagi.';
                            } finally {
                                this.submitting = false;
                            }
                        }
                    }"
                    @submit.prevent="submit($event)"
                >
                    @if ($mailConfigured)
                        <input type="hidden" name="access_key" value="{{ $web3formsAccessKey }}">
                        <input type="hidden" name="subject" value="Website Contact — M. Natsir Kongah">
                    @endif

                    <input type="checkbox" name="botcheck" class="hidden" style="display: none;" tabindex="-1" autocomplete="off">

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="contact-name" class="mb-2 block font-mono text-xs font-semibold tracking-[0.04em] text-slate-600">
                                Nama Lengkap
                            </label>
                            <input
                                id="contact-name"
                                type="text"
                                name="name"
                                required
                                maxlength="120"
                                autocomplete="name"
                                class="min-h-12 w-full rounded-none border border-line bg-white px-4 py-3 text-[15px] text-ink outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                            >
                        </div>

                        <div>
                            <label for="contact-email" class="mb-2 block font-mono text-xs font-semibold tracking-[0.04em] text-slate-600">
                                Email Kerja Institusi
                            </label>
                            <input
                                id="contact-email"
                                type="email"
                                name="email"
                                required
                                maxlength="190"
                                autocomplete="email"
                                class="min-h-12 w-full rounded-none border border-line bg-white px-4 py-3 text-[15px] text-ink outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                            >
                        </div>

                        <div>
                            <label for="contact-institution" class="mb-2 block font-mono text-xs font-semibold tracking-[0.04em] text-slate-600">
                                Institusi / Media
                            </label>
                            <input
                                id="contact-institution"
                                type="text"
                                name="institution"
                                required
                                maxlength="190"
                                autocomplete="organization"
                                class="min-h-12 w-full rounded-none border border-line bg-white px-4 py-3 text-[15px] text-ink outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                            >
                        </div>

                        <div>
                            <label for="contact-purpose" class="mb-2 block font-mono text-xs font-semibold tracking-[0.04em] text-slate-600">
                                Tujuan Kontak
                            </label>
                            <select
                                id="contact-purpose"
                                name="purpose"
                                required
                                class="min-h-12 w-full rounded-none border border-line bg-white px-4 py-3 text-[15px] text-ink outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                            >
                                <option value="">Pilih Tujuan...</option>
                                @foreach ($purposes as $label)
                                    <option value="{{ $label }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="mb-2 flex items-center justify-between gap-4">
                            <label for="contact-message" class="font-mono text-xs font-semibold tracking-[0.04em] text-slate-600">
                                Pesan &amp; Tenggat Waktu (Deadline)
                            </label>
                            <span class="font-mono text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-400">
                                Opsional
                            </span>
                        </div>

                        <textarea
                            id="contact-message"
                            name="message"
                            rows="7"
                            maxlength="3000"
                            class="w-full resize-y rounded-none border border-line bg-white px-4 py-3 text-[15px] leading-7 text-ink outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                            placeholder="Jelaskan konteks pertanyaan, kebutuhan wawancara, atau tenggat waktu jika ada."
                        ></textarea>
                    </div>

                    <div class="mt-8 border-t border-line pt-6">
                        <div
                            x-show="status"
                            x-cloak
                            role="status"
                            aria-live="polite"
                            class="mb-5 border-l-2 px-4 py-3 text-sm leading-6"
                            :class="statusType === 'success'
                                ? 'border-emerald-600 bg-emerald-50 text-emerald-900'
                                : 'border-rose-600 bg-rose-50 text-rose-900'"
                        >
                            <span x-text="status"></span>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="max-w-md text-xs leading-6 text-slate-500">
                                @if ($mailConfigured)
                                    Pesan dikirim langsung melalui Web3Forms ke email tujuan yang terhubung dengan access key.
                                @else
                                    Layanan pengiriman sedang disiapkan.
                                @endif
                            </p>

                            <x-ui.button
                                type="submit"
                                variant="primary"
                                size="lg"
                                x-bind:disabled="submitting || ! configured"
                                :disabled="! $mailConfigured"
                                @class([
                                    'shrink-0',
                                    'cursor-not-allowed opacity-50' => ! $mailConfigured,
                                ])
                            >
                                <span x-show="! submitting">Kirim Pesan Terverifikasi</span>
                                <span x-show="submitting" x-cloak>Mengirim...</span>
                                <span aria-hidden="true">▷</span>
                            </x-ui.button>
                        </div>
                    </div>
                </form>
            </article>

            <aside class="grid gap-4" aria-label="Informasi kontak tambahan">
                <section class="border border-line bg-slate-100 p-6 sm:p-7">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
                        <svg class="size-6 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="m5 19 14-14M8 4l12 12M4 8l12 12" />
                        </svg>
                        <h2 class="text-xl font-semibold tracking-[-0.02em] text-ink">
                            Panduan Media
                        </h2>
                    </div>

                    <dl class="mt-5 grid gap-5">
                        <div class="grid grid-cols-[1.5rem_1fr] gap-3">
                            <svg class="mt-0.5 size-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <circle cx="12" cy="12" r="8" />
                                <path d="M12 7.5v5l3 2" />
                            </svg>
                            <div>
                                <dt class="text-sm font-semibold text-ink">Waktu Respons</dt>
                                <dd class="mt-1 text-sm leading-6 text-slate-600">24–48 jam untuk pertanyaan terverifikasi.</dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-[1.5rem_1fr] gap-3">
                            <svg class="mt-0.5 size-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <rect x="5" y="4" width="14" height="16" rx="1" />
                                <path d="M8 8h8M8 12h5M8 16h7" />
                            </svg>
                            <div>
                                <dt class="text-sm font-semibold text-ink">Spesialisasi</dt>
                                <dd class="mt-1 text-sm leading-6 text-slate-600">AML, Kejahatan Kerah Putih, Intelijen Keuangan.</dd>
                            </div>
                        </div>
                    </dl>
                </section>

                <section class="border border-line bg-white p-6 sm:p-7">
                    <div class="flex items-center gap-3">
                        <svg class="size-5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="M5 5h14v10H9l-4 4V5Z" />
                            <path d="M8 9h8M8 12h5" />
                        </svg>
                        <h2 class="text-lg font-semibold text-ink">WhatsApp</h2>
                    </div>

                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Untuk konfirmasi berita cepat atau koordinasi waktu wawancara mendesak.
                    </p>

                    <div class="mt-5">
                        @if (filled($whatsappUrl))
                            <x-ui.button
                                :href="$whatsappUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                variant="primary"
                                size="md"
                                class="w-full"
                            >
                                Mulai Chat
                                <span aria-hidden="true">↗</span>
                            </x-ui.button>
                        @else
                            <span class="inline-flex min-h-11 w-full cursor-not-allowed items-center justify-center border border-line bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-400">
                                Segera tersedia
                            </span>
                        @endif
                    </div>
                </section>

                <section class="border border-slate-900 bg-slate-950 p-6 text-white sm:p-7">
                    <div class="flex items-center gap-3">
                        <svg class="size-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="M5 4h10l4 4v12H5V4Z" />
                            <path d="M15 4v5h5M8 13h8M8 16h5" />
                        </svg>
                        <h2 class="text-lg font-semibold">Professional CV</h2>
                    </div>

                    <p class="mt-3 text-sm leading-6 text-slate-300">
                        Profil profesional, pengalaman, dan bidang keahlian M. Natsir Kongah untuk kebutuhan institusi, akademik, dan media.
                    </p>

                    <div class="mt-5">
                        @if (filled($cvUrl))
                            <x-ui.button
                                :href="$cvUrl"
                                variant="secondary"
                                size="md"
                                class="w-full"
                            >
                                Download CV
                                <span aria-hidden="true">↓</span>
                            </x-ui.button>
                        @else
                            <span class="inline-flex min-h-11 w-full cursor-not-allowed items-center justify-center border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-400">
                                CV segera tersedia
                            </span>
                        @endif
                    </div>
                </section>
            </aside>
        </div>
    </section>
</div>
