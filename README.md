# 🚀 Laravel Super Migrate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bobinrinder/laravel-super-migrate.svg?style=flat-square)](https://packagist.org/packages/bobinrinder/laravel-super-migrate)
[![GitHub Tests Action Status](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/run-tests.yml/badge.svg)](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/bobinrinder/laravel-super-migrate/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/bobinrinder/laravel-super-migrate.svg?style=flat-square)](https://packagist.org/packages/bobinrinder/laravel-super-migrate)

This package extends Laravel's default migration functionality.

It hooks seamlessly into the existing migration process, but tracks more details than the built-in process.

| Feature                           | Super Migrate | Native Laravel |
| :-------------------------------- | :-----------: | :------------: |
| Logs migrations                   |      ✅       |       ✅       |
| Logs start, end and failure times |      ✅       |       ❌       |
| Logs rollbacks                    |      ✅       |       ❌       |
| Logs exceptions with stack trace  |      ✅       |       ❌       |
| CLI to see history and failures   |      ✅       |       ❌       |
| Optional parallel run prevention  |      ✅       |       ❌       |

## Installation

You can install the package via composer:

```bash
composer require bobinrinder/laravel-super-migrate
```

And then run the migration with:

```bash
php artisan migrate
```

## Publish

Optionally you can publish the [config file](https://github.com/bobinrinder/laravel-super-migrate/blob/main/config/super-migrate.php) with and/or the migration with:

```bash
php artisan vendor:publish --tag="super-migrate-config"
php artisan vendor:publish --tag="super-migrate-migrations"
```

<!-- Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="super-migrate-views"
``` -->

## Usage

Use Laravel's migration system like you usually do.

```bash
php artisan migrate
```

To show a history of migrations run:

```bash
php artisan super-migrate:list
```

To show details of the last failure:

```bash
php artisan super-migrate:failure
```

To show details of a specific failure add the ID seen in the `list` command:

```bash
php artisan super-migrate:failure 5
```

## Testing

```bash
composer test
```

## Roadmap

-   [ ] Log events and errors to Slack etc via Monolog
-   [ ] Web/Nova UI to look at history

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
