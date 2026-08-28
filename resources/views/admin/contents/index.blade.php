<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink leading-tight">Manajemen Konten</h2>
            <a href="{{ route('admin.contents.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-on-primary uppercase tracking-widest hover:bg-primary-hover transition-colors duration-fast">
                + Tambah Konten
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-success/10 border border-success/20 text-success text-sm rounded-md p-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" class="flex gap-2">
                <x-text-input type="text" name="search" placeholder="Cari judul konten..." value="{{ $search }}" class="w-full sm:w-72" />
                <x-secondary-button type="submit">Cari</x-secondary-button>
            </form>

            <div class="bg-surface shadow-card border border-border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-border text-sm">
                    <thead class="bg-background">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-muted">Judul</th>
                            <th class="px-4 py-3 text-left font-medium text-muted">Tipe</th>
                            <th class="px-4 py-3 text-left font-medium text-muted">Durasi</th>
                            <th class="px-4 py-3 text-left font-medium text-muted">Jadwal</th>
                            <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($contents as $content)
                            <tr>
                                <td class="px-4 py-3 text-ink">
                                    {{ $content->title }}
                                    @if ($content->is_priority)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">Prioritas</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-muted">{{ $content->type_label }}</td>
                                <td class="px-4 py-3 text-muted">{{ $content->duration }}s</td>
                                <td class="px-4 py-3 text-muted">
                                    {{ optional($content->start_date)->format('d/m/Y') ?? '-' }}
                                    &ndash;
                                    {{ optional($content->end_date)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($content->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-border/50 text-muted">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.contents.edit', $content) }}" class="text-primary hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.contents.destroy', $content) }}" class="inline"
                                        onsubmit="return confirm('Hapus konten &quot;{{ $content->title }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-muted">Belum ada konten. Silakan tambah konten baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $contents->links() }}
        </div>
    </div>
</x-app-layout>
