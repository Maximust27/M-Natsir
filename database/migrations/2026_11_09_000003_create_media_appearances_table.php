<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_appearances', function (Blueprint $table): void {
            $table->id();
            $table->char('seed_key', 64)->unique();
            $table->string('category', 80)->index();
            $table->string('category_label', 200);
            $table->string('badge_variant', 80);
            $table->string('date_label', 100);
            $table->unsignedSmallInteger('year')->index();
            $table->string('outlet', 255);
            $table->string('role', 255);
            $table->string('title', 500);
            $table->text('summary');
            $table->json('topics');
            $table->text('url');
            $table->string('action_label', 150);
            $table->text('thumbnail_url')->nullable();
            $table->text('source_note')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort_order')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_appearances');
    }
};
