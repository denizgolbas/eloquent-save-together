# Eloquent SaveTogether

[![Latest Version on Packagist](https://img.shields.io/packagist/v/denizgolbas/eloquent-save-together.svg?style=flat-square)](https://packagist.org/packages/denizgolbas/eloquent-save-together)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/denizgolbas/eloquent-save-together/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/denizgolbas/eloquent-save-together/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/denizgolbas/eloquent-save-together/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/denizgolbas/eloquent-save-together/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/denizgolbas/eloquent-save-together.svg?style=flat-square)](https://packagist.org/packages/denizgolbas/eloquent-save-together)

For Laravel Eloquent that makes it easy to save related models together.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/eloquent-save-together.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/eloquent-save-together)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require denizgolbas/eloquent-save-together
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="eloquent-save-together-config"
```

This is the contents of the published config file:

```php
return [
];
```


## Usage

```php
$eloquentSaveTogether = new Denizgolbas\EloquentSaveTogether();
echo $eloquentSaveTogether->echoPhrase('Hello, Denizgolbas!');
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

- [denizgolbas](https://github.com/denizgolbas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
