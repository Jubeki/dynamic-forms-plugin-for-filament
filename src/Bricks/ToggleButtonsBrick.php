<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class ToggleButtonsBrick extends DynamicBrick
{
    public static string $identifier = 'toggle_buttons_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Toggle Buttons');
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

    public function form(): ToggleButtons
    {
        return $this->configureForForm(ToggleButtons::class)
            ->inline()
            ->grouped()
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
