<?php

namespace Tests\Feature\Admin;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_content_management(): void
    {
        $this->get(route('admin.contents.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_content_list(): void
    {
        $user = User::factory()->create();
        Content::factory()->create(['title' => 'Konten Uji']);

        $this->actingAs($user)
            ->get(route('admin.contents.index'))
            ->assertOk()
            ->assertSee('Konten Uji');
    }

    public function test_admin_can_create_image_content_with_upload(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.contents.store'), [
            'title' => 'Poster Promo',
            'type' => 'image',
            'file' => UploadedFile::fake()->image('promo.jpg'),
            'duration' => 10,
            'is_active' => '1',
            'is_priority' => '0',
        ]);

        $response->assertRedirect(route('admin.contents.index'));

        $content = Content::where('title', 'Poster Promo')->firstOrFail();
        $this->assertSame('image', $content->type);
        $this->assertTrue($content->is_active);
        Storage::disk('public')->assertExists($content->file_path);
    }

    public function test_text_content_requires_text_body(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.contents.store'), [
            'title' => 'Pengumuman Tanpa Isi',
            'type' => 'text',
            'duration' => 10,
        ]);

        $response->assertSessionHasErrors('text_body');
        $this->assertDatabaseMissing('contents', ['title' => 'Pengumuman Tanpa Isi']);
    }

    public function test_admin_can_update_and_delete_content(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $content = Content::factory()->create(['title' => 'Judul Lama', 'type' => 'text', 'text_body' => 'lama']);

        $this->actingAs($user)->put(route('admin.contents.update', $content), [
            'title' => 'Judul Baru',
            'type' => 'text',
            'text_body' => 'baru',
            'duration' => 12,
            'is_active' => '1',
        ])->assertRedirect(route('admin.contents.index'));

        $this->assertSame('Judul Baru', $content->fresh()->title);

        $this->actingAs($user)->delete(route('admin.contents.destroy', $content))
            ->assertRedirect(route('admin.contents.index'));

        $this->assertDatabaseMissing('contents', ['id' => $content->id]);
    }
}
