<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja FrankenPHP package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\FrankenPhp;

use Throwable;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Abstract\WorkerHttp;
use Valkyrja\Application\Env\Env;

class FrankenPhpHttp extends WorkerHttp
{
    /**
     * Run the FrankenPHP app.
     *
     * @see https://frankenphp.dev/docs/worker/
     */
    public static function run(HttpConfig $config, Env $env = new Env()): void
    {
        $app = static::bootstrap(
            config: $config,
            env: $env,
        );

        $container = $app->getContainer();
        $data      = $container->getData();

        // Handler outside the loop for better performance (doing less work)
        $handler = static function () use ($app, $data): void {
            try {
                static::handle($app, $data, static::getRequest());
            } catch (Throwable) {
                // Currently not handled
            }
        };

        $maxRequests = (int) ($_SERVER['MAX_REQUESTS'] ?? 0);

        for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; $nbRequests++) {
            $keepRunning = frankenphp_handle_request($handler);

            // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
            gc_collect_cycles();

            if (!$keepRunning) {
                break;
            }
        }
    }
}
