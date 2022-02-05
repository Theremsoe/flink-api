<?php

namespace Tests\Feature\Models;

use App\Models\Company;
use ArrayObject;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @testdox Test Company model integration with COMPANY table
 *
 * @internal
 * @coversNothing
 */
class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @testdox Check that model not corrupt data with database table
     */
    public function testIntegrity(): void
    {
        /** @var \App\Models\Company */
        $company = Company::factory()->create();

        static::assertIsString($company->id);
        static::assertIsString($company->name);
        static::assertIsString($company->description);
        static::assertIsString($company->symbol);
        static::assertInstanceOf(ArrayObject::class, $company->market);

        $this->assertDatabaseHas(
            $company->getTable(),
            [
                'id' => $company->id,
                'name' => $company->name,
                'description' => $company->description,
                'symbol' => $company->symbol,
                'market' => json_encode($company->market),
            ]
        );
    }

    /**
     * @testdox Check that name column doesn't accept null values
     */
    public function testNullableName(): void
    {
        $this->expectException(QueryException::class);

        Company::factory()->create(['name' => null]);
    }

    /**
     * @testdox Check that description column does accept null values
     */
    public function testNullableDescription(): void
    {
        $this->expectException(QueryException::class);

        Company::factory()->create(['description' => null]);
    }

    /**
     * @testdox Check that symbol column does accept null values
     */
    public function testNullableSymbol(): void
    {
        $this->expectException(QueryException::class);

        Company::factory()->create(['symbol' => null]);
    }

    /**
     * @testdox Check that market column does accept null values
     */
    public function testNullableMarket(): void
    {
        $this->expectException(QueryException::class);

        Company::factory()->create(['market' => null]);
    }

    /**
     * @testdox Check that market column store right JSONn data
     */
    public function testCasteableMarkets(): void
    {
        $markets = ['one', 'two', 'three'];

        /** @var \App\Models\Company */
        $company = Company::factory()->create(['market' => $markets]);

        static::assertSame($company->market->getArrayCopy(), $markets);
    }
}
