<?php

namespace Jubeki\Filament\DynamicForms\Resources;

use Awcodes\Mason\Mason;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Jubeki\Filament\DynamicForms\Bricks\DynamicBrick;
use Jubeki\Filament\DynamicForms\Models\FormPage;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages\CreateFormPage;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages\EditFormPage;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages\ListFormPages;

class FormPageResource extends Resource
{
    public static ?string $model = FormPage::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function getModelLabel(): string
    {
        return trans('dynamic-forms::resources.pages.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('dynamic-forms::resources.pages.plural');
    }

    public static function getNavigationLabel(): string
    {
        return trans('dynamic-forms::resources.pages.navigation');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([

                Group::make([

                    TextInput::make('name.de')
                        ->label('Name (DE)')
                        ->required(),

                    TextInput::make('name.en')
                        ->label('Name (EN)')
                        ->required(),

                ])->columns(2),

                Mason::make('fields')
                    ->bricks(
                        Arr::map(DynamicBrick::$bricks, fn ($brick) => $brick::make())
                    ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name'),
        ]);
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListFormPages::route('/'),
            'create' => CreateFormPage::route('/create'),
            'edit' => EditFormPage::route('/{record}/edit'),
        ];
    }
}
