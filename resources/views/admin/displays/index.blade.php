<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink leading-tight">Manajemen Layar</h2>
            <a href="{{ route('admin.displays.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-on-primary uppercase tracking-widest hover:bg-primary-hover transition-colors duration-fast">
                + Tambah Layar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <div class="bg-surface shadow-card border border-border rounded-lg overflow-hidden">
                @if ($displays->isEmpty())
                    <x-empty-state icon="tv" title="Belum ada layar terdaftar"
                        description="Daftarkan layar untuk mendapatkan URL unik yang bisa dibuka di TV atau monitor publik."
                        action-label="+ Tambah Layar" :action-href="route('admin.displays.create')" />
                @else
                    <table class="min-w-full divide-y divide-border text-sm">
                        <thead class="bg-background">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-muted">Nama</th>
                                <th class="px-4 py-3 text-left font-medium text-muted">Lokasi</th>
                                <th class="px-4 py-3 text-left font-medium text-muted">Kode Unik / URL</th>
                                <th class="px-4 py-3 text-left font-medium text-muted">Playlist</th>
                                <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                                <th class="px-4 py-3 text-right font-medium text-muted">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($displays as $display)
                                <tr class="hover:bg-background/60 transition-colors duration-fast">
                                    <td class="px-4 py-3 text-ink">{{ $display->name }}</td>
                                    <td class="px-4 py-3 text-muted">{{ $display->location ?? '-' }}</td>
                                    <td class="px-4 py-3 text-muted">
                                        <a href="{{ route('display.show', $display->unique_code) }}" target="_blank"
                                            class="text-primary hover:underline font-mono text-xs">
                                            /display/{{ $display->unique_code }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-muted">{{ $display->playlist?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($display->is_online)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success"><span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse motion-reduce:animate-none"></span>Online</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-border/50 text-muted"><span class="w-1.5 h-1.5 rounded-full bg-muted"></span>Offline</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.displays.edit', $display) }}" class="text-primary hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.displays.regenerate-code', $display) }}" class="inline"
                                            onsubmit="return confirm('Buat ulang kode unik untuk layar ini? URL lama tidak akan berfungsi lagi.');">
                                            @csrf
                                            <button type="submit" class="text-warning hover:underline">Kode Baru</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.displays.destroy', $display) }}" class="inline"
                                            onsubmit="return confirm('Hapus layar &quot;{{ $display->name }}&quot;?');">
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

            {{ $displays->links() }}
        </div>
    </div>
</x-app-layout>
