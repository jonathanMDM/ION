<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'custom_id' => $this->faker->unique()->bothify('AST-####'),
            'serial_number' => $this->faker->unique()->bothify('SN-########'),
            'model' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'purchase_date' => $this->faker->date(),
            'purchase_price' => $this->faker->randomFloat(2, 100, 5000),
            'current_value' => $this->faker->randomFloat(2, 50, 4000),
            'value' => $this->faker->randomFloat(2, 50, 4000),
            'status' => $this->faker->randomElement(['available', 'in_use', 'in_maintenance', 'retired']),
            'condition' => $this->faker->randomElement(['excellent', 'good', 'fair', 'poor']),
            'company_id' => \App\Models\Company::factory(),
            'category_id' => \App\Models\Category::factory(),
            'subcategory_id' => \App\Models\Subcategory::factory(),
            'location_id' => \App\Models\Location::factory(),
        ];
    }
}
