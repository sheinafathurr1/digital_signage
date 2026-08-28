<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Konten</h2>
            <a href="{{ route('admin.contents.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Tambah Konten
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" class="flex gap-2">
                <x-text-input type="text" name="search" placeholder="Cari judul konten..." value="{{ $search }}" class="w-full sm:w-72" />
                <x-secondary-button type="submit">Cari</x-secondary-button>
            </form>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Judul</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Tipe</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Durasi</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Jadwal</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($contents as $content)
                            <tr>
                                <td class="px-4 py-3 text-gray-800">
                                    {{ $content->title }}
                                    @if ($content->is_priority)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Prioritas</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $content->type_label }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $content->duration }}s</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ optional($content->start_date)->format('d/m/Y') ?? '-' }}
                                    &ndash;
                                    {{ optional($content->end_date)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($content->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.contents.edit', $content) }}" class="text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.contents.destroy', $content) }}" class="inline"
                                        onsubmit="return confirm('Hapus konten &quot;{{ $content->title }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada konten. Silakan tambah konten baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $contents->links() }}
        </div>
    </div>
</x-app-layout>
