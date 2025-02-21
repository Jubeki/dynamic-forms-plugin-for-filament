@use(Jubeki\Filament\DynamicForms\Bricks\DynamicBrick)
@use(Jubeki\Filament\DynamicForms\Livewire\BrickFormDummy)
@use(Filament\Forms\ComponentContainer)

@php
    $component = DynamicBrick::resolve($type, get_defined_vars())
        ->form()
        ->container(new ComponentContainer(new BrickFormDummy));

    if($component->isRequired()) {
        $component->required(false)->markAsRequired();
    }
@endphp

<div class="px-3 py-3">
    {{ $component->render() }}
</div>