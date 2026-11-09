<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryResource extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'topic_labels' => 'array',
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
            'collection' => $this->collection,
            'collectionLabel' => $this->collection_label,
            'title' => $this->title,
            'type' => $this->document_type,
            'typeLabel' => $this->type_label,
            'institution' => $this->institution,
            'institutionLabel' => $this->institution_label,
            'year' => ctype_digit((string) $this->year_label) ? (int) $this->year_label : $this->year_label,
            'topics' => $this->topics ?? [],
            'topicLabels' => $this->topic_labels ?? [],
            'authorLabel' => $this->author_label,
            'roleLabel' => $this->role_label,
            'summary' => $this->summary,
            'url' => $this->url,
            'language' => $this->language,
            'accessLabel' => $this->access_label,
            'sourceNote' => $this->source_note,
        ];
    }
}
