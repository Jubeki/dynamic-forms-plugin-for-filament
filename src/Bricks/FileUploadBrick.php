<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\Auth;

class FileUploadBrick extends DynamicBrick
{
    public static string $identifier = 'file_upload_brick';

    protected static ?Closure $resolveDirectory = null;

    public static function make(): Brick
    {
        return static::brick()->label('File Upload');
    }

    public function form(): FileUpload
    {
        $component = $this->configureForForm(FileUpload::class);
        $name = $component->getName();

        return $component
            ->name("{$name}.0.file")
            ->statePath("{$name}.0.file")
            ->storeFileNamesIn("{$name}.0.file_name")
            ->maxFiles(1)
            ->maxSize(1024)
            ->disk('local')
            ->directory(static::resolveDirectory())
            ->rule('extensions:pdf,jpg,jpeg,png')
            ->acceptedFileTypes(['application/pdf', 'image/jpg', 'image/jpeg', 'image/png']);
    }

    public function infolist(): RepeatableEntry
    {
        return $this->configureForInfolist(RepeatableEntry::class)
            ->schema([
                TextEntry::make('file_name')->label('Datei')->default('---'),
            ])
            ->url(fn ($state) => $state[0]['file'] === null ? null : ('/'.$state[0]['file']), shouldOpenInNewTab: true);
    }

    public static function resolveDirectoryUsing(Closure $callback): void
    {
        static::$resolveDirectory = $callback;
    }

    public static function resolveDirectory(): string
    {
        if(static::$resolveDirectory) {
            return call_user_func(static::$resolveDirectory);
        }

        return 'documents/'.Auth::id();
    }
}
