<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\RelationManagers;

use App\Filament\Admin\Resources\SubmissionResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;

class FormPagesRelationManager extends RelationManager
{
    protected static string $relationship = 'pages';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->url(FormPageResource::getUrl('create')),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
