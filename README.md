# Playground

[![Playground CI Workflow](https://github.com/gammamatrix/playground-test/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-test/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-test/testing/develop/coverage.svg)](tests)
[![PHPStan Level 9 src and tests](https://img.shields.io/badge/PHPStan-level%209-brightgreen)](.github/workflows/ci.yml#L120)

The Playground Test package.

This package utilizes PHPUnit and Laravel-based test cases.

## Installation

You can install the package via composer:

```bash
composer require --dev gammamatrix/playground-test
```

## Configuration

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Playground\Test\ServiceProvider" --tag="playground-config"
```

See the contents of the published config file: [config/playground-test.php](config/playground-test.php)

### Environment Variables

Information on [environment variables is available on the wiki for this package](https://github.com/gammamatrix/playground-test/wiki/Environment-Variables)

## PHPStan

Tests at level 9 on:
- `config/`
- `database/`
- `resources/`
- `src/`
- `tests/Feature/`
- `tests/Unit/`

```sh
composer analyse
```

## Coding Standards

```sh
composer format
```

## Tests

```sh
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jeremy Postlethwaite](https://github.com/gammamatrix/playground-test)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
