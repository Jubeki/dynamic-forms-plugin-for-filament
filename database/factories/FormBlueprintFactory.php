<?php

namespace Jubeki\Filament\DynamicForms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;

class FormBlueprintFactory extends Factory
{
    protected $model = FormBlueprint::class;

    public function definition()
    {
        return [
            'handle' => fake()->slug(),
            'version' => 1,
            'name' => [
                'de' => fake()->sentence(),
                'en' => fake()->sentence(),
            ],
        ];
    }
}
