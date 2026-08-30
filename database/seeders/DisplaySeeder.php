<?php

namespace Database\Seeders;

use App\Models\Display;
use App\Models\Playlist;
use Illuminate\Database\Seeder;

class DisplaySeeder extends Seeder
{
    public function run(): void
    {
        $playlist = Playlist::first();

        Display::create([
            'name' => 'Layar Lobi Utama',
            'location' => 'Lobi Lantai 1',
            'orientation' => 'landscape',
            'playlist_id' => $playlist?->id,
        ]);

        // A second screen in portrait so the portrait layout is reachable
        // straight after seeding, not only once someone adds one by hand.
        Display::create([
            'name' => 'Layar Koridor',
            'location' => 'Koridor Lantai 2',
            'orientation' => 'portrait',
            'playlist_id' => $playlist?->id,
        ]);
    }
}
