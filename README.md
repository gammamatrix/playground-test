# Playground

[![Playground CI Workflow](https://github.com/gammamatrix/playground-test/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-test/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-test/testing/develop/coverage.svg)](tests)
[![PHPStan Level 10 src and tests](https://img.shields.io/badge/PHPStan-level%2010-brightgreen)](.github/workflows/ci.yml#L120)

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

## Cloc

```sh
composer cloc
```

```
➜  playground-test git:(develop) ✗ composer cloc
     102 text files.
      96 unique files.                              
       7 files ignored.

github.com/AlDanial/cloc v 2.08  T=0.03 s (2777.4 files/s, 289426.0 lines/s)
-------------------------------------------------------------------------------
Language                     files          blank        comment           code
-------------------------------------------------------------------------------
PHP                             87           1914           2114           5352
XML                              3              0              7            218
YAML                             1              4              0            188
Markdown                         3             35              0             88
JSON                             2              0              0             84
-------------------------------------------------------------------------------
SUM:                            96           1953           2121           5930
-------------------------------------------------------------------------------
```

## PHPStan

Tests at level 10 on:
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
