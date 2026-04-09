# valkyrja/frankenphp

FrankenPHP persistent worker entry point for the [Valkyrja Framework](https://www.valkyrja.io).

## Installation

```bash
composer require valkyrja/frankenphp
```

Requires [FrankenPHP](https://frankenphp.dev) running in worker mode.

## Usage

```php
// app/public/index.php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\FrankenPhp\FrankenPhpHttp;

FrankenPhpHttp::run(new HttpConfig(
    dir: __DIR__ . '/..',
));
```

`run()` bootstraps the application once when the worker process starts, then
enters the FrankenPHP request loop. Each request is handled in an isolated
child container so state never bleeds between requests.

## Customising Bootstrap

Override `bootstrapParentServices()` to force-resolve services that are
expensive to create and safe to share across requests:

```php
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;

class MyFrankenPhpHttp extends FrankenPhpHttp
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

See the [Valkyrja Application README](https://github.com/valkyrja/valkyrja) for
a full explanation of the persistent worker lifecycle, the child container
isolation model, and configuration options.

## License

MIT