<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('disk')->default('uploads');
            $table->string('path');            // re-encoded WebP, 1600px
            $table->string('path_sm')->nullable(); // 800px variant for srcset
            $table->string('original_name')->nullable();
            $table->string('alt')->nullable();
            $table->boolean('is_cover')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_media');
    }
};
