<?php

namespace Jubeki\Filament\DynamicForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Jubeki\Filament\DynamicForms\Bricks\DynamicBrick;
use Spatie\Translatable\HasTranslations;

class CustomFieldSet extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'dynamic_custom_field_sets';

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

    /**
     * @param  list<string>  $dependencies
     */
    public function form(?array $dependencies = null, bool $disableRequiredCheck = false, string $prefix = ''): array
    {
        $dependencies ??= $this->fieldsDependedOn();

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

    public function infolist(string $prefix = ''): array
    {
        return collect($this->fields['content'])->map(
            fn ($content) => DynamicBrick::resolve(
                $content['attrs']['identifier'],
                $content['attrs']['values'],
                prefix: $prefix,
            )->infolist(),
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
