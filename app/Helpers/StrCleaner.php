<?php

namespace App\Helpers;

class StrCleaner
{
    /**
     * @return string[]
     */
    public static function arraifyCommasSequence(string $commasSequence): array
    {
        $elems = array_map(
            fn ($elem) => trim($elem),
            preg_split('/,\s?/', $commasSequence)
        );

        $result = array_filter($elems, fn ($elem) => strlen($elem) > 0);

        return $result;
    }
}
