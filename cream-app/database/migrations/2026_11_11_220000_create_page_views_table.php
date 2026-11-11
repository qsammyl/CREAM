<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 255)->index();
            // Daily-rotating salted hash of IP+UA — cookieless, and not
            // correlatable across days by design (the salt changes each day).
            $table->char('visitor_hash', 64)->nullable();
            $table->string('referer_host', 128)->nullable();   // external referrer host only
            $table->string('utm_source', 64)->nullable();      // ?utm_source= campaign tag
            $table->char('country', 2)->nullable();            // Cloudflare CF-IPCountry, when present
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->timestamp('created_at')->nullable();       // no updated_at (append-only)

            // Date-range scans + per-day unique-visitor counts.
            $table->index(['created_at', 'visitor_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
