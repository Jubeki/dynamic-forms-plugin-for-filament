<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;

class TextInputBrick extends DynamicBrick
{
    public static string $identifier = 'text_input_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Text Input');
    }

    public function form(): TextInput
    {
        return $this->configureForForm(TextInput::class);
    }

    public function infolist(): TextEntry
    {
        return $this->configureForInfolist(TextEntry::class);
    }
}
