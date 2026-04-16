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