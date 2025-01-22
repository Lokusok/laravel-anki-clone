<?php

namespace App\Helpers;

class StrCleaner
{
    /**
     * @return string[]
     */
    public static function arraifyCommasSequence(string $commasSequence): array
    {
        return array_map(
            fn ($elem) => trim($elem),
            preg_split('/,\s?/', $commasSequence)
        );
    }
}
