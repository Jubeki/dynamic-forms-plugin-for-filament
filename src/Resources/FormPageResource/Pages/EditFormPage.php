<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;
use Livewire\Attributes\Computed;

class EditFormPage extends EditRecord
{
    public static string $resource = FormPageResource::class;

    public function getTitle(): string
    {
        return $this->blueprint->name . " - Seite bearbeiten";
    }

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        if(! $this->blueprint->canBeUpdated()) {
            $this->redirect(FormBlueprintResource::getUrl('edit', [$this->blueprint]));
        }
    }

    #[Computed]
    public function blueprint(): FormBlueprint
    {
        return $this->getRecord()->blueprint;
    }
}
