<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-success/10 border border-success/20 text-success text-sm rounded-md p-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                    <div class="text-sm text-muted">Total Konten</div>
                    <div class="text-3xl font-bold text-ink mt-1">{{ $contentCount }}</div>
                    <a href="{{ route('admin.contents.index') }}" class="text-sm text-primary hover:underline mt-2 inline-block">Kelola konten &rarr;</a>
                </div>
                <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                    <div class="text-sm text-muted">Total Playlist</div>
                    <div class="text-3xl font-bold text-ink mt-1">{{ $playlistCount }}</div>
                    <a href="{{ route('admin.playlists.index') }}" class="text-sm text-primary hover:underline mt-2 inline-block">Kelola playlist &rarr;</a>
                </div>
                <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                    <div class="text-sm text-muted">Total Layar</div>
                    <div class="text-3xl font-bold text-ink mt-1">{{ $displayCount }}</div>
                    <a href="{{ route('admin.displays.index') }}" class="text-sm text-primary hover:underline mt-2 inline-block">Kelola layar &rarr;</a>
                </div>
                <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                    <div class="text-sm text-muted">Layar Online</div>
                    <div class="text-3xl font-bold text-success mt-1">{{ $onlineDisplayCount }} / {{ $displayCount }}</div>
                    <span class="text-sm text-muted mt-2 inline-block">Aktif dalam 2 menit terakhir</span>
                </div>
            </div>

            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                <h3 class="font-semibold text-ink mb-2">Selamat datang di Panel Admin Digital Signage</h3>
                <p class="text-sm text-muted">
                    Gunakan menu di atas untuk mengelola konten, menyusun playlist, dan mendaftarkan layar publik.
                    Setiap layar dapat diakses melalui URL unik <code class="bg-background px-1 rounded">/display/{{ '{kode_unik}' }}</code> tanpa perlu login.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
