@use(Jubeki\Filament\DynamicForms\Bricks\DynamicBrick)
@use(Jubeki\Filament\DynamicForms\Livewire\BrickFormDummy)
@use(Filament\Forms\ComponentContainer)

@php
    $component = DynamicBrick::resolve($type, get_defined_vars(), [])
        ->form()
        ->container(new ComponentContainer(new BrickFormDummy))
        ->visible(true);

    if($component->isRequired()) {
        $component->required(false)->markAsRequired();
    }
@endphp

<div class="px-3 py-3">
    {{ $component->render() }}

    @if (($visible === 'if_all' || $visible === 'if_any') && count($visible_conditions ?? []) > 0)
        <div class="mt-2 text-sm text-gray-600">
            @if ($visible === 'if_all')
                <p>This field is only visible if all conditions are true:</p>
            @else
                <p>This field is only visible if any of the conditions are true:</p>
            @endif

            <ul>
                @foreach ($visible_conditions as $condition)
                    <li>
                        {{ $condition['field'] }}
                        {{ $condition['operator'] }}
                        &quot;{{ $condition['value'] }}&quot;
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>