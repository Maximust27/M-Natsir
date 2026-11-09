<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_primary_source' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Preserve the existing public card API while the data is stored in SQL.
     *
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'category' => $this->category,
            'categoryLabel' => $this->category_label,
            'badgeClasses' => $this->badge_classes,
            'source' => $this->source,
            'date' => $this->date_label,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'url' => $this->url,
            'accessLabel' => $this->access_label,
            'isPrimarySource' => $this->is_primary_source,
            'sourceNote' => $this->source_note,
        ];
    }
}
