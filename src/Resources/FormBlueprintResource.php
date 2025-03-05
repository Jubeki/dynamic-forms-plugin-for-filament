<?php

namespace Jubeki\Filament\DynamicForms\Resources;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages\CreateFormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages\EditFormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages\ListFormBlueprints;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\RelationManagers\FormPagesRelationManager;
use Livewire\Component as Livewire;

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
                        ->required()
                        ->disabled(fn (Livewire $livewire) => ! $livewire instanceof CreateFormBlueprint),

                    TextInput::make('version')
                        ->label('Version')
                        ->default('1')
                        ->disabled(),

                ])->columns(2),

                Group::make([

                    TextInput::make('name.de')
                        ->label('Name (DE)')
                        ->required(),

                    TextInput::make('name.en')
                        ->label('Name (EN)')
                        ->required(),

                ])->columns(2),

                ...$form->getRecord()->canBeUpdated() ? [] : [
                    $form->getRecord()->form(prefix: 'preview.', asTabs: true)->disabled(),
                ],

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('version'),
                TextColumn::make('status')
                    ->badge()
                    ->state(fn (FormBlueprint $record) => match (true) {
                        $record->isArchived() => 'archived',
                        $record->isPublished() => 'published',
                        default => 'editing',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'published' => 'Veröffentlicht',
                        'archived' => 'Archiviert',
                        default => 'In Bearbeitung',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'warning',
                    }),
            ])->actions([

            ViewAction::make(),

            ReplicateAction::make()
                ->form([
                    TextInput::make('handle')
                        ->label('Neues Handle')
                        ->required()
                        ->unique(FormBlueprint::class, 'handle'),

                    TextInput::make('name.de')
                        ->label('Neuer Name (DE)')
                        ->required(),

                    TextInput::make('name.en')
                        ->label('Neuer Name (EN)')
                        ->required(),
                ])
                ->mutateRecordDataUsing(function (array $data): array {

                    $data['handle'] = $data['handle'].'-copy';
                    $data['name'] = [
                        'de' => $data['name']['de'].' (Kopie)',
                        'en' => $data['name']['en'].' (Copy)',
                    ];

                    return $data;
                })
                ->beforeReplicaSaved(function (FormBlueprint $replica): void {
                    $replica->version = 1;
                    $replica->published_at = null;
                    $replica->archived_at = null;
                })
                ->after(function (FormBlueprint $replica, FormBlueprint $record): void {
                    $replica->pages()->createMany(
                        $record->pages->map->replicate()->toArray()
                    );
                }),
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
