<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Created without a factory on purpose: factories pull in fakerphp/faker,
        // which is a dev dependency and is absent after `composer install --no-dev`.
        // Seeding a freshly deployed server has to work without it.
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Digital Signage',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        if ($user->wasRecentlyCreated) {
            $this->command?->warn('Akun admin dibuat: admin@example.com / password');
            $this->command?->warn('Kredensial ini bersifat publik — segera ganti kata sandinya jika server dapat diakses dari internet.');
        }

        $this->call([
            ContentSeeder::class,
            PlaylistSeeder::class,
            DisplaySeeder::class,
        ]);
    }
}
