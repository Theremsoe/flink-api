<?php

namespace Database\Factories;

use App\Models\Company;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(50),
            'description' => $this->faker->text(100),
            'symbol' => $this->faker->text(10),
            'market' => Collection::times(
                $this->faker->randomDigitNotZero(),
                fn ($key): array => [
                    'datetime' => now()->addDay($key)->format(DateTime::RFC3339_EXTENDED),
                    'value' => $this->faker->randomFloat(nbMaxDecimals: 2, max: 100),
                ]
            ),
        ];
    }
}
