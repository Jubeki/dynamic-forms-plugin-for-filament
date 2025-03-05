<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;

class FormPagesRelationManager extends RelationManager
{
    protected static string $relationship = 'pages';
    
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->canBeUpdated();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('sort')
            ->defaultSort(function(Builder $query) {
                $query->orderBy('sort')->orderBy('id');
            })
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->url(FormPageResource::getUrl('create', [
                    'blueprint' => $this->getOwnerRecord()->getKey(),
                ])),
            ])
            ->actions([
                ReplicateAction::make(),
                EditAction::make()
                    ->url(fn ($record) => FormPageResource::getUrl('edit', [$record])),
            ])
            ->bulkActions([
                //
            ]);
    }
}
