<?php

namespace Tests\Feature\Public;

use App\Models\Content;
use App\Models\Display;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DisplayContentsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_display_page_renders_without_authentication(): void
    {
        $display = Display::factory()->create();

        $this->get(route('display.show', $display->unique_code))
            ->assertOk()
            ->assertSee($display->name);

        $this->assertNotNull($display->fresh()->last_seen_at);
    }

    public function test_unknown_display_code_returns_404(): void
    {
        $this->get('/display/DOES-NOT-EXIST')->assertNotFound();
    }

    public function test_api_returns_only_active_scheduled_contents_from_assigned_playlist(): void
    {
        $playlist = Playlist::factory()->create();
        $display = Display::factory()->create(['playlist_id' => $playlist->id]);

        $active = Content::factory()->create(['is_active' => true, 'title' => 'Aktif']);
        $inactive = Content::factory()->create(['is_active' => false, 'title' => 'Nonaktif']);
        $expired = Content::factory()->create([
            'is_active' => true,
            'title' => 'Kedaluwarsa',
            'end_date' => Carbon::yesterday(),
        ]);

        $playlist->contents()->attach([
            $active->id => ['order' => 0],
            $inactive->id => ['order' => 1],
            $expired->id => ['order' => 2],
        ]);

        $response = $this->getJson(route('api.display.contents', $display->unique_code));

        $response->assertOk();
        $titles = collect($response->json('contents'))->pluck('title');
        $this->assertTrue($titles->contains('Aktif'));
        $this->assertFalse($titles->contains('Nonaktif'));
        $this->assertFalse($titles->contains('Kedaluwarsa'));
    }

    public function test_priority_content_interrupts_every_display_regardless_of_playlist(): void
    {
        $display = Display::factory()->create(['playlist_id' => null]);
        $priority = Content::factory()->create(['is_active' => true, 'is_priority' => true, 'title' => 'Darurat']);

        $response = $this->getJson(route('api.display.contents', $display->unique_code));

        $response->assertOk();
        $priorityTitles = collect($response->json('priority_contents'))->pluck('title');
        $this->assertTrue($priorityTitles->contains('Darurat'));
    }

    public function test_polling_updates_heartbeat_last_seen_at(): void
    {
        $display = Display::factory()->create(['last_seen_at' => null]);

        $this->getJson(route('api.display.contents', $display->unique_code));

        $this->assertNotNull($display->fresh()->last_seen_at);
    }
}
