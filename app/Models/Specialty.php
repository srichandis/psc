<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'icon',
    'short_description',
    'full_description',
    'conditions',
    'diagnostic_services',
    'sort_order',
    'is_active',
])]
class Specialty extends Model
{
    use HasSlug;

    /**
     * Line-art icons available to specialty cards, keyed by the stored value.
     */
    public const ICONS = [
        'brain' => 'Neurology',
        'psychology' => 'Psychology',
        'psychiatry' => 'Psychiatry',
        'endocrinology' => 'Endocrinology',
        'nephrology' => 'Nephrology',
        'geriatrics' => 'Geriatric Medicine',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'diagnostic_services' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Specialists who consult within this specialty.
     */
    public function specialists(): HasMany
    {
        return $this->hasMany(Specialist::class);
    }

    /**
     * Only specialties that should appear on the public website.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Order by the manually curated position, then name.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
