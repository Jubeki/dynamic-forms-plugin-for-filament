<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;

class CheckboxBrick extends DynamicBrick
{
    public static string $identifier = 'checkbox_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Checkbox');
    }

    public function form(): Checkbox
    {
        return $this->configureForForm(Checkbox::class)
            ->accepted($this->data['required']);
    }

    public function infolist(): TextEntry
    {
        return $this->configureForInfolist(TextEntry::class)
            ->badge()
            ->color(fn(?bool $state) => match($state) {
                true => 'success',
                false => 'danger',
                default => null,
            })
            ->formatStateUsing(fn(?bool $state) => match($state) {
                true => 'Accepted',
                false => 'Declined',
                default => null,
            });
    }
}
