<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Filament\Forms\Components\Group as FormGroup;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group as InfolistGroup;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Jubeki\Filament\DynamicForms\Models\CustomFieldSet;

class ContentBrick extends DynamicBrick
{
    public static string $identifier = 'content_brick';

    public static function make(): Brick
    {
        return static::brick()->label('Content');
    }

    public static function schema(): array
    {
        return [

            TextInput::make('handle')
                ->label('Handle')
                ->required(),

            FormGroup::make([
                TextInput::make('label.de')
                    ->label('Label (DE)'),

                TextInput::make('label.en')
                    ->label('Label (EN)'),
            ]),

            RichEditor::make('content.de')
                ->label('Content (DE)')
                ->required(),

            RichEditor::make('content.en')
                ->label('Content (EN)')
                ->required(),
        ];
    }

    public function form(): Placeholder
    {

        return Placeholder::make($this->data['handle'])
            ->label($label = $this->localized('label'))
            ->hiddenLabel($label === null)
            ->content($this->sanitizedContent());
    }

    public function infolist(): TextEntry
    {
        return TextEntry::make($this->data['handle'])
            ->label($label = $this->localized('label'))
            ->hiddenLabel($label === null)
            ->state($this->sanitizedContent());
    }

    protected function sanitizedContent(): HtmlString
    {
        return new HtmlString(
            Str::of($this->localized('content'))->sanitizeHtml()
        );
    }
}
