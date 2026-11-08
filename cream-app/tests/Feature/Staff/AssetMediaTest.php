<?php

namespace Tests\Feature\Staff;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetMediaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);
    }

    public function test_uploaded_image_is_re_encoded_to_webp_and_set_as_cover(): void
    {
        Storage::fake('uploads');
        $asset = Asset::factory()->create();

        $file = UploadedFile::fake()->image('photo.jpg', 2000, 1500);

        $this->actingAs($this->admin())
            ->post('/staff/assets/'.$asset->slug.'/media', ['photos' => [$file]])
            ->assertRedirect();

        $media = $asset->media()->first();
        $this->assertNotNull($media);
        $this->assertStringEndsWith('.webp', $media->path);
        $this->assertStringEndsWith('-800.webp', $media->path_sm);
        $this->assertTrue($media->is_cover);
        Storage::disk('uploads')->assertExists($media->path);
        Storage::disk('uploads')->assertExists($media->path_sm);
    }

    public function test_non_image_upload_is_rejected(): void
    {
        Storage::fake('uploads');
        $asset = Asset::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 200, 'application/pdf');

        $this->actingAs($this->admin())
            ->post('/staff/assets/'.$asset->slug.'/media', ['photos' => [$file]])
            ->assertSessionHasErrors('photos.0');

        $this->assertSame(0, $asset->media()->count());
    }
}
