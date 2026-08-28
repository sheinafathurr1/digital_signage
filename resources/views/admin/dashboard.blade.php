<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.contents.index') }}"
                    class="group bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6 transition-all duration-base hover:shadow-elevated hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 shrink-0 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3 3h18v18H3V3Zm10.5 6a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                            </svg>
                        </span>
                        <div class="text-sm text-muted">Total Konten</div>
                    </div>
                    <div class="text-3xl font-bold text-ink mt-3">{{ $contentCount }}</div>
                    <span class="text-sm text-primary group-hover:underline mt-2 inline-block">Kelola konten &rarr;</span>
                </a>
                <a href="{{ route('admin.playlists.index') }}"
                    class="group bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6 transition-all duration-base hover:shadow-elevated hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 shrink-0 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h8.25" />
                            </svg>
                        </span>
                        <div class="text-sm text-muted">Total Playlist</div>
                    </div>
                    <div class="text-3xl font-bold text-ink mt-3">{{ $playlistCount }}</div>
                    <span class="text-sm text-primary group-hover:underline mt-2 inline-block">Kelola playlist &rarr;</span>
                </a>
                <a href="{{ route('admin.displays.index') }}"
                    class="group bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6 transition-all duration-base hover:shadow-elevated hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 shrink-0 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h16.5v11.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V3.75ZM8.25 20.25h7.5M12 16.75v3.5" />
                            </svg>
                        </span>
                        <div class="text-sm text-muted">Total Layar</div>
                    </div>
                    <div class="text-3xl font-bold text-ink mt-3">{{ $displayCount }}</div>
                    <span class="text-sm text-primary group-hover:underline mt-2 inline-block">Kelola layar &rarr;</span>
                </a>
                <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 shrink-0 rounded-full bg-success/10 text-success flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.62-5.62 14.532-5.62 20.152 0M12 20.25h.008v.008H12v-.008Z" />
                            </svg>
                        </span>
                        <div class="text-sm text-muted">Layar Online</div>
                    </div>
                    <div class="text-3xl font-bold text-success mt-3">{{ $onlineDisplayCount }} / {{ $displayCount }}</div>
                    <span class="text-sm text-muted mt-2 inline-block">Aktif dalam 2 menit terakhir</span>
                </div>
            </div>

            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                <h3 class="font-semibold text-ink mb-2">Selamat datang di Panel Admin Digital Signage</h3>
                <p class="text-sm text-muted leading-relaxed">
                    Gunakan menu di atas untuk mengelola konten, menyusun playlist, dan mendaftarkan layar publik.
                    Setiap layar dapat diakses melalui URL unik <code class="bg-background px-1.5 py-0.5 rounded text-primary">/display/{{ '{kode_unik}' }}</code> tanpa perlu login.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
