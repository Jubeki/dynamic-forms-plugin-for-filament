<?php

namespace Jubeki\Filament\DynamicForms\Events;

use Jubeki\Filament\DynamicForms\Models\FormBlueprint;

readonly class DuplicatedBlueprint
{
    public function __construct(
        public FormBlueprint $record,
        public FormBlueprint $replica,
    ) {}
}