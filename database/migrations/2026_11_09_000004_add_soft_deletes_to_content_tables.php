<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['articles', 'library_resources', 'media_appearances'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->softDeletes();
            });
        }
    }

    public function down(): void
    {
        foreach (['articles', 'library_resources', 'media_appearances'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->dropSoftDeletes();
            });
        }
    }
};
