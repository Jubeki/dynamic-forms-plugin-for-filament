<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Filament\Forms\Components\Select;
use Jubeki\Filament\DynamicForms\Models\CustomFieldSet;

class CustomFieldSetBrick extends DynamicBrick
{
    public static string $identifier = 'custom_field_set_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Custom Field Set');
    }

    public static function schema(): array
    {
        return [
            Select::make('dynamic_custom_field_set_id')
                ->label('Custom Field Set')
                ->options(fn () => CustomFieldSet::query()->pluck('name', 'id'))
                ->required(),
        ];
    }

    public function form(): array
    {
        return CustomFieldSet::find($this->data['dynamic_custom_field_set_id'])
            ?->form($this->dependencies, $this->disableRequiredCheck, $this->prefix)
            ?? [];
    }

    public function infolist(): array
    {
        return CustomFieldSet::find($this->data['dynamic_custom_field_set_id'])
            ?->infolist($this->prefix)
            ?? [];
    }
}
