<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();      // never shown publicly
            $table->string('role')->nullable();        // e.g. "Diaspora Buyer · London, 2025"
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('body');
            // consent === true is REQUIRED before a testimonial may be published.
            $table->boolean('consent')->default(false);
            $table->string('status', 20)->default('pending')->index();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('source', 20)->nullable();  // web | qr | import
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
