<?php

namespace Jubeki\Filament\DynamicForms\Resources;

use Awcodes\Mason\Mason;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Jubeki\Filament\DynamicForms\Bricks\DynamicBrick;
use Jubeki\Filament\DynamicForms\Models\CustomFieldSet;
use Jubeki\Filament\DynamicForms\Models\FormPage;
use Jubeki\Filament\DynamicForms\Resources\CustomFieldSetResource\Pages\CreateCustomFieldSet;
use Jubeki\Filament\DynamicForms\Resources\CustomFieldSetResource\Pages\EditCustomFieldSet;
use Jubeki\Filament\DynamicForms\Resources\CustomFieldSetResource\Pages\ListCustomFieldSets;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages\CreateFormPage;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages\EditFormPage;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages\ListFormPages;

class CustomFieldSetResource extends Resource
{
    public static ?string $model = CustomFieldSet::class;

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
            'index' => ListCustomFieldSets::route('/'),
            'create' => CreateCustomFieldSet::route('/create'),
            'edit' => EditCustomFieldSet::route('/{record}/edit'),
        ];
    }
}
