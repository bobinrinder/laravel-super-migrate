# 🚀 Laravel Super Migrate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bobinrinder/laravel-super-migrate.svg?style=flat-square)](https://packagist.org/packages/bobinrinder/laravel-super-migrate)
[![GitHub Tests Action Status](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/run-tests.yml/badge.svg)](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/bobinrinder/laravel-super-migrate.svg?style=flat-square)](https://packagist.org/packages/bobinrinder/laravel-super-migrate)

This package intends to extend Laravel's default migration functionality.

It provides:

-   Extensive logs for every migration run and rollback
-   Provides timestamps for start, finish and failure
-   Logging of exceptions and stack traces happening in migrations
-   Optional prevention of parallel migration runs
-   CLI to look at history
-   [ ] (TODO) Log events and errors to Slack etc via Monolog
-   [ ] (TODO) UI to look at history

## Installation

You can install the package via composer:

```bash
composer require bobinrinder/laravel-super-migrate
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="super-migrate-migrations"
php artisan migrate
```

You can publish the [config file](https://github.com/bobinrinder/laravel-super-migrate/blob/main/config/super-migrate.php) with:

```bash
php artisan vendor:publish --tag="super-migrate-config"
```

<!-- Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="super-migrate-views"
``` -->

## Usage

Use Laravel's migration system like you usually do.

Laravel Super Migrate will work seamlessly in the background.

```bash
php artisan migrate
```

To show a history of migrations run:

```bash
php artisan super-migrate
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
