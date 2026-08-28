<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Konten</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $contentCount }}</div>
                    <a href="{{ route('admin.contents.index') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Kelola konten &rarr;</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Playlist</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $playlistCount }}</div>
                    <a href="{{ route('admin.playlists.index') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Kelola playlist &rarr;</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Layar</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $displayCount }}</div>
                    <a href="{{ route('admin.displays.index') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Kelola layar &rarr;</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500">Layar Online</div>
                    <div class="text-3xl font-bold text-green-600 mt-1">{{ $onlineDisplayCount }} / {{ $displayCount }}</div>
                    <span class="text-sm text-gray-400 mt-2 inline-block">Aktif dalam 2 menit terakhir</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-2">Selamat datang di Panel Admin Digital Signage</h3>
                <p class="text-sm text-gray-600">
                    Gunakan menu di atas untuk mengelola konten, menyusun playlist, dan mendaftarkan layar publik.
                    Setiap layar dapat diakses melalui URL unik <code class="bg-gray-100 px-1 rounded">/display/{{ '{kode_unik}' }}</code> tanpa perlu login.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
