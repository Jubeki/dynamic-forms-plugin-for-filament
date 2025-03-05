<?php

namespace Jubeki\Filament\DynamicForms\Actions;

use Filament\Actions\ReplicateAction;
use Filament\Support\Enums\MaxWidth;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;

class NewVersionAction
{
    public static function make(): ReplicateAction
    {
        return ReplicateAction::make('create_new_version')
            ->label('Neue Version zum Bearbeiten erstellen')
            ->beforeReplicaSaved(function (FormBlueprint $replica): void {
                $replica->version = FormBlueprint::where('handle', $replica->handle)->max('version') + 1;
                $replica->published_at = null;
                $replica->archived_at = null;
            })
            ->after(function (FormBlueprint $replica, FormBlueprint $record): void {
                $replica->pages()->createMany(
                    $record->pages->map->replicate()->toArray()
                );
            })
            ->modalHeading('Neue Version zum Bearbeiten erstellen')
            ->modalDescription('Es ist aus technischen Gründen nicht möglich Änderungen an einem bereits veröffentlichen Blueprint zu machen. Erstellen Sie eine neue Version, um Änderungen vorzunehmen. Die alte Version bleibt solange verfügbar.')
            ->modalSubmitActionLabel('Neue Version erstellen')
            ->modalWidth(MaxWidth::ExtraLarge)
            ->visible(fn (FormBlueprint $record) => $record->isPublished() && $record->isLatest())
            ->successRedirectUrl(fn (FormBlueprint $replica) => FormBlueprintResource::getUrl('edit', [$replica]));
    }
}
