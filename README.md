<p align="center"><a href="https://valkyrja.io" target="_blank">
    <img src="https://raw.githubusercontent.com/valkyrjaio/art/refs/heads/master/long-banner/orange/php.png" width="100%">
</a></p>

# Valkyrja FrankenPHP

FrankenPHP persistent worker entry point for the [Valkyrja Framework](https://www.valkyrja.io).

About
-----

> This repository provides the FrankenPHP persistent worker entry point for the Valkyrja Framework.

Bootstraps the application once at startup, then dispatches every incoming request to an
isolated child container — so request state never bleeds between requests.

<p>
    <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/require/php" alt="PHP Version Require"></a>
    <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/license" alt="License"></a>
    <!-- <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/downloads" alt="Total Downloads"></a>-->
    <a href="https://scrutinizer-ci.com/g/valkyrjaio/frankenphp/?branch=26.x"><img src="https://scrutinizer-ci.com/g/valkyrjaio/frankenphp/badges/quality-score.png?b=26.x" alt="Scrutinizer"></a>
    <a href="https://coveralls.io/github/valkyrjaio/frankenphp?branch=26.x"><img src="https://coveralls.io/repos/github/valkyrjaio/frankenphp/badge.svg?branch=26.x" alt="Coverage Status" /></a>
    <a href="https://shepherd.dev/github/valkyrjaio/frankenphp"><img src="https://shepherd.dev/github/valkyrjaio/frankenphp/coverage.svg" alt="Psalm Shepherd" /></a>
    <a href="https://sonarcloud.io/summary/new_code?id=valkyrjaio_frankenphp"><img src="https://sonarcloud.io/api/project_badges/measure?project=valkyrjaio_frankenphp&metric=sqale_rating" alt="Maintainability Rating" /></a>
</p>

Build Status
------------

<table>
    <tbody>
        <tr>
            <td>Linting</td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpcodesniffer.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpcodesniffer.yml/badge.svg?branch=26.x" alt="PHP Code Sniffer Build Status"></a>
            </td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpcsfixer.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpcsfixer.yml/badge.svg?branch=26.x" alt="PHP CS Fixer Build Status"></a>
            </td>
        </tr>
        <tr>
            <td>Coding Rules</td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/phparkitect.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/phparkitect.yml/badge.svg?branch=26.x" alt="PHPArkitect Build Status"></a>
            </td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/rector.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/rector.yml/badge.svg?branch=26.x" alt="Rector Build Status"></a>
            </td>
        </tr>
        <tr>
            <td>Static Analysis</td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpstan.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpstan.yml/badge.svg?branch=26.x" alt="PHPStan Build Status"></a>
            </td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/psalm.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/psalm.yml/badge.svg?branch=26.x" alt="Psalm Build Status"></a>
            </td>
        </tr>
        <tr>
            <td>Testing</td>
            <td>
                <a href="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpunit.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/frankenphp/actions/workflows/phpunit.yml/badge.svg?branch=26.x" alt="PHPUnit Build Status"></a>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
## Installation

```bash
composer require valkyrja/frankenphp
```

Requires [FrankenPHP](https://frankenphp.dev/docs/worker/) running in worker mode.

## Usage

```php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\FrankenPhp\FrankenPhpHttp;

FrankenPhpHttp::run(new HttpConfig(
    dir: __DIR__ . '/..',
));
```

`run()` bootstraps the application once when the worker process starts, then
enters the FrankenPHP request loop. Each request is handled in an isolated child
container so state never bleeds between requests.

## Customising Bootstrap

Override `bootstrapParentServices()` to force-resolve services that are
expensive to create and safe to share across requests:

```php
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;

class App extends FrankenPhpHttp
{
    protected static function bootstrapParentServices(ApplicationContract $app): void
    {
        $container = $app->getContainer();
        $container->getSingleton(CollectionContract::class);
        $container->getSingleton(MyExpensiveSharedService::class);
    }
}
```

## Worker Lifecycle

See the [Valkyrja Framework README](https://github.com/valkyrjaio/valkyrja) for
a full explanation of the persistent worker lifecycle, the child container
isolation model, and configuration options.

## License

MIT
