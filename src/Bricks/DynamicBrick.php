<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Closure;
use Filament\Forms\Components\Component as FormComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Component as InfolistComponent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use LogicException;

abstract class DynamicBrick
{
    public static array $bricks = [
        TextInputBrick::class,
        RadioBrick::class,
    ];

    public static string $identifier;

    public static function brick(): Brick
    {
        return Brick::make(static::$identifier)
            ->slideOver()
            ->form(method_exists(static::class, 'schema') ? static::schema() : static::defaultSchema())
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

    public static function resolve(string $type, array $data): static
    {
        foreach(static::$bricks as $brick) {
            if($brick::$identifier === $type) {
                return $brick::build($data);
            }
        }
        
        throw new LogicException('Unknown dynamic brick type.');
    }

    public static function build(array $data): static
    {
        return new static($data);
    }

    public static function defaultSchema(array $schema = []): array
    {
        return [
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

            Toggle::make('required')
                ->label('Required'),
            

            ...$schema,
        ];
    }

    protected function __construct(protected array $data) {}

    public abstract function form(): FormComponent;
    public abstract function infolist(): InfolistComponent;

    /**
     * @template T of \Filament\Forms\Components\Component
     * 
     * @param  class-string<T>  $class 
     * @return T
     */
    protected function configureForForm(string $class): FormComponent
    {
        return $class::make($this->data['handle'])
            ->label($this->localized('label'))
            ->helperText($this->localized('helperText'))
            ->hint($this->localized('hint'))
            ->required($this->bool('required'))
            ->visible($this->visibleClosure());
    }

    /**
     * @template T of \Filament\Infolists\Components\Component
     * 
     * @param  class-string<T>  $class 
     * @return T
     */
    protected function configureForInfolist(string $class): InfolistComponent
    {
        return $class::make($this->data['handle'])
            ->label($this->localized('label'))
            ->visible($this->visibleClosure());
    }

    protected function visibleClosure(): Closure
    {
        return function() {
            return true;
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

        if($array === null) {
            return null;
        }

        return Arr::map($array, fn($value) => $value[App::getLocale()]);
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