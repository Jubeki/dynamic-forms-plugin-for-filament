<?php

namespace Jubeki\Filament\DynamicForms\Bricks;

use Awcodes\Mason\Brick;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;

class FileUploadBrick extends DynamicBrick
{
    public static string $identifier = 'file_upload_brick';
    
    public static function make(): Brick
    {
        return static::brick()->label('File Upload');
    }

    public function form(): FileUpload
    {
        $component = $this->configureForForm(FileUpload::class);

        return $component
            ->name($component->getName().'.file')
            ->storeFileNamesIn($component->getName().'.file_name')
            ->maxFiles(1)
            ->maxSize(1024)
            ->rule('extensions:pdf,jpg,jpeg,png')
            ->acceptedFileTypes(['application/pdf', 'image/jpg', 'image/jpeg', 'image/png']);
    }

    public function infolist(): TextEntry
    {
        return $this->configureForInfolist(TextEntry::class);
    }
}
