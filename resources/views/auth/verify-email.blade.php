<x-guest-layout>
    <h2 class="text-lg font-semibold text-ink mb-1">Verifikasi Email</h2>
    <p class="text-sm text-muted mb-6">
        Terima kasih sudah mendaftar. Sebelum mulai, mohon verifikasi email Anda melalui tautan
        yang baru saja kami kirim. Jika belum menerimanya, kami dapat mengirim ulang.
    </p>

    <x-auth-session-status :status="session('status') === 'verification-link-sent' ? 'Tautan verifikasi baru telah dikirim ke email Anda.' : null" />

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-muted hover:text-ink rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Keluar
            </button>
        </form>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>
                Kirim Ulang Email
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
