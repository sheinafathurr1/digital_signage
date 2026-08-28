<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Layar</h2>
            <a href="{{ route('admin.displays.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Tambah Layar
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

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nama</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Lokasi</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Kode Unik / URL</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Playlist</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($displays as $display)
                            <tr>
                                <td class="px-4 py-3 text-gray-800">{{ $display->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $display->location ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    <a href="{{ route('display.show', $display->unique_code) }}" target="_blank"
                                        class="text-indigo-600 hover:underline font-mono text-xs">
                                        /display/{{ $display->unique_code }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $display->playlist?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($display->is_online)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Online</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Offline</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.displays.edit', $display) }}" class="text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.displays.regenerate-code', $display) }}" class="inline"
                                        onsubmit="return confirm('Buat ulang kode unik untuk layar ini? URL lama tidak akan berfungsi lagi.');">
                                        @csrf
                                        <button type="submit" class="text-amber-600 hover:underline">Kode Baru</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.displays.destroy', $display) }}" class="inline"
                                        onsubmit="return confirm('Hapus layar &quot;{{ $display->name }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada layar terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $displays->links() }}
        </div>
    </div>
</x-app-layout>
