<?php

namespace App\Models\Support;

use DateTime;

/**
 * Homologate property "dateFormat" with a format that support milliseconds.
 */
trait DateFormatteable
{
    /**
     * Get the format for database stored dates.
     */
    public function getDateFormat(): string
    {
        return DateTime::RFC3339_EXTENDED;
    }
}
