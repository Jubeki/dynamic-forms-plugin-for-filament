<?php

namespace Jubeki\Filament\DynamicForms\Resources\FormBlueprintResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

    public function getTabs(): array
    {
        return [
            'dashboard' => Tab::make('Übersicht')->modifyQueryUsing(function (Builder $query) {
                $query->whereRaw('id IN (SELECT max(id) FROM dynamic_form_blueprints WHERE archived_at IS NULL group by handle)')
                ->orderBy('handle');
            }),

            'published' => Tab::make('Veröffentlicht')->modifyQueryUsing(function (Builder $query) {
                $query->whereNotNull('published_at')->whereNull('archived_at')->orderBy('handle');
            }),

            'archived' => Tab::make('Archiviert')->modifyQueryUsing(function (Builder $query) {
                $query->whereNotNull('archived_at')->orderBy('created_at', 'desc');
            }),
        ];
    }
}
