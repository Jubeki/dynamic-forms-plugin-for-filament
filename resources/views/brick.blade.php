@use(Jubeki\Filament\DynamicForms\Bricks\DynamicBrick)
@use(Jubeki\Filament\DynamicForms\Livewire\BrickFormDummy)
@use(Filament\Forms\ComponentContainer)

@php
    $components = Arr::wrap(DynamicBrick::resolve($type, get_defined_vars(), [])->form());

    foreach($components as $component) {
    
        $component->container(new ComponentContainer(new BrickFormDummy))
            ->visible(true);

        if(method_exists($component, 'isRequired') && $component->isRequired()) {
            $component->required(false)->markAsRequired();
        }
    }

    $visible ??= 'always';
@endphp
<div>
    @foreach($components as $component)
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
    @endforeach
</div>