<?php

namespace Jubeki\Filament\DynamicForms\Actions;

use Filament\Actions\Action;
use Filament\Actions\ReplicateAction;
use Filament\Support\Enums\MaxWidth;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;

class PublishAction
{
    public static function make(): Action
    {
        return Action::make('publish')
            ->label('Veröffentlichen')
            ->action(function(FormBlueprint $record) {
                FormBlueprint::where('handle', $record->handle)
                    ->where('version', '<', $record->version)
                    ->whereNull('archived_at')
                    ->update(['archived_at' => now()]);

                $record->update(['published_at' => now()]);
            })
            ->visible(fn (FormBlueprint $record) => ! $record->isPublished())
            ->requiresConfirmation()
            ->modalDescription('Nach der Veröffentlichung sind keine Änderungen mehr möglich, außer es wird eine neue Version erstellt.')
            ->modalSubmitActionLabel('Veröffentlichen')
            ->color('success');
    }
}