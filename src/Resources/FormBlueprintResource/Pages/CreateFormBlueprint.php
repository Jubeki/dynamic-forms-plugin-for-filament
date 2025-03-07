<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;

class CreateFormBlueprint extends CreateRecord
{
    public static string $resource = FormBlueprintResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $data['version'] = '1';

        return parent::handleRecordCreation($data);
    }
}
