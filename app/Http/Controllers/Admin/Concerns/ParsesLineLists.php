<?php

namespace App\Http\Controllers\Admin\Concerns;

trait ParsesLineLists
{
    /**
     * Convert a textarea value (one item per line) into a clean list.
     *
     * @return array<int, string>
     */
    protected function parseLineList(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
