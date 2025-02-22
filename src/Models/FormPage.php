<?php

namespace Jubeki\Filament\DynamicForms\Models;

use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\Components\Tabs\Tab;
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
    public function form(array $dependencies): Step
    {
        $dependencies ??= $this->fieldsDependedOn();

        return Step::make($this->name)->schema($this->formSchema($dependencies));
    }

    public function infolist(): Tab
    {
        return Tab::make($this->name)->schema($this->infolistSchema());
    }

    /**
     * @param  list<string>  $dependencies
     */
    public function formSchema(array $dependencies): array
    {
        return Arr::map(
            $this->fields['content'],
            fn ($content) => DynamicBrick::resolve(
                $content['attrs']['identifier'],
                $content['attrs']['values'],
                $dependencies,
            )->form(),
        );
    }

    public function infolistSchema(): array
    {
        return Arr::map(
            $this->fields['content'],
            fn ($content) => DynamicBrick::resolve(
                $content['attrs']['identifier'],
                $content['attrs']['values'],
            )->infolist(),
        );
    }

    public function fieldsDependedOn(): array
    {
        return Arr::map(
            $this->fields['content'],
            fn ($content) => Arr::pluck($content['attrs']['values']['visible_conditions'] ?? [], 'field'),
        );
    }
}
