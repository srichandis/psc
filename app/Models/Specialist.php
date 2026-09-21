<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'qualifications',
    'role',
    'specialty_id',
    'image',
    'bio',
    'consulting_days',
    'special_interests',
    'profile_sections',
    'sort_order',
    'is_active',
])]
class Specialist extends Model
{
    use HasSlug;

    protected function casts(): array
    {
        return [
            'special_interests' => 'array',
            'profile_sections' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * The ordered content blocks shown on the public profile page.
     *
     * Each block is ['heading' => ?string, 'type' => 'list'|'paragraphs',
     * 'items' => string[]]. Blocks without items are skipped so a partially
     * filled form never renders an empty heading.
     *
     * @return array<int, array{heading: ?string, type: string, items: array<int, string>}>
     */
    public function profileBlocks(): array
    {
        return collect($this->profile_sections ?? [])
            ->map(fn (array $block) => [
                'heading' => trim((string) ($block['heading'] ?? '')) ?: null,
                'type' => ($block['type'] ?? 'list') === 'paragraphs' ? 'paragraphs' : 'list',
                'items' => array_values(array_filter(
                    array_map(fn ($item) => trim((string) $item), $block['items'] ?? []),
                    fn (string $item) => $item !== '',
                )),
            ])
            ->filter(fn (array $block) => $block['items'] !== [])
            ->values()
            ->all();
    }

    /**
     * The public profile page for this specialist.
     *
     * The slug is passed explicitly: the route binds on `{specialist:slug}`,
     * so handing over the model alone would generate the numeric id.
     */
    public function profileUrl(): string
    {
        return route('specialists.show', $this->slug);
    }

    /**
     * The specialty this specialist consults within.
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Booking requests directed at this specialist.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Only specialists that should appear on the public website.
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
