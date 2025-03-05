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
            'type' => fake()->slug(),
            'version' => 1,
            'name' => [
                'de' => fake()->words(3),
                'en' => fake()->words(3),
            ],
        ];
    }
}
