<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    /**
     * Import the original published catalogues once. Existing rows are never
     * overwritten, so subsequent editorial changes are preserved.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedArticles();
            $this->seedLibrary();
            $this->seedMedia();
        });
    }

    private function seedArticles(): void
    {
        foreach (require database_path('seeders/data/articles.php') as $position => $record) {
            DB::table('articles')->insertOrIgnore([
                'seed_key' => hash('sha256', $record['title'].'|'.$record['url']),
                'category' => $record['category'],
                'category_label' => $record['categoryLabel'],
                'badge_classes' => $record['badgeClasses'],
                'source' => $record['source'],
                'date_label' => $record['date'],
                'title' => $record['title'],
                'excerpt' => $record['excerpt'],
                'url' => $record['url'],
                'access_label' => $record['accessLabel'],
                'is_primary_source' => $record['isPrimarySource'] ?? true,
                'source_note' => $record['sourceNote'] ?? null,
                'is_published' => true,
                'sort_order' => $position + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedLibrary(): void
    {
        foreach (require database_path('seeders/data/library_resources.php') as $position => $record) {
            DB::table('library_resources')->insertOrIgnore([
                'seed_key' => hash('sha256', $record['title'].'|'.$record['url']),
                'collection' => $record['collection'],
                'collection_label' => $record['collectionLabel'],
                'title' => $record['title'],
                'document_type' => $record['type'],
                'type_label' => $record['typeLabel'],
                'institution' => $record['institution'],
                'institution_label' => $record['institutionLabel'],
                'year_label' => (string) $record['year'],
                'topics' => json_encode($record['topics'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'topic_labels' => json_encode($record['topicLabels'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'author_label' => $record['authorLabel'] ?? null,
                'role_label' => $record['roleLabel'] ?? null,
                'summary' => $record['summary'],
                'url' => $record['url'],
                'language' => $record['language'],
                'access_label' => $record['accessLabel'],
                'source_note' => $record['sourceNote'] ?? null,
                'is_published' => true,
                'sort_order' => $position + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedMedia(): void
    {
        foreach (require database_path('seeders/data/media_appearances.php') as $position => $record) {
            DB::table('media_appearances')->insertOrIgnore([
                'seed_key' => hash('sha256', $record['title'].'|'.$record['url']),
                'category' => $record['category'],
                'category_label' => $record['categoryLabel'],
                'badge_variant' => $record['badgeVariant'],
                'date_label' => $record['date'],
                'year' => $record['year'],
                'outlet' => $record['outlet'],
                'role' => $record['role'],
                'title' => $record['title'],
                'summary' => $record['summary'],
                'topics' => json_encode($record['topics'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'url' => $record['url'],
                'action_label' => $record['actionLabel'],
                'thumbnail_url' => $record['thumbnail'] ?? null,
                'source_note' => $record['sourceNote'] ?? null,
                'is_published' => true,
                'sort_order' => $position + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
