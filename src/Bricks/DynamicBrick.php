<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Closure;
use Filament\Forms\Components\Component as FormComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\Component as InfolistComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Livewire\Component as Livewire;
use LogicException;

abstract class DynamicBrick
{
    public static array $bricks = [
        TextInputBrick::class,
        RadioBrick::class,
        ToggleButtonsBrick::class,
        SelectBrick::class,
        CheckboxBrick::class,
        ToggleBrick::class,
        ContentBrick::class,
        FileUploadBrick::class,
    ];

    public static string $identifier;

    public static function brick(): Brick
    {
        return Brick::make(static::$identifier)
            ->slideOver()
            ->form(method_exists(static::class, 'schema') ? static::schema() : static::defaultSchema())
            ->fillForm(fn (array $arguments): array => $arguments)
            ->action(function (array $arguments, array $data, Mason $component) {

                $data['type'] = static::$identifier;

                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => static::$identifier,
                                'values' => $data,
                                'path' => 'dynamic-forms::brick',
                                'view' => view('dynamic-forms::brick', $data)->render(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }

    public static function resolve(string $type, array $data, ?array $dependencies = null, bool $disableRequiredCheck = false, string $prefix = ''): static
    {
        foreach (static::$bricks as $brick) {
            if ($brick::$identifier === $type) {
                return $brick::build($data, $dependencies, $disableRequiredCheck, $prefix);
            }
        }

        throw new LogicException('Unknown dynamic brick type.');
    }

    /**
     * @param  list<string>  $dependencies
     */
    public static function build(array $data, ?array $dependencies = null, bool $disableRequiredCheck = false, string $prefix = ''): static
    {
        return new static($data, $dependencies ?? [], $disableRequiredCheck, $prefix);
    }

    public static function defaultSchema(array $schema = []): array
    {
        return [

            Tabs::make()->contained(false)->schema([

                Tab::make('General')->schema([

                    TextInput::make('handle')
                        ->label('Handle')
                        ->required(),

                    Group::make([

                        TextInput::make('label.de')
                            ->label('Label (DE)')
                            ->required(),

                        TextInput::make('label.en')
                            ->label('Label (EN)')
                            ->required(),

                    ])->columns(2),

                    Group::make([

                        TextInput::make('helperText.de')
                            ->label('Helper Text (DE)'),

                        TextInput::make('helperText.en')
                            ->label('Helper Text (EN)'),

                    ])->columns(2),

                    Group::make([

                        TextInput::make('hint.de')
                            ->label('Hint (DE)'),

                        TextInput::make('hint.en')
                            ->label('Hint (EN)'),

                    ])->columns(2),

                    ...$schema,
                ]),

                Tab::make('Validation')->schema([

                    Toggle::make('required')
                        ->label('Required'),

                    Repeater::make('rules')
                        ->simple(
                            TextInput::make('rule')
                                ->label('Rule')
                                ->required()
                        ),
                ]),

                Tab::make('Visibility')->schema([

                    Select::make('visible')
                        ->label('Visible')
                        ->options([
                            'always' => 'Always',
                            'if_all' => 'If all conditions are true',
                            'if_any' => 'If any condition is true',
                            'never' => 'Never',
                        ])
                        ->default('always')
                        ->live(),

                    TableRepeater::make('visible_conditions')
                        ->visible(fn (Get $get) => in_array($get('visible'), ['if_all', 'if_any']))
                        ->headers([
                            Header::make('Field'),
                            Header::make('Operator'),
                            Header::make('Value'),
                        ])
                        ->schema([
                            TextInput::make('field')
                                ->label('Field')
                                ->required(),

                            Select::make('operator')
                                ->label('Operator')
                                ->options([
                                    '==' => '==',
                                    '!=' => '!=',
                                    '>' => '>',
                                    '<' => '<',
                                    '>=' => '>=',
                                    '<=' => '<=',
                                    'contains' => 'contains',
                                    'starts_with' => 'starts with',
                                    'ends_with' => 'ends with',
                                    'in' => 'in',
                                    'not_in' => 'not in',
                                ])
                                ->required(),

                            TextInput::make('value')
                                ->label('Value')
                                ->required(),
                        ])->defaultItems(1),

                ]),
            ]),

        ];
    }

    protected function __construct(
        protected array $data,
        protected array $dependencies = [],
        protected bool $disableRequiredCheck = false,
        protected string $prefix = '',
    ) {}

    abstract public function form(): FormComponent|array;

    abstract public function infolist(): InfolistComponent|array;

    /**
     * @template T of \Filament\Forms\Components\Component
     *
     * @param  class-string<T>  $class
     * @return T
     */
    protected function configureForForm(string $class): FormComponent
    {
        return $class::make($this->prefix.$this->data['handle'])
            ->label($this->localized('label'))
            ->helperText($this->localized('helperText'))
            ->hint($this->localized('hint'))
            ->required(fn (Livewire $livewire) => ! ($livewire->disableRequiredCheck ?? false) && $this->bool('required'))
            ->visible($this->visibleClosureForm())
            ->live(condition: in_array($this->data['handle'], $this->dependencies));
    }

    /**
     * @template T of \Filament\Infolists\Components\Component
     *
     * @param  class-string<T>  $class
     * @return T
     */
    protected function configureForInfolist(string $class): InfolistComponent
    {
        return $class::make($this->prefix.$this->data['handle'])
            ->label($this->localized('label'))
            ->visible($this->visibleClosureInfolist())
            ->default('---');
    }

    protected function visibleClosureForm(): Closure|bool
    {
        if ($this->data['visible'] === 'always') {
            return true;
        }

        if ($this->data['visible'] === 'never') {
            return false;
        }

        if ($this->data['visible'] === 'if_all') {
            return function (Get $get) {
                foreach ($this->data['visible_conditions'] as $condition) {
                    if ($this->evaluateConditionForm($condition, $get) === false) {
                        return false;
                    }
                }

                return true;

            };
        }

        if ($this->data['visible'] === 'if_any') {
            return function (Get $get) {

                foreach ($this->data['visible_conditions'] as $condition) {
                    if ($this->evaluateConditionForm($condition, $get) === true) {
                        return true;
                    }
                }

                return false;
            };
        }

        return true;
    }

    protected function evaluateConditionForm(array $condition, Get $get): bool
    {
        $field = $this->prefix.$condition['field'];
        $operator = $condition['operator'];
        $value = $condition['value'];

        $fieldValue = $get($field);

        return match ($operator) {
            '==' => $fieldValue == $value,
            '!=' => $fieldValue != $value,
            '>' => $fieldValue > $value,
            '<' => $fieldValue < $value,
            '>=' => $fieldValue >= $value,
            '<=' => $fieldValue <= $value,
            'contains' => str_contains($fieldValue, $value),
            'starts_with' => str_starts_with($fieldValue, $value),
            'ends_with' => str_ends_with($fieldValue, $value),
            'in' => in_array($fieldValue, explode(',', $value)),
            'not_in' => ! in_array($fieldValue, explode(',', $value)),
            default => false,
        };
    }

    protected function visibleClosureInfolist(): Closure|bool
    {
        if ($this->data['visible'] === 'always') {
            return true;
        }

        if ($this->data['visible'] === 'never') {
            return false;
        }

        if ($this->data['visible'] === 'if_all') {
            return function (Model $record) {
                foreach ($this->data['visible_conditions'] as $condition) {
                    if ($this->evaluateConditionInfolist($condition, $record) === false) {
                        return false;
                    }
                }

                return true;

            };
        }

        if ($this->data['visible'] === 'if_any') {
            return function (Model $record) {

                foreach ($this->data['visible_conditions'] as $condition) {
                    if ($this->evaluateConditionInfolist($condition, $record) === true) {
                        return true;
                    }
                }

                return false;
            };
        }

        return true;
    }

    protected function evaluateConditionInfolist(array $condition, Model $record): bool
    {
        $field = $this->prefix.$condition['field'];
        $operator = $condition['operator'];
        $value = $condition['value'];

        $fieldValue = Arr::get($record, $field);

        return match ($operator) {
            '==' => $fieldValue == $value,
            '!=' => $fieldValue != $value,
            '>' => $fieldValue > $value,
            '<' => $fieldValue < $value,
            '>=' => $fieldValue >= $value,
            '<=' => $fieldValue <= $value,
            'contains' => str_contains($fieldValue, $value),
            'starts_with' => str_starts_with($fieldValue, $value),
            'ends_with' => str_ends_with($fieldValue, $value),
            'in' => in_array($fieldValue, explode(',', $value)),
            'not_in' => ! in_array($fieldValue, explode(',', $value)),
            default => false,
        };
    }

    protected function localized(string $key): ?string
    {
        return Arr::get($this->data, $key.'.'.App::getLocale());
    }

    protected function string(string $key): ?string
    {
        return Arr::get($this->data, $key);
    }

    protected function localizedArray(string $key): ?array
    {
        $array = Arr::get($this->data, $key);

        if ($array === null) {
            return null;
        }

        return Arr::mapWithKeys($array, fn ($value) => [
            $value['value'] => $value['label'][App::getLocale()],
        ]);
    }

    protected function array(string $key): ?array
    {
        return Arr::get($this->data, $key);
    }

    protected function bool(string $key): ?bool
    {
        return Arr::get($this->data, $key);
    }
}
