<?php

namespace Jubeki\Filament\DynamicForms\Models;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Wizard;
use Filament\Infolists\Components\Tabs as InfolistTabs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jubeki\Filament\DynamicForms\Database\Factories\FormBlueprintFactory;

#[UseFactory(FormBlueprintFactory::class)]
class FormBlueprint extends Model
{
    use HasTranslations, HasFactory;

    public $translatable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'dynamic_form_blueprints';

    public function pages(): HasMany
    {
        return $this->hasMany(FormPage::class);
    }

    public function form(bool $disableRequiredCheck = false, string $prefix = '', bool $asTabs = false): Wizard|Tabs
    {
        // $dependencies = $this->fieldsDependedOn();
        $dependencies = null;

        if($asTabs) {
            return Tabs::make()->schema(
                $this->pages->map->form($dependencies, $disableRequiredCheck, $prefix, asTab: true)->all()
            );
        }

        return Wizard::make($this->pages->map->form($dependencies, $disableRequiredCheck)->all());
    }

    public function infolist(string $prefix = ''): InfolistTabs
    {
        return InfolistTabs::make()->schema($this->pages->map->infolist($prefix)->all());
    }

    public function fieldsDependedOn(): array
    {
        return $this->pages->map->fieldsDependedOn()->flatten()->unique()->values()->all();
    }

    public function isLatest(): bool
    {
        return $this->version === FormBlueprint::where('handle', $this->handle)->max('version');
    }

    public function canBeArchived(): bool
    {
        return $this->archived_at === null;
    }

    public function canBePublished(): bool
    {
        return $this->published_at === null;
    }

    public function canBeUpdated(): bool
    {
        return $this->archived_at === null && $this->published_at === null;
    }

    public function isPublished(): bool
    {
        return $this->archived_at === null && $this->published_at !== null;
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }
}
