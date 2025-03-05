<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormPageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Jubeki\Filament\DynamicForms\Models\FormBlueprint;
use Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource;
use Jubeki\Filament\DynamicForms\Resources\FormPageResource;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;

class CreateFormPage extends CreateRecord
{
    public static string $resource = FormPageResource::class;

    #[Locked]
    #[Url('blueprint')]
    public ?string $blueprintId;

    public function getTitle(): string
    {
        return $this->blueprint->name . " - Neue Seite anlegen";
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
        return FormBlueprint::findOrFail($this->blueprintId);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['blueprint_id'] = $this->blueprintId;

        return static::getModel()::create($data);
    }
}
