<h1 align="center">
    Dynamic Forms Plugin for Filament
</h1>

<p align="center">
    Create custom forms using the Components from Filament Form Builder.
</p>

<p align="center">
    <a href="https://github.com/Jubeki/dynamic-forms-plugin-for-filament/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/Jubeki/dynamic-forms-plugin-for-filament/tests.yml?branch=2.x&label=Tests&logo=github&style=for-the-badge"></a>
    <a href="https://packagist.org/packages/jubeki/dynamic-forms-plugin-for-filament/stats"><img alt="Downloads Total" src="https://img.shields.io/packagist/dt/Jubeki/dynamic-forms-plugin-for-filament?style=for-the-badge"></a>
    <a href="LICENSE.md"><img alt="License Type" src="https://img.shields.io/github/license/Jubeki/dynamic-forms-plugin-for-filament?style=for-the-badge"></a>
    <a href="https://github.com/Jubeki/dynamic-forms-plugin-for-filament/releases/latest"><img alt="Latest released version" src="https://img.shields.io/github/v/release/Jubeki/dynamic-forms-plugin-for-filament?sort=semver&style=for-the-badge"></a>
</p>

## Introduction

This package is a dynamic form plugin for Filament Forms. It allows you to create forms with dynamic fields.

## Installation

THIS IS WORK IN PROGRESS AND NOT PRODUCTION READY!
THERE ARE CURRENTLY NO TESTS AND NO SECURITY MEASURES IMPLEMENTED!

For now you need the following repositories at your `composer.json` (this will not be needed when the package is released.)

```json
{
    "repositories": {
        "jubeki/dynamic-forms-plugin-for-filament": {
            "type": "vcs",
            "url": "https://github.com/Jubeki/dynamic-forms-plugin-for-filament"
        },
        "awcodes/mason": {
            "type": "vcs",
            "url": "https://github.com/awcodes/mason"
        }
    }
}
```

```shell
composer require jubeki/dynamic-forms-plugin-for-filament
```

## Basic Usage

### Registering the Plugin for a Filament Panel

To create and update forms, you need to register the plugin for a Filament Panel.

```php
<?php

namespace App\Providers\Filament;

// ...
use Jubeki\Filament\DynamicForms\DynamicFormsPlugin;
// ...

class AdminPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...   
            ->plugins([
                DynamicFormsPlugin::make(),
            ]);
    }
}
```

TODO: How to embedded the form and how to handle submissions.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```shell
composer test
```

## Contributing

Please see [CONTRIBUTING](./.github/CONTRIBUTING.md) for details.

## Credits

- [Julius Kiekbusch (@Jubeki)](https://github.com/Jubeki)
- [Adam Weston (@awcodes)](https://github.com/awcodes) (Creator of the Mason Plugin for Filament)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.