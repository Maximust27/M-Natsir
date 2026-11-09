<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    // Retire only original source records about PR visits, awards, or Humas administration.
    // Substantive case interviews remain even if the interviewee was identified as Humas/Jubir.
    private const RETIRED_MEDIA = [
        ['title' => 'Sinergi PPATK dan Pikiran Rakyat: Membangun Kesadaran Publik Akan Bahayanya Pencucian Uang', 'url' => 'https://www.ppatk.go.id/news/read/1534/sinergi-ppatk-dan-pikiran-rakyat-membangun-kesadaran-publik-akan-bahayanya-pencucian-uang.html'],
        ['title' => 'PPATK Teguhkan Komitmen Sebagai Badan Publik Informatif', 'url' => 'https://ppid.ppatk.go.id/?p=15022'],
        ['title' => 'PPATK Sabet Penghargaan Gatra Awards 2023', 'url' => 'https://www.ppatk.go.id/news/read/1314/trees'],
        ['title' => 'Berbagi Pengalaman Keterbukaan Informasi Publik Bersama PIP KPK', 'url' => 'https://ppid.ppatk.go.id/?p=6548'],
        ['title' => 'Rangkul Pers, Pimpinan PPATK Sambang The Jakarta Post', 'url' => 'https://www.ppatk.go.id/news/read/1032/rangkul-pers-pimpinan-ppatk-sambang-the-jakarta-post.html'],
        ['title' => 'Festival Kreatif Generasi Anti Pencucian Uang 2019', 'url' => 'https://www.ppatk.go.id/siaran_pers/read/998/tingkatkan-kesadaran-anti-pencucian-uang-pada-milenial-dengan-kreatifitas-.html'],
    ];
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
            $this->hideLegacyPressOnlyMedia();
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
    private function hideLegacyPressOnlyMedia(): void
    {
        foreach (self::RETIRED_MEDIA as $record) {
            // Do not touch manually created rows or edited seed rows. Retain the old
            // record in SQL for audit/history rather than deleting user content.
            DB::table('media_appearances')
                ->where('seed_key', hash('sha256', $record['title'].'|'.$record['url']))
                ->where('title', $record['title'])
                ->where('url', $record['url'])
                ->where('is_published', true)
                ->update(['is_published' => false, 'updated_at' => now()]);
        }
    }
}
