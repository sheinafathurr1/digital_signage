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
    }
}
