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
            <form method="GET" class="flex gap-2">
                <x-text-input type="text" name="search" placeholder="Cari judul konten..." value="{{ $search }}" class="w-full sm:w-72" />
                <x-secondary-button type="submit">Cari</x-secondary-button>
            </form>

            <div class="bg-surface shadow-card border border-border rounded-lg overflow-hidden">
                @if ($contents->isEmpty())
                    <x-empty-state icon="inbox" title="Belum ada konten"
                        description="Mulai tambahkan gambar, video, atau teks pengumuman untuk ditayangkan di layar."
                        action-label="+ Tambah Konten" :action-href="route('admin.contents.create')" />
                @else
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
                            @foreach ($contents as $content)
                                <tr class="even:bg-background hover:bg-border/40 transition-colors duration-fast">
                                    <td class="px-4 py-3 text-ink">
                                        <div class="flex items-center gap-3">
                                            <div class="w-14 h-10 shrink-0 rounded-md border border-border overflow-hidden bg-background flex items-center justify-center text-muted">
                                                @if ($content->type === 'image' && $content->file_path)
                                                    <img src="{{ $content->file_url }}" alt="" class="w-full h-full object-cover">
                                                @elseif ($content->type === 'video')
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                        <path d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" />
                                                    </svg>
                                                @elseif ($content->type === 'html-embed')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-medium">{{ $content->title }}</div>
                                                @if ($content->is_priority)
                                                    <span class="mt-0.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                                        </svg>
                                                        Prioritas
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
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
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Aktif</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-border/50 text-muted"><span class="w-1.5 h-1.5 rounded-full bg-muted"></span>Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.contents.edit', $content) }}" class="text-primary hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.contents.destroy', $content) }}" class="inline"
                                            data-confirm="Konten &quot;{{ $content->title }}&quot; akan dihapus permanen dan tidak bisa dikembalikan.">
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

            {{ $contents->links() }}
        </div>
    </div>
</x-app-layout>
