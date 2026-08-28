<?php

namespace Tests\Feature\Admin;

use App\Models\Content;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaylistControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_playlist_and_assign_ordered_contents(): void
    {
        $user = User::factory()->create();
        $playlist = Playlist::factory()->create();
        $contentA = Content::factory()->create(['title' => 'A']);
        $contentB = Content::factory()->create(['title' => 'B']);

        $this->actingAs($user)->put(route('admin.playlists.contents.update', $playlist), [
            'content_ids' => [$contentB->id, $contentA->id],
        ])->assertRedirect(route('admin.playlists.edit', $playlist));

        $ordered = $playlist->fresh()->contents;
        $this->assertSame(['B', 'A'], $ordered->pluck('title')->all());
    }

    public function test_admin_can_reorder_playlist_contents_by_resubmitting(): void
    {
        $user = User::factory()->create();
        $playlist = Playlist::factory()->create();
        $contentA = Content::factory()->create(['title' => 'A']);
        $contentB = Content::factory()->create(['title' => 'B']);

        $playlist->contents()->attach([
            $contentA->id => ['order' => 0],
            $contentB->id => ['order' => 1],
        ]);

        $this->actingAs($user)->put(route('admin.playlists.contents.update', $playlist), [
            'content_ids' => [$contentB->id, $contentA->id],
        ]);

        $this->assertSame(['B', 'A'], $playlist->fresh()->contents->pluck('title')->all());
    }
}
