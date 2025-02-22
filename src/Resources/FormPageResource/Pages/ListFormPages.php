<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;

class ListFormPages extends ListRecords
{
    public static string $resource = FormPageResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
