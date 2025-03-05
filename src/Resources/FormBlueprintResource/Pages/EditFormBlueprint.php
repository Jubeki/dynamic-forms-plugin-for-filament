<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Jubeki\Filament\DynamicForms\Actions\NewVersionAction;
use Jubeki\Filament\DynamicForms\Actions\PublishAction;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;

class EditFormBlueprint extends EditRecord
{
    public static string $resource = FormBlueprintResource::class;

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getHeaderActions(): array
    {
        return [
            NewVersionAction::make(),
            PublishAction::make(),
        ];
    }
}
