<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'full_name',
    'phone',
    'email',
    'specialist_id',
    'specialty',
    'referral_status',
    'preferred_date',
    'preferred_time',
    'notes',
    'status',
    'admin_notes',
])]
class Appointment extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_BOOKED = 'booked';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * The admin workflow stages a booking request can move through.
     */
    public const STATUSES = [
        self::STATUS_NEW => 'New',
        self::STATUS_CONTACTED => 'Contacted',
        self::STATUS_BOOKED => 'Booked',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    /**
     * Whether the patient already holds a referral.
     */
    public const REFERRAL_STATUSES = [
        'yes' => 'Has referral',
        'pending' => 'Getting one',
        'no' => 'Needs guidance',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
        ];
    }

    /**
     * The specialist the patient asked to see, if any.
     */
    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    /**
     * Filter by workflow status.
     */
    public function scopeWithStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            $status && array_key_exists($status, self::STATUSES),
            fn (Builder $query) => $query->where('status', $status),
        );
    }

    /**
     * Free-text search across the patient's contact details.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        return $query->when($term !== '', function (Builder $query) use ($term) {
            $query->where(function (Builder $query) use ($term) {
                $like = '%'.$term.'%';

                $query->where('full_name', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('specialty', 'like', $like);
            });
        });
    }

    /**
     * Human readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /**
     * Human readable referral label.
     */
    public function getReferralLabelAttribute(): string
    {
        return self::REFERRAL_STATUSES[$this->referral_status] ?? 'Not specified';
    }
}
