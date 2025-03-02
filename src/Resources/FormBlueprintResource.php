<?php

namespace Jubeki\Filament\DynamicForms\Resources;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
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

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getModelLabel(): string
    {
        return trans('dynamic-forms::resources.blueprints.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('dynamic-forms::resources.blueprints.plural');
    }

    public static function getNavigationLabel(): string
    {
        return trans('dynamic-forms::resources.blueprints.navigation');
    }

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
        ])->actions([
            ReplicateAction::make(),
            EditAction::make(),
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
