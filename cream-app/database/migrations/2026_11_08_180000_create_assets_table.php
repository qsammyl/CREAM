<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('reference')->unique();

            $table->string('title');
            $table->text('summary')->nullable();
            $table->text('summary2')->nullable();
            $table->longText('description')->nullable(); // sanitised rich text (dashboard-created)

            $table->string('category', 20)->index();     // sale|rent|land|short_stay|new_project
            $table->string('status', 20)->default('draft')->index();

            // Flags
            $table->boolean('is_exclusive')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_off_plan')->default(false);
            $table->boolean('is_furnished')->default(false);
            $table->boolean('title_verified')->default(false);
            $table->text('verified_summary')->nullable();
            $table->json('documents_available')->nullable();

            // Price
            $table->unsignedBigInteger('price')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('price_period', 10)->default('total'); // total|month|night
            $table->boolean('price_on_request')->default(false);
            $table->string('price_note')->nullable();

            // Details (kept as the display strings the static site used, to import losslessly)
            $table->string('beds')->nullable();
            $table->string('baths')->nullable();
            $table->string('floor_area')->nullable();
            $table->string('plot_size')->nullable();
            $table->string('tenure')->nullable();
            $table->string('survey')->nullable();
            $table->string('access')->nullable();
            $table->string('lease_years')->nullable();
            $table->string('stage')->nullable();            // new projects
            $table->string('expected_delivery')->nullable(); // new projects
            $table->string('availability')->nullable();
            $table->unsignedInteger('max_guests')->nullable(); // short stays

            // Location
            $table->string('location')->nullable();       // full display line
            $table->string('location_area')->nullable();  // e.g. East Legon
            $table->string('location_city')->default('Accra');
            $table->string('country')->default('Ghana');
            $table->string('area_key')->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->json('features')->nullable();
            $table->string('video_url')->nullable(); // YouTube/Vimeo URL or uploaded mp4 path

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
