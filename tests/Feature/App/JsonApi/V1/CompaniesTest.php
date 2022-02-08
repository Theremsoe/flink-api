<?php

namespace Tests\Feature\App\JsonApi\V1;

use App\Models\Company;
use DateTimeInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Tests\TestCase;

/**
 * @testdox Test Company resource JSON API
 *
 * @internal
 * @coversNothing
 */
class CompaniesTest extends TestCase
{
    use MakesJsonApiRequests;
    use RefreshDatabase;

    /**
     * @testdox Check that company list returns a JSON API resources
     */
    public function testList(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\Company> */
        $companies = Company::factory()->count(3)->create();

        $this->jsonApi()
            ->expects('company')
            ->get(route('api.v1.company.list'))
            ->assertFetchedMany($companies)
        ;
    }

    /**
     * @testdox Check that company list returns a JSON API resources empty
     */
    public function testListEmpty(): void
    {
        $this->jsonApi()
            ->expects('company')
            ->get(route('api.v1.company.list'))
            ->assertFetchedNone()
        ;
    }

    /**
     * @testdox Check that company read returns a JSON API resource
     */
    public function testRead(): void
    {
        /** @var \App\Models\Company */
        $company = Company::factory()->create();

        $this->jsonApi()
            ->expects('company')
            ->get(route('api.v1.company.read', $company->id))
            ->assertFetchedOne($company)
        ;
    }

    /**
     * @testdox Check that company create store a new element in database
     */
    public function testCreated(): void
    {
        /** @var \App\Models\Company */
        $company = Company::factory()->make();

        /** @var array */
        $payload = [
            'type' => 'company',
            'attributes' => [
                'name' => $company->name,
                'description' => $company->description,
                'symbol' => $company->symbol,
                'market' => $company->market->toArray(),
            ],
        ];

        /** @var \LaravelJsonApi\Testing\TestResponse */
        $response = $this->jsonApi()
            ->expects('company')
            ->withData($payload)
            ->post(route('api.v1.company.create'))
            ->assertCreatedWithServerId(route('api.v1.company.create'), $payload)
        ;

        $this->assertDatabaseHas($company->getTable(), [
            'id' => $response->json('data.id'),
            'name' => $company->name,
            'description' => $company->description,
            'symbol' => $company->symbol,
        ]);
    }

    /**
     * @testdox Check that company update store a element in database
     */
    public function testUpdated(): void
    {
        /** @var \App\Models\Company */
        $company = Company::factory()->create();

        /** @var \App\Models\Company */
        $patch = Company::factory()->make();

        /** @var array */
        $payload = [
            'id' => $company->id,
            'type' => 'company',
            'attributes' => [
                'name' => $patch->name,
                'description' => $patch->description,
                'symbol' => $patch->symbol,
                'market' => $patch->market,
            ],
        ];

        $this->jsonApi()
            ->expects('company')
            ->withData($payload)
            ->patch(route('api.v1.company.update', $company->id))
            ->assertFetchedOne($company)
        ;

        $this->assertDatabaseHas($company->getTable(), [
            'id' => $company->getKey(),
            'name' => $patch->name,
            'description' => $patch->description,
            'symbol' => $patch->symbol,
        ]);
    }

    /**
     * @testdox Check that company delete a stored element (soft delete)
     */
    public function testDeleted(): void
    {
        /** @var \App\Models\Company */
        $company = Company::factory()->create();

        /** @var array */
        $payload = [
            'id' => $company->id,
            'type' => 'company',
            'attributes' => [
                'deletedAt' => now()->format(DateTimeInterface::RFC3339_EXTENDED),
            ],
        ];

        $this->jsonApi()
            ->expects('company')
            ->withData($payload)
            ->patch(route('api.v1.company.update', $company->getKey()))
        ;

        $this->assertSoftDeleted($company);
    }

    /**
     * @testdox Check that company delete a stored element (force)
     */
    public function testForceDelete(): void
    {
        /** @var \App\Models\Company */
        $company = Company::factory()->create();

        $this->jsonApi()
            ->expects('company')
            ->delete(route('api.v1.company.delete', $company->getKey()))
            ->assertNoContent()
        ;

        $this->assertModelMissing($company);
    }

    /**
     * @testdox Check that json api server returns a not found schema
     */
    public function testNotFound(): void
    {
        $this->jsonApi()
            ->expects('company')
            ->get(route('api.v1.company.read', Str::uuid()))
            ->assertErrorStatus(['status' => '404', 'title' => 'Not Found'])
        ;
    }

    /**
     * @testdox Check that json api server returns an unprocessable entity schema
     */
    public function testUnprocessableEntity(): void
    {
        /** @var array */
        $payload = [
            'type' => 'company',
            'attributes' => [
                'name' => '',
                'description' => '',
                'symbol' => '',
                'market' => [],
            ],
        ];

        $this->jsonApi()
            ->expects('company')
            ->withData($payload)
            ->post(route('api.v1.company.create'))
            ->assertErrors(422, [
                [
                    'detail' => 'The name field is required.',
                    'title' => 'Unprocessable Entity',
                    'status' => '422',
                    'source' => ['pointer' => '/data/attributes/name'],
                ],
                [
                    'detail' => 'The description field is required.',
                    'title' => 'Unprocessable Entity',
                    'status' => '422',
                    'source' => ['pointer' => '/data/attributes/description'],
                ],
                [
                    'detail' => 'The symbol field is required.',
                    'title' => 'Unprocessable Entity',
                    'status' => '422',
                    'source' => ['pointer' => '/data/attributes/symbol'],
                ],
                [
                    'detail' => 'The market field is required.',
                    'title' => 'Unprocessable Entity',
                    'status' => '422',
                    'source' => ['pointer' => '/data/attributes/market'],
                ],
            ])
        ;
    }
}
