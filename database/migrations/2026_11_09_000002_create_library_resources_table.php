<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_resources', function (Blueprint $table): void {
            $table->id();
            $table->char('seed_key', 64)->unique();
            $table->string('collection', 80)->index();
            $table->string('collection_label', 200);
            $table->string('title', 500);
            $table->string('document_type', 100)->index();
            $table->string('type_label', 200);
            $table->string('institution', 100)->index();
            $table->string('institution_label', 255);
            $table->string('year_label', 40);
            $table->json('topics');
            $table->json('topic_labels');
            $table->string('author_label', 255)->nullable();
            $table->string('role_label', 255)->nullable();
            $table->text('summary');
            $table->text('url');
            $table->string('language', 32);
            $table->string('access_label', 150);
            $table->text('source_note')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort_order')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_resources');
    }
};
