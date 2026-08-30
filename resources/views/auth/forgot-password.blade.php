<x-guest-layout>
    <h2 class="text-lg font-semibold text-ink mb-1">Lupa Kata Sandi</h2>
    <p class="text-sm text-muted mb-6">
        Masukkan email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
    </p>

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-muted hover:text-ink rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('login') }}">
                Kembali ke halaman masuk
            </a>

            <x-primary-button>
                Kirim Tautan
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
