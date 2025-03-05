<?php

namespace Jubeki\Filament\DynamicForms\Models;

use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\Components\Tabs\Tab as InfolistTab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Jubeki\Filament\DynamicForms\Bricks\DynamicBrick;
use Spatie\Translatable\HasTranslations;

class FormPage extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'dynamic_form_pages';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
        ];
    }

    public function blueprint(): BelongsTo
    {
        return $this->belongsTo(FormBlueprint::class, 'form_blueprint_id');
    }

    /**
     * @param  list<string>  $dependencies
     */
    public function form(?array $dependencies = null, bool $disableRequiredCheck = false, string $prefix = '', bool $asTab = false): Step|Tab
    {
        $dependencies ??= $this->fieldsDependedOn();
        $component = $asTab ? Tab::class : Step::class;

        return $component::make($this->name)->schema($this->formSchema($dependencies, $disableRequiredCheck, $prefix));
    }

    public function infolist(string $prefix = ''): InfolistTab
    {
        return InfolistTab::make($this->name)->schema($this->infolistSchema($prefix));
    }

    /**
     * @param  list<string>  $dependencies
     */
    public function formSchema(array $dependencies, bool $disableRequiredCheck = false, string $prefix = ''): array
    {
        return collect($this->fields['content'])->map(
            fn ($content) => DynamicBrick::resolve(
                $content['attrs']['identifier'],
                $content['attrs']['values'],
                $dependencies,
                $disableRequiredCheck,
                $prefix,
            )->form(),
        )->flatten()->all();
    }

    public function infolistSchema(string $prefix = ''): array
    {
        return collect($this->fields['content'])->map(
            fn ($content) => DynamicBrick::resolve(
                $content['attrs']['identifier'],
                $content['attrs']['values'],
                prefix: $prefix,
            )->infolist($prefix),
        )->flatten()->all();
    }

    public function fieldsDependedOn(): array
    {
        return Arr::map(
            $this->fields['content'],
            fn ($content) => Arr::pluck($content['attrs']['values']['visible_conditions'] ?? [], 'field'),
        );
    }
}
