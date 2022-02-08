<?php

namespace App\JsonApi\V1\Companies;

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
            'market.*' => 'required|string|distinct',
            'deletedAt' => ['nullable', Rule::dateTime()],
        ];
    }
}
