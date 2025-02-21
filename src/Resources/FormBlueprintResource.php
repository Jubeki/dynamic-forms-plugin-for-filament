<?php

namespace Jubeki\Filament\DynamicForms\Resources;

use Awcodes\Mason\Mason;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages\CreateFormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages\EditFormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages\ListFormBlueprints;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\RelationManagers\FormPagesRelationManager;

class FormBlueprintResource extends Resource
{
    public static ?string $model = FormBlueprint::class;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([

                Group::make([

                    TextInput::make('handle')
                        ->label('Handle')
                        ->required(),
    
                    TextInput::make('version')
                        ->label('Version')
                        ->required(),

                ])->columns(2),

                Group::make([
                
                    TextInput::make('name.de')
                        ->label('Name (DE)')
                        ->required(),

                    TextInput::make('name.en')
                        ->label('Name (EN)')
                        ->required(),

                ])->columns(2),

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
            'index' => ListFormBlueprints::route('/'),
            'create' => CreateFormBlueprint::route('/create'),
            'edit' => EditFormBlueprint::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            FormPagesRelationManager::class,
        ];
    }
}