<?php

namespace Jubeki\Filament\DynamicForms\Models;

use Filament\Forms\Components\Wizard;
use Filament\Infolists\Components\Tabs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class FormBlueprint extends Model
{
    use HasTranslations;

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

    public function form(): Wizard
    {
        $dependencies = $this->fieldsDependedOn();

        return Wizard::make($this->pages->map->form($dependencies)->all());
    }

    public function infolist(): Tabs
    {
        return Tabs::make($this->pages->map->infolist()->all());
    }

    public function fieldsDependedOn(): array
    {
        return $this->pages->map->fieldsDependedOn()->flatten()->unique()->values()->all();
    }
}
