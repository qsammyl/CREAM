<?php

namespace Tests\Feature\Console;

use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportStaticTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_the_static_listings_idempotently(): void
    {
        if (! is_file(base_path('../data/listings.json'))) {
            $this->markTestSkipped('Static data/listings.json not present.');
        }

        Storage::fake('uploads');

        $this->artisan('cream:import-static')->assertExitCode(0);
        $count = Asset::count();
        $this->assertSame(11, $count, 'Expected 8 listings + 3 land plots.');

        // Second run must not duplicate.
        $this->artisan('cream:import-static')->assertExitCode(0);
        $this->assertSame($count, Asset::count());
    }
}
