<div class="mt-9">
    <div class="grid gap-5 md:grid-cols-3 lg:gap-7" role="group" aria-label="Pilih jenis konten">
        @foreach ($modules as $key => $module)
            <button
                type="button"
                wire:key="admin-module-{{ $key }}"
                wire:click="selectModule('{{ $key }}')"
                aria-pressed="{{ $selected === $key ? 'true' : 'false' }}"
                aria-controls="admin-content-form"
                class="flex h-full min-h-64 w-full flex-col border bg-white p-6 text-left transition duration-200 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink sm:p-8 {{ $selected === $key ? 'border-accent ring-2 ring-accent/20' : 'border-line hover:border-slate-400 hover:shadow-sm' }}"
            >
                <span class="type-meta text-accent">{{ $module['number'] }} / MODUL</span>
                <span class="type-card-title mt-4 block text-ink">{{ $module['title'] }}</span>
                <span class="type-body mt-3 block text-ink">{{ $module['description'] }}</span>
                <span class="type-meta mt-auto block w-full border-t border-line pt-5 text-slate-500">
                    {{ number_format($counts[$key]['published'], 0, ',', '.') }} terbit · {{ number_format($counts[$key]['draft'], 0, ',', '.') }} draf
                    @if ($counts[$key]['trash'] > 0)
                        · {{ $counts[$key]['trash'] }} di Sampah
                    @endif
                </span>
                <span class="type-action mt-4 inline-flex items-center gap-2 font-semibold text-accent">
                    {{ $selected === $key ? 'Modul dipilih' : 'Kelola '. $module['title'] }}
                    <span aria-hidden="true">→</span>
                </span>
            </button>
        @endforeach
    </div>

    @if ($notice)
        <div role="status" aria-live="polite" class="type-body mt-6 border-l-2 border-teal-700 bg-teal-50 px-5 py-4 text-ink">
            {{ $notice }}
        </div>
    @endif

    <section id="admin-content-form" class="mt-9 scroll-mt-12 border border-line bg-white p-6 sm:p-8 lg:p-10" aria-labelledby="admin-entry-heading">
        @if ($selected === '')
            <p class="type-eyebrow text-accent">Langkah 01</p>
            <h3 id="admin-entry-heading" class="type-section-title mt-3 text-ink">Pilih kartu untuk mulai</h3>
            <p class="type-body mt-4 text-slate-500">Klik Artikel, Library, atau Media. Kolom formulir akan mengikuti data yang dibutuhkan halaman publik tersebut.</p>
        @else
            @php
                $inputClass = 'type-form-control min-h-12 w-full rounded-sm border border-line bg-paper px-4 py-3 text-ink outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:bg-white focus:ring-2 focus:ring-slate-200';
                $labelClass = 'type-body mb-2 block font-semibold text-ink';
                $errorClass = 'type-support mt-2 text-rose-700';
            @endphp

            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-line pb-6">
                <div class="max-w-3xl">
                    <p class="type-eyebrow text-accent">{{ $editingId ? 'Form edit' : 'Form tambah' }} konten · {{ $modules[$selected]['number'] }}</p>
                    <h3 id="admin-entry-heading" class="type-section-title mt-3 text-ink">{{ $editingId ? 'Edit' : 'Tambah' }} {{ $modules[$selected]['title'] }}</h3>
                    <p class="type-body mt-3 text-slate-500">Isi kolom bertanda *. Kode unik, ID, dan urutan dikelola otomatis. Konten berstatus draf tidak ditampilkan di website.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.badge variant="neutral">{{ $editingId ? 'Mode Edit' : 'Mode Tambah' }}</x-ui.badge>
                    @if ($editingId)
                        <x-ui.button type="button" variant="secondary" size="sm" wire:click="cancelEdit">Batal edit</x-ui.button>
                    @endif
                </div>
            </div>

            <form wire:submit="save" wire:key="admin-entry-{{ $selected }}-{{ $editingId ?? 'new' }}" class="mt-8 space-y-6">
                <div>
                    <label for="entry-title" class="{{ $labelClass }}">Judul *</label>
                    <input id="entry-title" type="text" wire:model="entry.title" class="{{ $inputClass }}" placeholder="Masukkan judul yang sesuai sumber" maxlength="500" required>
                    @error('entry.title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                @if ($selected === 'articles')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="article-category" class="{{ $labelClass }}">Kategori artikel *</label>
                            <select id="article-category" wire:model="entry.category" class="{{ $inputClass }}">
                                @foreach ($articleCategories as $key => $category)
                                    <option value="{{ $key }}">{{ $category['label'] }}</option>
                                @endforeach
                            </select>
                            @error('entry.category') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="article-date" class="{{ $labelClass }}">Tanggal publikasi {{ $editingId ? '(ubah bila perlu)' : '*' }}</label>
                            <input id="article-date" type="date" wire:model="entry.date" class="{{ $inputClass }}" @required(! $editingId)>
                            @if ($editingRecord)
                                <p class="type-support mt-2 text-slate-500">Tanggal sebelumnya: {{ $editingRecord->date_label }}. Kosongkan untuk mempertahankannya.</p>
                            @endif
                            @error('entry.date') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="article-source" class="{{ $labelClass }}">Media / sumber publikasi *</label>
                        <input id="article-source" type="text" wire:model="entry.source" class="{{ $inputClass }}" placeholder="Contoh: Majalah Editor, Kompas, Kumparan" maxlength="255" required>
                        @error('entry.source') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="article-excerpt" class="{{ $labelClass }}">Ringkasan artikel *</label>
                        <textarea id="article-excerpt" wire:model="entry.excerpt" rows="5" class="{{ $inputClass }}" placeholder="Ringkasan singkat; jangan salin seluruh tulisan dari media lain." required></textarea>
                        @error('entry.excerpt') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <label class="type-body flex cursor-pointer items-start gap-3 text-ink">
                        <input type="checkbox" wire:model="entry.is_primary_source" class="mt-1 size-5 shrink-0 accent-slate-900">
                        <span>Link menuju publikasi asli <span class="type-support block text-slate-500">Nonaktifkan jika tautan hanya merupakan referensi atau jejak bibliografi.</span></span>
                    </label>
                    @error('entry.is_primary_source') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror

                @elseif ($selected === 'library')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="library-collection" class="{{ $labelClass }}">Koleksi *</label>
                            <select id="library-collection" wire:model="entry.collection" class="{{ $inputClass }}">
                                @foreach ($libraryCollections as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('entry.collection') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="library-type" class="{{ $labelClass }}">Jenis dokumen *</label>
                            <select id="library-type" wire:model="entry.type" class="{{ $inputClass }}">
                                @foreach ($libraryTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('entry.type') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="library-institution" class="{{ $labelClass }}">Institusi / sumber *</label>
                            <select id="library-institution" wire:model.live="entry.institution" class="{{ $inputClass }}">
                                @foreach ($libraryInstitutions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('entry.institution') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="library-year" class="{{ $labelClass }}">Tahun *</label>
                            <input id="library-year" type="text" inputmode="numeric" wire:model="entry.year" class="{{ $inputClass }}" placeholder="2026 atau Arsip" maxlength="5" required>
                            @error('entry.year') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    @if (($entry['institution'] ?? '') === 'other')
                        <div>
                            <label for="library-institution-other" class="{{ $labelClass }}">Nama institusi lainnya *</label>
                            <input id="library-institution-other" wire:model="entry.institution_other" type="text" class="{{ $inputClass }}" placeholder="Contoh: Universitas Indonesia" maxlength="255" required>
                            @error('entry.institution_other') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="library-author" class="{{ $labelClass }}">Penulis (opsional)</label>
                            <input id="library-author" type="text" wire:model="entry.author" class="{{ $inputClass }}" placeholder="Nama penulis" maxlength="255">
                            @error('entry.author') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="library-role" class="{{ $labelClass }}">Peran / kontribusi (opsional)</label>
                            <input id="library-role" type="text" wire:model="entry.role" class="{{ $inputClass }}" placeholder="Contoh: Kontributor" maxlength="255">
                            @error('entry.role') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <fieldset class="border border-line bg-paper p-5">
                        <legend class="type-body px-2 font-semibold text-ink">Topik referensi * <span class="type-support font-normal text-slate-500">(boleh lebih dari satu)</span></legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($libraryTopics as $value => $label)
                                <label wire:key="library-topic-{{ $value }}" class="type-support flex cursor-pointer items-center gap-3 text-ink">
                                    <input type="checkbox" value="{{ $value }}" wire:model="entry.topics" class="size-4 shrink-0 accent-slate-900">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('entry.topics') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        @error('entry.topics.*') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </fieldset>
                    <div>
                        <label for="library-language" class="{{ $labelClass }}">Bahasa dokumen *</label>
                        <select id="library-language" wire:model="entry.language" class="{{ $inputClass }}">
                            <option value="ID">Indonesia</option>
                            <option value="EN">Inggris</option>
                            <option value="EN / ID">Inggris / Indonesia</option>
                        </select>
                        @error('entry.language') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="library-summary" class="{{ $labelClass }}">Ringkasan dokumen *</label>
                        <textarea id="library-summary" wire:model="entry.summary" rows="5" class="{{ $inputClass }}" placeholder="Apa yang dibahas dalam dokumen ini?" required></textarea>
                        @error('entry.summary') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                @elseif ($selected === 'media')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="media-category" class="{{ $labelClass }}">Jenis penampilan *</label>
                            <select id="media-category" wire:model="entry.category" class="{{ $inputClass }}">
                                @foreach ($mediaCategories as $value => $category)
                                    <option value="{{ $value }}">{{ $category['label'] }}</option>
                                @endforeach
                                @if ($editingRecord && ! isset($mediaCategories[$editingRecord->category]))
                                    <option value="{{ $editingRecord->category }}">Arsip kategori lama: {{ $editingRecord->category }}</option>
                                @endif
                            </select>
                            @error('entry.category') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="media-date" class="{{ $labelClass }}">Tanggal penampilan {{ $editingId ? '(ubah bila perlu)' : '*' }}</label>
                            <input id="media-date" type="date" wire:model="entry.date" class="{{ $inputClass }}" @required(! $editingId)>
                            @if ($editingRecord)
                                <p class="type-support mt-2 text-slate-500">Tanggal sebelumnya: {{ $editingRecord->date_label }}. Kosongkan untuk mempertahankannya.</p>
                            @endif
                            @error('entry.date') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="media-outlet" class="{{ $labelClass }}">Media / outlet *</label>
                            <input id="media-outlet" type="text" wire:model="entry.outlet" class="{{ $inputClass }}" placeholder="Contoh: RRI, ANTARA, PPATK" maxlength="255" required>
                            @error('entry.outlet') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="media-role" class="{{ $labelClass }}">Peran dalam liputan *</label>
                            <input id="media-role" type="text" wire:model="entry.role" class="{{ $inputClass }}" placeholder="Contoh: Narasumber wawancara, pemateri" maxlength="255" required>
                            @error('entry.role') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="media-summary" class="{{ $labelClass }}">Ringkasan kasus / pembahasan *</label>
                        <textarea id="media-summary" wire:model="entry.summary" rows="5" class="{{ $inputClass }}" placeholder="Fokus pada substansi kasus atau penanganan, bukan kegiatan kehumasan." required></textarea>
                        @error('entry.summary') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="media-topics" class="{{ $labelClass }}">Topik (opsional)</label>
                        <input id="media-topics" type="text" wire:model="entry.topics_text" class="{{ $inputClass }}" placeholder="Contoh: Judi Online, TPPU, Penanganan Kasus" maxlength="500">
                        <p class="type-support mt-2 text-slate-500">Pisahkan beberapa topik dengan koma. Sistem akan menyimpannya sebagai tag otomatis.</p>
                        @error('entry.topics_text') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div class="border border-line bg-paper p-5">
                        <h4 class="type-subheading text-ink">Foto penampilan (opsional)</h4>
                        @if ($editingRecord && $editingRecord->thumbnail_url)
                            <p class="type-support mt-2 text-slate-500">
                                Foto saat ini:
                                @if (str_starts_with($editingRecord->thumbnail_url, 'https://') || str_starts_with($editingRecord->thumbnail_url, 'http://') || str_starts_with($editingRecord->thumbnail_url, '/storage/'))
                                    <a class="underline underline-offset-2 hover:text-accent" href="{{ $editingRecord->thumbnail_url }}" target="_blank" rel="noopener noreferrer">Lihat foto yang tersimpan</a>
                                @else
                                    Tautan gambar belum valid
                                @endif
                            </p>
                            <label class="type-support mt-3 flex items-center gap-3 text-ink">
                                <input type="checkbox" wire:model="removeThumbnail" class="size-4 shrink-0 accent-slate-900">
                                Hapus foto saat menyimpan perubahan
                            </label>
                        @endif
                        <p class="type-support mt-2 text-slate-500">Pilih file gambar maksimal 2 MB atau isi URL foto. Jika keduanya ada, file unggahan dipakai.</p>
                        <div class="mt-4 grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="media-thumbnail-upload" class="{{ $labelClass }}">Unggah foto</label>
                                <input id="media-thumbnail-upload" type="file" wire:model="thumbnail" accept="image/jpeg,image/png,image/webp" class="{{ $inputClass }} file:mr-3 file:border-0 file:bg-transparent file:font-semibold">
                                <p wire:loading wire:target="thumbnail" class="type-support mt-2 text-slate-500">Mengunggah foto…</p>
                                @error('thumbnail') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="media-thumbnail-url" class="{{ $labelClass }}">Atau URL foto</label>
                                <input id="media-thumbnail-url" type="url" wire:model="entry.thumbnail_url" class="{{ $inputClass }}" placeholder="https://...">
                                @if ($editingRecord)
                                    <p class="type-support mt-2 text-slate-500">Jika dibiarkan kosong, foto lama tetap digunakan kecuali opsi hapus dicentang.</p>
                                @endif
                                @error('entry.thumbnail_url') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="entry-url" class="{{ $labelClass }}">
                        {{ $selected === 'articles' ? 'Tautan publikasi asli *' : ($selected === 'library' ? 'Tautan dokumen / sumber *' : 'Tautan liputan / video asli *') }}
                    </label>
                    <input id="entry-url" type="url" wire:model="entry.url" class="{{ $inputClass }}" placeholder="https://..." required>
                    <p class="type-support mt-2 text-slate-500">Gunakan URL sumber resmi / publikasi yang dapat diperiksa, bukan tautan tanpa sumber.</p>
                    @error('entry.url') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <details class="border-t border-line pt-6">
                    <summary class="type-action cursor-pointer font-semibold text-ink">Catatan verifikasi sumber (opsional)</summary>
                    <div class="mt-4">
                        <label for="entry-source-note" class="{{ $labelClass }}">Catatan sumber / atribusi</label>
                        <textarea id="entry-source-note" wire:model="entry.source_note" rows="3" class="{{ $inputClass }}" placeholder="Contoh: Sumber primer, tanggal pemeriksaan, atau catatan verifikasi."></textarea>
                        @error('entry.source_note') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </details>

                <div class="border border-line bg-paper p-5">
                    <label class="type-body flex cursor-pointer items-start gap-3 font-semibold text-ink">
                        <input type="checkbox" wire:model="entry.is_published" class="mt-1 size-5 shrink-0 accent-slate-900">
                        <span>{{ $editingId ? 'Status terbit' : 'Publikasikan langsung' }}
                            <span class="type-support mt-1 block font-normal text-slate-500">Jika tidak dicentang, data disimpan sebagai draf dan belum muncul di halaman publik.</span>
                        </span>
                    </label>
                    @error('entry.is_published') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-line pt-6">
                    <p class="type-support text-slate-500">Periksa judul, ringkasan, serta URL sumber sebelum menerbitkan.</p>
                    <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save,thumbnail">
                        {{ $editingId ? 'Simpan Perubahan' : 'Simpan '.$modules[$selected]['title'] }}
                        <span aria-hidden="true">→</span>
                    </x-ui.button>
                </div>
            </form>
        @endif
    </section>
</div>
