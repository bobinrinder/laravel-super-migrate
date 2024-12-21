# This is my package laravel-super-migrate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bobinrinder/laravel-super-migrate.svg?style=flat-square)](https://packagist.org/packages/bobinrinder/laravel-super-migrate)
[![GitHub Tests Action Status](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/run-tests.yml/badge.svg)](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/bobinrinder/laravel-super-migrate.svg?style=flat-square)](https://packagist.org/packages/bobinrinder/laravel-super-migrate)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require bobinrinder/laravel-super-migrate
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-super-migrate-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-super-migrate-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-super-migrate-views"
```

## Usage

```php
$laravelSuperMigrate = new Bobinrinder\LaravelSuperMigrate();
echo $laravelSuperMigrate->echoPhrase('Hello, Bobinrinder!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [bobinrinder](https://github.com/bobinrinder)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
