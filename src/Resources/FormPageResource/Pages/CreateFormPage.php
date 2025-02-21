<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;

class CreateFormPage extends CreateRecord
{
    public static string $resource = FormPageResource::class;
}