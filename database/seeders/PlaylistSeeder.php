<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Playlist;
use Illuminate\Database\Seeder;

class PlaylistSeeder extends Seeder
{
    public function run(): void
    {
        $playlist = Playlist::create(['name' => 'Playlist Utama Lobi']);

        $contents = Content::where('is_priority', false)->orderBy('order')->get();

        foreach ($contents as $order => $content) {
            $playlist->contents()->attach($content->id, ['order' => $order]);
        }
    }
}
