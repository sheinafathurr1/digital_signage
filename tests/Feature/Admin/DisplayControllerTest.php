<?php

namespace Tests\Feature\Admin;

use App\Models\Display;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_display_with_auto_generated_unique_code(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.displays.store'), [
            'name' => 'Layar Lobi',
            'location' => 'Lantai 1',
            'orientation' => 'landscape',
        ])->assertRedirect(route('admin.displays.index'));

        $display = Display::where('name', 'Layar Lobi')->firstOrFail();
        $this->assertNotEmpty($display->unique_code);
        $this->assertSame(8, strlen($display->unique_code));
    }

    public function test_admin_can_regenerate_unique_code(): void
    {
        $user = User::factory()->create();
        $display = Display::factory()->create();
        $originalCode = $display->unique_code;

        $this->actingAs($user)->post(route('admin.displays.regenerate-code', $display))
            ->assertRedirect(route('admin.displays.index'));

        $this->assertNotSame($originalCode, $display->fresh()->unique_code);
    }
}
