<?php

namespace App\Http\Controllers\Admin\Concerns;

trait HasBulkMessages
{
    /**
     * Build a readable confirmation such as "3 requests marked as Booked".
     */
    protected function bulkMessage(int $count, string $noun, string $verb): string
    {
        $label = trans_choice(":count {$noun}|:count {$noun}s", $count, ['count' => $count]);

        return "{$label} {$verb}.";
    }
}
