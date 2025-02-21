<?php

namespace Jubeki\Filament\DynamicForms\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Forms\Form;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BrickFormDummy extends Component implements HasForms
{
    use InteractsWithForms;
    
    public function form(Form $form): Form
    {
        return $form;
    }
}