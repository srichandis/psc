<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Keep the slug column unique and derived from the model name.
     */
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model): void {
            $slug = Str::slug($model->slug ?: $model->name);

            if ($slug === '') {
                return;
            }

            $base = $slug;
            $suffix = 1;

            while (static::slugExists($model, $slug)) {
                $slug = $base.'-'.++$suffix;
            }

            $model->slug = $slug;
        });
    }

    /**
     * Determine whether another record already owns the given slug.
     */
    protected static function slugExists(Model $model, string $slug): bool
    {
        $query = static::query()->where('slug', $slug);

        if ($model->exists) {
            $query->whereKeyNot($model->getKey());
        }

        return $query->exists();
    }
}
