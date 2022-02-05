<?php

namespace App\Models\Support;

use Illuminate\Support\Str;

/**
 * Normalize "Eloquent" model names to singular with uppercase letter.
 */
trait Normalizable
{
    public function getTable(): string
    {
        return $this->table ?? Str::of(class_basename($this))->snake()->upper()->__toString();
    }
}
