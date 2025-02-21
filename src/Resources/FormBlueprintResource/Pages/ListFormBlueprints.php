<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;

class ListFormBlueprints extends ListRecords
{
    public static string $resource = FormBlueprintResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}