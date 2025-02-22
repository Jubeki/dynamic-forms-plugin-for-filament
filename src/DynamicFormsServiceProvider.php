<?php

namespace Jubeki\Filament\DynamicForms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DynamicFormsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('dynamic-forms')
            // ->hasConfigFile()
            ->hasViews()
            // ->hasTranslations()
            ->discoversMigrations();
    }
}
