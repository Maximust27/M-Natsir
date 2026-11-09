<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table): void {
            $table->id();
            $table->char('seed_key', 64)->unique();
            $table->string('category', 80)->index();
            $table->string('category_label', 200);
            $table->string('badge_classes', 255);
            $table->string('source', 255);
            $table->string('date_label', 100);
            $table->string('title', 500);
            $table->text('excerpt');
            $table->text('url');
            $table->string('access_label', 150);
            $table->boolean('is_primary_source')->default(true);
            $table->text('source_note')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort_order')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
