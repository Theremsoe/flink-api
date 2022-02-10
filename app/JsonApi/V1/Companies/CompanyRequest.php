<?php

namespace App\JsonApi\V1\Companies;

use DateTime;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule;

class CompanyRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'required|max:100',
            'symbol' => 'required|max:10',
            'market' => 'required|array',
            'market.*' => 'required|array',
            'market.*.datetime' => 'required|date_format:'.DateTime::RFC3339_EXTENDED,
            'market.*.value' => 'required|numeric',
            'deletedAt' => ['nullable', Rule::dateTime()],
        ];
    }
}
