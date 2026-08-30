<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">Tambah Layar</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                <form method="POST" action="{{ route('admin.displays.store') }}" class="space-y-6">
                    @csrf

                    @include('admin.displays._form')

                    <p class="text-xs text-muted">Kode unik layar akan dibuat otomatis setelah disimpan.</p>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.displays.index') }}" class="text-sm text-muted hover:underline">Batal</a>
                        <x-primary-button>Simpan Layar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
