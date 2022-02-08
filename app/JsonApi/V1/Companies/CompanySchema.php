<?php

namespace App\JsonApi\V1\Companies;

use App\Models\Company;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Support\Carbon;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\SoftDelete;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\SoftDeletes;

class CompanySchema extends Schema
{
    use SoftDeletes;

    /**
     * The model the schema corresponds to.
     */
    public static string $model = Company::class;

    /**
     * The resource type as it appears in URIs.
     */
    protected ?string $uriType = 'company';

    /**
     * Default pagination.
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * Determine if the resource is authorizable.
     */
    public function authorizable(): bool
    {
        return false;
    }

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make('id')->uuid(),
            Str::make('name'),
            Str::make('description'),
            Str::make('symbol'),
            ArrayList::make('market')->serializeUsing(static fn (ArrayObject $value): array => $value->toArray()),
            DateTime::make('createdAt')->sortable()->serializeUsing(static fn (Carbon $value): string => $value->format(DateTimeInterface::RFC3339_EXTENDED)),
            DateTime::make('updatedAt')->sortable()->serializeUsing(static fn (Carbon $value): string => $value->format(DateTimeInterface::RFC3339_EXTENDED)),
            SoftDelete::make('deletedAt')->sortable()->serializeUsing(static fn (?Carbon $value): ?string => $value?->format(DateTimeInterface::RFC3339_EXTENDED)),
        ];
    }

    /**
     * Get the resource filters.
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make('name'),
            Where::make('description'),
            Where::make('symbol'),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }

    /**
     * Get the JSON:API resource type.
     */
    public static function type(): string
    {
        return 'company';
    }
}
