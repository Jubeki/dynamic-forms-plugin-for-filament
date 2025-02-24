<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class SelectBrick extends DynamicBrick
{
    public static string $identifier = 'select_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Select');
    }

    public static function schema(): array
    {
        return DynamicBrick::defaultSchema([

            TableRepeater::make('options')
                ->headers([
                    Header::make('value')->label('Value'),
                    Header::make('label.de')->label('Label (DE)'),
                    Header::make('label.en')->label('Label (EN)'),
                ])
                ->schema([
                    TextInput::make('value')->required(),
                    TextInput::make('label.de')->required(),
                    TextInput::make('label.en')->required(),
                ]),

        ]);
    }

    public function form(): Select
    {
        return $this->configureForForm(Select::class)
            ->options(
                Arr::mapWithKeys(
                    $this->array('options') ?? [],
                    fn ($option) => [$option['value'] => $option['label'][App::getLocale()]]
                ),
            );
    }

    public function infolist(): TextEntry
    {
        return $this->configureForInfolist(TextEntry::class)
            ->formatStateUsing(function ($value) {
                return $this->localizedArray('options')[$value] ?? $value;
            });
    }
}
