<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">Kelola Playlist: {{ $playlist->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-success/10 border border-success/20 text-success text-sm rounded-md p-4">
                    {{ session('status') }}
                </div>
            @endif

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
                    assigned: {{ Illuminate\Support\Js::from($playlist->contents->map(fn ($c) => ['id' => $c->id, 'title' => $c->title, 'type_label' => $c->type_label])) }},
                    available: {{ Illuminate\Support\Js::from($availableContents->map(fn ($c) => ['id' => $c->id, 'title' => $c->title, 'type_label' => $c->type_label])) }},
                })">
                <h3 class="font-semibold text-ink mb-1">Susun Konten Playlist</h3>
                <p class="text-sm text-muted mb-4">Tambahkan konten dari daftar tersedia, lalu atur urutan tayang dengan tombol naik/turun.</p>

                <form method="POST" action="{{ route('admin.playlists.contents.update', $playlist) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-muted mb-2">Konten Terpilih (urutan tayang)</h4>
                            <ul class="border border-border rounded-md divide-y divide-border min-h-[3rem]">
                                <template x-for="(item, index) in assigned" :key="item.id">
                                    <li class="flex items-center justify-between px-3 py-2 text-sm">
                                        <span>
                                            <span class="text-muted mr-2" x-text="index + 1 + '.'"></span>
                                            <span x-text="item.title"></span>
                                            <span class="text-muted" x-text="'(' + item.type_label + ')'"></span>
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <button type="button" class="text-muted hover:text-ink disabled:opacity-25"
                                                @click="moveUp(index)" :disabled="index === 0">&uarr;</button>
                                            <button type="button" class="text-muted hover:text-ink disabled:opacity-25"
                                                @click="moveDown(index)" :disabled="index === assigned.length - 1">&darr;</button>
                                            <button type="button" class="text-danger hover:underline" @click="unassign(index)">Hapus</button>
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
                                    <li class="flex items-center justify-between px-3 py-2 text-sm">
                                        <span>
                                            <span x-text="item.title"></span>
                                            <span class="text-muted" x-text="'(' + item.type_label + ')'"></span>
                                        </span>
                                        <button type="button" class="text-primary hover:underline" @click="assign(index)">+ Tambah</button>
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
            };
        }
    </script>
    @endpush
</x-app-layout>
