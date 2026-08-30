<?php

namespace Tests\Feature\Admin;

use App\Models\Content;
use App\Models\Display;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentBackgroundColorTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_pick_a_background_colour_for_a_text_slide(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.contents.store'), [
            'title' => 'Pengumuman Biru',
            'type' => 'text',
            'text_body' => 'Halo',
            'background_color' => 'biru',
            'duration' => 10,
            'is_active' => '1',
        ])->assertRedirect(route('admin.contents.index'));

        $content = Content::where('title', 'Pengumuman Biru')->firstOrFail();
        $this->assertSame('biru', $content->background_color);
        $this->assertSame('#2563EB', $content->background_hex);
    }

    public function test_a_colour_outside_the_palette_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.contents.store'), [
            'title' => 'Warna Ngawur',
            'type' => 'text',
            'text_body' => 'Halo',
            'background_color' => '#000000',
            'duration' => 10,
        ])->assertSessionHasErrors('background_color');

        $this->assertDatabaseMissing('contents', ['title' => 'Warna Ngawur']);
    }

    public function test_content_without_a_colour_falls_back_to_the_default(): void
    {
        $content = Content::factory()->create(['background_color' => null, 'is_priority' => false]);

        $expected = Content::BACKGROUND_COLORS[Content::DEFAULT_BACKGROUND_COLOR]['hex'];
        $this->assertSame($expected, $content->background_hex);
    }

    public function test_priority_content_stays_red_whatever_colour_was_chosen(): void
    {
        $content = Content::factory()->create([
            'background_color' => 'biru',
            'is_priority' => true,
        ]);

        $this->assertSame(Content::PRIORITY_BACKGROUND_HEX, $content->background_hex);
    }

    public function test_the_display_api_exposes_the_resolved_colour(): void
    {
        $playlist = Playlist::factory()->create();
        $display = Display::factory()->create(['playlist_id' => $playlist->id]);

        $content = Content::factory()->create([
            'type' => 'text',
            'background_color' => 'ungu',
            'is_active' => true,
            'is_priority' => false,
        ]);
        $playlist->contents()->attach($content->id, ['order' => 0]);

        $this->getJson(route('api.display.contents', $display->unique_code))
            ->assertOk()
            ->assertJsonPath('contents.0.background_hex', '#7C3AED');
    }

    public function test_every_palette_colour_clears_wcag_aa_against_white_text(): void
    {
        foreach (Content::BACKGROUND_COLORS as $key => $colour) {
            $ratio = $this->contrastAgainstWhite($colour['hex']);

            $this->assertGreaterThanOrEqual(
                4.5,
                round($ratio, 2),
                "Warna \"{$key}\" ({$colour['hex']}) hanya {$ratio}:1 terhadap teks putih."
            );
        }
    }

    private function contrastAgainstWhite(string $hex): float
    {
        $luminance = function (string $hex): float {
            $hex = ltrim($hex, '#');
            $channels = [];

            foreach ([0, 2, 4] as $offset) {
                $value = hexdec(substr($hex, $offset, 2)) / 255;
                $channels[] = $value <= 0.03928
                    ? $value / 12.92
                    : (($value + 0.055) / 1.055) ** 2.4;
            }

            return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
        };

        return (1.0 + 0.05) / ($luminance($hex) + 0.05);
    }
}
