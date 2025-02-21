<?php

namespace Jubeki\Filament\DynamicForms;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;

class DynamicFormsPlugin implements Plugin
{    
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'dynamic-forms';
    }
 
    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                FormBlueprintResource::class,
                FormPageResource::class,
            ])
            ->assets([
                Css::make('dynamic-forms', __DIR__ . '/../resources/dist/plugin.css'),
            ]);
    }
 
    public function boot(Panel $panel): void
    {
        //
    }
}