<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Setup model for use UUID values as keys.
 */
trait UuidIdentifiable
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (Model $model): void {
            if ($model->getKeyName()) {
                $model->setAttribute($model->getKeyName(), (string) Str::orderedUuid());
            }
        });
    }
}
