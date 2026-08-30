@php
    $mapContent = fn ($c) => [
        'id' => $c->id,
        'title' => $c->title,
        'type' => $c->type,
        'type_label' => $c->type_label,
        'duration' => $c->duration,
        'file_url' => $c->file_url,
        'text_body' => $c->text_body,
        'background_hex' => $c->background_hex,
        'is_priority' => $c->is_priority,
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">Kelola Playlist: {{ $playlist->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                <h3 class="font-semibold text-ink mb-4">Nama Playlist</h3>
                <form method="POST" action="{{ route('admin.playlists.update', $playlist) }}" class="flex items-end gap-3">
                    @csrf
                    @method('PUT')
                    <div class="flex-1">
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $playlist->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <x-secondary-button type="submit">Simpan Nama</x-secondary-button>
                </form>
            </div>

            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6"
                x-data="playlistEditor({
                    assigned: {{ Illuminate\Support\Js::from($playlist->contents->map($mapContent)) }},
                    available: {{ Illuminate\Support\Js::from($availableContents->map($mapContent)) }},
                })">
                <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="font-semibold text-ink mb-1">Susun Konten Playlist</h3>
                        <p class="text-sm text-muted">
                            Tarik baris untuk mengubah urutan, atau gunakan tombol naik/turun.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm text-muted whitespace-nowrap">
                            Perkiraan durasi: <span class="font-medium text-ink" x-text="totalDurationLabel"></span>
                        </span>
                        <x-secondary-button type="button" x-on:click="openPreview()" x-bind:disabled="assigned.length === 0">
                            Pratinjau
                        </x-secondary-button>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.playlists.contents.update', $playlist) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-muted mb-2">Konten Terpilih (urutan tayang)</h4>
                            <ul class="border border-border rounded-md divide-y divide-border min-h-[3rem]">
                                <template x-for="(item, index) in assigned" :key="item.id">
                                    <li draggable="true"
                                        x-on:dragstart="onDragStart(index, $event)"
                                        x-on:dragover.prevent="onDragOver(index)"
                                        x-on:drop.prevent="onDrop(index)"
                                        x-on:dragend="onDragEnd()"
                                        class="flex items-center gap-2 px-3 py-2 text-sm cursor-grab active:cursor-grabbing transition-colors duration-fast"
                                        :class="{
                                            'opacity-40': dragIndex === index,
                                            'bg-primary/10': dragOverIndex === index && dragIndex !== null && dragIndex !== index,
                                        }">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 shrink-0 text-muted">
                                            <path d="M9 5.25a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm-9 6.75a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm-9 6.75a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                        </svg>

                                        <span class="text-muted w-5 shrink-0 tabular-nums" x-text="index + 1 + '.'"></span>

                                        <div class="w-12 h-9 shrink-0 rounded border border-border overflow-hidden bg-background flex items-center justify-center">
                                            <template x-if="item.type === 'image' && item.file_url">
                                                <img :src="item.file_url" alt="" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!(item.type === 'image' && item.file_url)">
                                                <span class="text-[10px] font-semibold uppercase text-muted" x-text="shortType(item.type)"></span>
                                            </template>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="truncate text-ink" x-text="item.title"></div>
                                            <div class="text-xs text-muted">
                                                <span x-text="item.type_label"></span>
                                                &middot; <span x-text="item.duration + 's'"></span>
                                            </div>
                                        </div>

                                        <span class="flex items-center gap-2 shrink-0">
                                            <button type="button" class="text-muted hover:text-ink disabled:opacity-25"
                                                x-on:click="moveUp(index)" :disabled="index === 0" aria-label="Naikkan urutan">&uarr;</button>
                                            <button type="button" class="text-muted hover:text-ink disabled:opacity-25"
                                                x-on:click="moveDown(index)" :disabled="index === assigned.length - 1" aria-label="Turunkan urutan">&darr;</button>
                                            <button type="button" class="text-danger hover:underline" x-on:click="unassign(index)">Hapus</button>
                                        </span>
                                    </li>
                                </template>
                                <li x-show="assigned.length === 0" x-cloak class="px-3 py-4 text-center text-muted text-sm">
                                    Belum ada konten dipilih.
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-muted mb-2">Konten Tersedia</h4>
                            <ul class="border border-border rounded-md divide-y divide-border min-h-[3rem]">
                                <template x-for="(item, index) in available" :key="item.id">
                                    <li class="flex items-center gap-2 px-3 py-2 text-sm">
                                        <div class="w-12 h-9 shrink-0 rounded border border-border overflow-hidden bg-background flex items-center justify-center">
                                            <template x-if="item.type === 'image' && item.file_url">
                                                <img :src="item.file_url" alt="" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!(item.type === 'image' && item.file_url)">
                                                <span class="text-[10px] font-semibold uppercase text-muted" x-text="shortType(item.type)"></span>
                                            </template>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="truncate text-ink" x-text="item.title"></div>
                                            <div class="text-xs text-muted">
                                                <span x-text="item.type_label"></span>
                                                &middot; <span x-text="item.duration + 's'"></span>
                                            </div>
                                        </div>

                                        <button type="button" class="text-primary hover:underline shrink-0" x-on:click="assign(index)">+ Tambah</button>
                                    </li>
                                </template>
                                <li x-show="available.length === 0" x-cloak class="px-3 py-4 text-center text-muted text-sm">
                                    Semua konten sudah dimasukkan ke playlist ini.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <template x-for="item in assigned" :key="'input-' + item.id">
                        <input type="hidden" name="content_ids[]" :value="item.id">
                    </template>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button type="submit">Simpan Urutan Konten</x-primary-button>
                    </div>
                </form>

                <!-- Modal pratinjau -->
                <div x-show="previewOpen" x-cloak
                    x-on:keydown.escape.window="closePreview()"
                    x-on:click.self="closePreview()"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/70"
                    x-transition:enter="transition ease-out duration-base"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-fast"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="w-full max-w-4xl bg-surface rounded-lg shadow-elevated overflow-hidden">
                        <div class="flex items-start justify-between gap-4 px-5 py-3 border-b border-border">
                            <div>
                                <h4 class="font-semibold text-ink">Pratinjau Playlist</h4>
                                <p class="text-xs text-muted">Menampilkan susunan saat ini, termasuk perubahan yang belum disimpan.</p>
                            </div>
                            <button type="button" x-on:click="closePreview()" aria-label="Tutup pratinjau"
                                class="shrink-0 text-muted hover:text-primary transition-colors duration-fast rounded-md p-1 focus:outline-none focus-visible:shadow-focus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="relative bg-background aspect-[16/9] overflow-hidden">
                            <template x-for="n in [previewIndex]" :key="'preview-' + previewIndex">
                                <div class="absolute inset-0 transition-opacity duration-base"
                                    :class="previewFading ? 'opacity-0' : 'opacity-100'">
                                    <template x-if="previewItem && previewItem.type === 'image'">
                                        <img :src="previewItem.file_url" :alt="previewItem.title" class="w-full h-full object-contain">
                                    </template>

                                    <template x-if="previewItem && previewItem.type === 'video'">
                                        <video :src="previewItem.file_url" class="w-full h-full object-contain"
                                            autoplay muted playsinline x-on:ended="advancePreview()"></video>
                                    </template>

                                    <template x-if="previewItem && previewItem.type === 'text'">
                                        <div class="w-full h-full flex items-center justify-center p-8"
                                            :style="'background-color: ' + previewItem.background_hex">
                                            <p class="text-2xl sm:text-3xl font-bold text-on-primary text-center whitespace-pre-line"
                                                x-text="previewItem.text_body"></p>
                                        </div>
                                    </template>

                                    <template x-if="previewItem && previewItem.type === 'html-embed'">
                                        <div class="w-full h-full overflow-hidden bg-surface text-ink" x-html="previewItem.text_body"></div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="previewItem && previewItem.is_priority">
                                <div class="absolute top-0 left-0 right-0 bg-danger text-on-primary text-center py-1.5 text-sm font-bold tracking-wide">
                                    &#9888; PENGUMUMAN PRIORITAS
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 border-t border-border text-sm">
                            <span class="text-muted">
                                Slide <span class="font-medium text-ink" x-text="previewIndex + 1"></span>
                                dari <span class="font-medium text-ink" x-text="assigned.length"></span>
                                <template x-if="previewItem">
                                    <span>&middot; <span class="text-ink" x-text="previewItem.title"></span></span>
                                </template>
                            </span>

                            <span class="flex items-center gap-2">
                                <button type="button" x-on:click="prevPreview()" class="text-primary hover:underline">&larr; Sebelumnya</button>
                                <button type="button" x-on:click="advancePreview()" class="text-primary hover:underline">Berikutnya &rarr;</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.playlists.index') }}" class="text-sm text-muted hover:underline">&larr; Kembali ke daftar playlist</a>
        </div>
    </div>

    @push('scripts')
    <script>
        function playlistEditor({ assigned, available }) {
            return {
                assigned,
                available,
                dragIndex: null,
                dragOverIndex: null,
                previewOpen: false,
                previewIndex: 0,
                previewFading: false,
                previewTimer: null,
                previewFadeTimer: null,

                shortType(type) {
                    return { video: 'VID', text: 'TEKS', 'html-embed': 'HTML' }[type] ?? 'IMG';
                },

                get totalDurationLabel() {
                    const total = this.assigned.reduce((sum, item) => sum + (parseInt(item.duration, 10) || 0), 0);
                    if (total < 60) return total + ' detik';

                    const minutes = Math.floor(total / 60);
                    const seconds = total % 60;

                    return seconds ? `${minutes} menit ${seconds} detik` : `${minutes} menit`;
                },

                assign(index) {
                    const [item] = this.available.splice(index, 1);
                    this.assigned.push(item);
                },

                unassign(index) {
                    const [item] = this.assigned.splice(index, 1);
                    this.available.push(item);
                },

                moveUp(index) {
                    if (index === 0) return;
                    [this.assigned[index - 1], this.assigned[index]] = [this.assigned[index], this.assigned[index - 1]];
                },

                moveDown(index) {
                    if (index === this.assigned.length - 1) return;
                    [this.assigned[index + 1], this.assigned[index]] = [this.assigned[index], this.assigned[index + 1]];
                },

                onDragStart(index, event) {
                    this.dragIndex = index;
                    // Firefox only starts a drag when some data is set.
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', String(index));
                },

                onDragOver(index) {
                    this.dragOverIndex = index;
                },

                onDrop(index) {
                    if (this.dragIndex !== null && this.dragIndex !== index) {
                        const [item] = this.assigned.splice(this.dragIndex, 1);
                        this.assigned.splice(index, 0, item);
                    }

                    this.onDragEnd();
                },

                onDragEnd() {
                    this.dragIndex = null;
                    this.dragOverIndex = null;
                },

                get previewItem() {
                    return this.assigned.length ? this.assigned[this.previewIndex] : null;
                },

                openPreview() {
                    if (this.assigned.length === 0) return;

                    this.previewIndex = 0;
                    this.previewFading = false;
                    this.previewOpen = true;
                    this.schedulePreview();
                },

                closePreview() {
                    this.previewOpen = false;
                    clearTimeout(this.previewTimer);
                    clearTimeout(this.previewFadeTimer);
                },

                schedulePreview() {
                    clearTimeout(this.previewTimer);

                    const item = this.previewItem;
                    if (!item) return;

                    // Video advances on its own `ended` event; this is only a
                    // safety net when playback is blocked or the file is broken.
                    const seconds = item.type === 'video'
                        ? 180
                        : Math.max(parseInt(item.duration, 10) || 10, 1);

                    this.previewTimer = setTimeout(() => this.advancePreview(), seconds * 1000);
                },

                advancePreview() {
                    this.stepPreview(1);
                },

                prevPreview() {
                    this.stepPreview(-1);
                },

                stepPreview(offset) {
                    if (this.assigned.length === 0) return;

                    clearTimeout(this.previewTimer);
                    clearTimeout(this.previewFadeTimer);

                    this.previewFading = true;
                    this.previewFadeTimer = setTimeout(() => {
                        const count = this.assigned.length;
                        this.previewIndex = (this.previewIndex + offset + count) % count;
                        this.previewFading = false;
                        this.schedulePreview();
                    }, 250);
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
