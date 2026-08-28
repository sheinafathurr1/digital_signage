<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Layar: {{ $display->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.displays.update', $display) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('admin.displays._form')

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.displays.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        <x-primary-button>Perbarui Layar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
