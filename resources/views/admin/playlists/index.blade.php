<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Playlist</h2>
            <a href="{{ route('admin.playlists.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Tambah Playlist
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Playlist</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Jumlah Konten</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Dipakai di Layar</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($playlists as $playlist)
                            <tr>
                                <td class="px-4 py-3 text-gray-800">{{ $playlist->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $playlist->contents_count }} konten</td>
                                <td class="px-4 py-3 text-gray-600">{{ $playlist->displays_count }} layar</td>
                                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.playlists.edit', $playlist) }}" class="text-indigo-600 hover:underline">Kelola</a>
                                    <form method="POST" action="{{ route('admin.playlists.destroy', $playlist) }}" class="inline"
                                        onsubmit="return confirm('Hapus playlist &quot;{{ $playlist->name }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada playlist.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $playlists->links() }}
        </div>
    </div>
</x-app-layout>
