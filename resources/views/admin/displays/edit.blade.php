<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink leading-tight">Edit Layar: {{ $display->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-card border border-border rounded-lg p-6">
                <form method="POST" action="{{ route('admin.displays.update', $display) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('admin.displays._form')

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.displays.index') }}" class="text-sm text-muted hover:underline">Batal</a>
                        <x-primary-button>Perbarui Layar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
