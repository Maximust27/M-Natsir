<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaAppearance extends Model
{
    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'year' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'category' => $this->category,
            'categoryLabel' => $this->category_label,
            'badgeVariant' => $this->badge_variant,
            'date' => $this->date_label,
            'year' => $this->year,
            'outlet' => $this->outlet,
            'role' => $this->role,
            'title' => $this->title,
            'summary' => $this->summary,
            'topics' => $this->topics ?? [],
            'url' => $this->url,
            'actionLabel' => $this->action_label,
            'thumbnail' => $this->thumbnail_url,
            'sourceNote' => $this->source_note,
        ];
    }
}
