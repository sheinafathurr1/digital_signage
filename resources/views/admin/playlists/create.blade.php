<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">Tambah Playlist</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                <form method="POST" action="{{ route('admin.playlists.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nama Playlist" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            value="{{ old('name') }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.playlists.index') }}" class="text-sm text-muted hover:underline">Batal</a>
                        <x-primary-button>Simpan &amp; Atur Konten</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
