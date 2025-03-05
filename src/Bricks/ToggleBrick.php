<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;

class ToggleBrick extends DynamicBrick
{
    public static string $identifier = 'toggle_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Toggle');
    }

    public function form(): Toggle
    {
        return $this->configureForForm(Toggle::class);
    }

    public function infolist(): TextEntry
    {
        return $this->configureForInfolist(TextEntry::class)
            ->badge()
            ->color(fn (?bool $state) => match ($state) {
                true => 'success',
                false => 'danger',
                default => null,
            })
            ->formatStateUsing(fn (?bool $state) => match ($state) {
                true => 'Accepted',
                false => 'Declined',
                default => null,
            });
    }
}
