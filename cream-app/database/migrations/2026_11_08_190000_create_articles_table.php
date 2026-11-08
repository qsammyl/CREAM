<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();        // sanitised HTML (allow-list)
            $table->string('cover_path')->nullable();     // WebP on the uploads disk
            $table->string('cover_path_sm')->nullable();

            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name')->nullable();    // for migrated posts without a user
            $table->string('category')->nullable();

            $table->string('status', 20)->default('draft')->index();
            $table->boolean('allow_comments')->default(true);

            $table->string('source')->nullable();         // e.g. "GhanaWeb" (migrated posts)
            $table->string('source_url')->nullable();
            $table->string('legacy_path')->nullable();    // original static URL for hand-written posts

            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
