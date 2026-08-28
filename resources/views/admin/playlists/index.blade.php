<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink leading-tight">Manajemen Playlist</h2>
            <a href="{{ route('admin.playlists.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-on-primary uppercase tracking-widest hover:bg-primary-hover transition-colors duration-fast">
                + Tambah Playlist
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <div class="bg-surface shadow-card border border-border rounded-lg overflow-hidden">
                @if ($playlists->isEmpty())
                    <x-empty-state icon="playlist" title="Belum ada playlist"
                        description="Buat playlist untuk mengelompokkan dan mengurutkan konten yang akan tayang di layar."
                        action-label="+ Tambah Playlist" :action-href="route('admin.playlists.create')" />
                @else
                    <table class="min-w-full divide-y divide-border text-sm">
                        <thead class="bg-background">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-muted">Nama Playlist</th>
                                <th class="px-4 py-3 text-left font-medium text-muted">Jumlah Konten</th>
                                <th class="px-4 py-3 text-left font-medium text-muted">Dipakai di Layar</th>
                                <th class="px-4 py-3 text-right font-medium text-muted">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($playlists as $playlist)
                                <tr class="hover:bg-background/60 transition-colors duration-fast">
                                    <td class="px-4 py-3 text-ink">{{ $playlist->name }}</td>
                                    <td class="px-4 py-3 text-muted">{{ $playlist->contents_count }} konten</td>
                                    <td class="px-4 py-3 text-muted">{{ $playlist->displays_count }} layar</td>
                                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.playlists.edit', $playlist) }}" class="text-primary hover:underline">Kelola</a>
                                        <form method="POST" action="{{ route('admin.playlists.destroy', $playlist) }}" class="inline"
                                            onsubmit="return confirm('Hapus playlist &quot;{{ $playlist->name }}&quot;?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{ $playlists->links() }}
        </div>
    </div>
</x-app-layout>
