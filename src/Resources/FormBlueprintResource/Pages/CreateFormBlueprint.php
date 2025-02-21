<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;

class CreateFormBlueprint extends CreateRecord
{
    public static string $resource = FormBlueprintResource::class;
}