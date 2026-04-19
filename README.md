<p align="center"><a href="https://valkyrja.io" target="_blank">
    <img src="https://raw.githubusercontent.com/valkyrjaio/art/refs/heads/master/long-banner/orange/php.png" width="100%">
</a></p>

# Valkyrja FrankenPHP

FrankenPHP persistent worker entry point for the [Valkyrja][Valkyrja url]
PHP framework.

This integration bootstraps the Valkyrja application once at worker startup,
then dispatches every incoming request to an isolated child container so
request state never bleeds between requests. The result is the performance
benefit of a persistent process without the state-contamination risks of
naive long-running PHP.

<p>
    <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/require/php" alt="PHP Version Require"></a>
    <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/valkyrja/frankenphp"><img src="https://poser.pugx.org/valkyrja/frankenphp/license" alt="License"></a>
    <a href="https://github.com/valkyrjaio/valkyrja-frankenphp-php/actions/workflows/ci.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/valkyrja-frankenphp-php/actions/workflows/ci.yml/badge.svg?branch=26.x" alt="CI Status"></a>
    <a href="https://scrutinizer-ci.com/g/valkyrjaio/frankenphp/?branch=26.x"><img src="https://scrutinizer-ci.com/g/valkyrjaio/frankenphp/badges/quality-score.png?b=26.x" alt="Scrutinizer"></a>
    <a href="https://coveralls.io/github/valkyrjaio/frankenphp?branch=26.x"><img src="https://coveralls.io/repos/github/valkyrjaio/frankenphp/badge.svg?branch=26.x" alt="Coverage Status" /></a>
    <a href="https://shepherd.dev/github/valkyrjaio/frankenphp"><img src="https://shepherd.dev/github/valkyrjaio/frankenphp/coverage.svg" alt="Psalm Shepherd" /></a>
    <a href="https://sonarcloud.io/summary/new_code?id=valkyrjaio_frankenphp"><img src="https://sonarcloud.io/api/project_badges/measure?project=valkyrjaio_frankenphp&metric=sqale_rating" alt="Maintainability Rating" /></a>
</p>

Requirements
------------

- PHP 8.4+
- [FrankenPHP][frankenphp docs url] running in worker mode
- An existing [Valkyrja][framework url] application

Installation
------------

```
composer require valkyrja/frankenphp
```

Usage
-----

Wire the FrankenPHP entry point into your application's front controller:

```
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\FrankenPhp\FrankenPhpHttp;

FrankenPhpHttp::run(new HttpConfig(
    dir: __DIR__ . '/..',
));
```

`run()` bootstraps the application once when the worker process starts, then
enters the FrankenPHP request loop. Each request is handled in an isolated
child container so state never bleeds between requests.

### Customizing Bootstrap

Override `bootstrapParentServices()` to force-resolve services that are
expensive to create and safe to share across requests:

```
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

Worker Lifecycle
----------------

See the [Valkyrja framework repository][framework url] for a full explanation
of the persistent worker lifecycle, the child container isolation model, and
configuration options.

Related Integrations
--------------------

Other persistent-worker runtime integrations for Valkyrja PHP:

- [**OpenSwoole**][openswoole url] — persistent worker via the OpenSwoole
  extension
- [**RoadRunner**][roadrunner url] — persistent worker via the Go-based
  RoadRunner manager

Contributing
------------

See [`CONTRIBUTING.md`][contributing url] for the submission process and
[`VOCABULARY.md`][vocabulary url] for the terminology used across Valkyrja.

Security Issues
---------------

If you discover a security vulnerability, please follow our
[disclosure procedure][security vulnerabilities url].

License
-------

Licensed under the [MIT license][MIT license url]. See
[`LICENSE.md`](./LICENSE.md).

[Valkyrja url]: https://valkyrja.io

[framework url]: https://github.com/valkyrjaio/valkyrja-php

[frankenphp docs url]: https://frankenphp.dev/docs/worker/

[openswoole url]: https://github.com/valkyrjaio/valkyrja-openswoole-php

[roadrunner url]: https://github.com/valkyrjaio/valkyrja-roadrunner-php

[contributing url]: https://github.com/valkyrjaio/.github/blob/master/CONTRIBUTING.md

[vocabulary url]: https://github.com/valkyrjaio/.github/blob/master/VOCABULARY.md

[security vulnerabilities url]: https://github.com/valkyrjaio/.github/blob/master/SECURITY.md

[MIT license url]: https://opensource.org/licenses/MIT
