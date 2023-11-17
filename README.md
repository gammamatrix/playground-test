# Playground

The Playground Test package.

This package utilizes PHPUnit and Laravel-based test cases.

## Installation

You can install the package via composer:

```bash
composer require --dev gammamatrix/playground-test
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="GammaMatrix\Playground\Test\ServiceProvider" --tag="playground-config"
```

See the contents of the published config file: [config/playground-test.php](config/playground-test.php)


## Configuration

All options are disabled by default.

Enable all options:

```
#
# @var bool Allow publishing of the configuration.
#
PLAYGROUND_TEST_PUBLISH_CONFIG=true
#
# @var string A password for testing the application.
#
PLAYGROUND_TEST_PASSWORD=some-password
#
# @var bool The password in PLAYGROUND_TEST_PASSWORD is already encrypted
#
PLAYGROUND_TEST_PASSWORD_ENCRYPTED=false
```

## Testing

The components of this package are meant to assist in testing playground packages under a Laravel installation with PHPUnit.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jeremy Postlethwaite](https://github.com/gammamatrix/playground-test)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
