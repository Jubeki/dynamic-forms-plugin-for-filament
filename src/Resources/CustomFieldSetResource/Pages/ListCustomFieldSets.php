<?php

namespace Jubeki\Filament\DynamicForms\Resources\CustomFieldSetResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Jubeki\Filament\DynamicForms\Resources\CustomFieldSetResource;

class ListCustomFieldSets extends ListRecords
{
    public static string $resource = CustomFieldSetResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
