<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Konten</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.contents.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @include('admin.contents._form')

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.contents.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        <x-primary-button>Simpan Konten</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
