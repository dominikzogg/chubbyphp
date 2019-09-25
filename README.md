# chubbyphp-framework

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-framework.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-framework)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-framework/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-framework/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-framework/?branch=master)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-framework/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-framework)
[![Monthly Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-framework/d/monthly)](https://packagist.org/packages/chubbyphp/chubbyphp-framework)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-framework/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-framework)
[![Latest Unstable Version](https://poser.pugx.org/chubbyphp/chubbyphp-framework/v/unstable)](https://packagist.org/packages/chubbyphp/chubbyphp-framework)

## Description

A minimal middleware based micro framework using [PHP Framework Interop Group - PSR][1], with the goal is to achive
the best combination of flexibility and simplicity by using standards.

 * [Basic Coding Standard (1)][2]
 * [Coding Style Guide (2)][3]
 * [Logger Interface (3)][4]
 * [Autoloading Standard (4)][5]
 * [HTTP Message Interface (7)][6]
 * [Container Interface (11)][7]
 * [HTTP Handlers (15)][8]
 * [HTTP Factories (17)][9]

![Application workflow](doc/Resources/workflow.png?raw=true "Application workflow")

## Requirements

 * php: ^7.2
 * [psr/container][20]: ^1.0
 * [psr/http-factory][21]: ^1.0.1
 * [psr/http-message-implementation][22]: ^1.0
 * [psr/http-message][23]: ^1.0.1
 * [psr/http-server-handler][24]: ^1.0.1
 * [psr/http-server-middleware][25]: ^1.0.1
 * [psr/log][25]: ^1.1

## Suggest

### Router

Any Router which implements `Chubbyphp\Framework\Router\RouterInterface` can be used.

 * [aura/router][30]: ^3.1
 * [nikic/fast-route][31]: ^1.0|^0.6

### PSR 7 / PSR 17

 * [guzzlehttp/psr7][32]: ^1.4.2 (with [http-interop/http-factory-guzzle][33]: ^1.0)
 * [nyholm/psr7][34]: ^1.0
 * [slim/psr7][35]: ^0.5
 * [sunrise/http-message][36]: ^1.0 (with [sunrise/http-factory][37]: ^1.0)
 * [zendframework/zend-diactoros][38]: ^2.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-framework][40].

## Usage

### Aura.Router

```bash
composer require chubbyphp/chubbyphp-framework "^2.3" \
    aura/router "^3.1" zendframework/zend-diactoros "^2.0"
```

```php
<?php

declare(strict_types=1);

namespace App;

use Chubbyphp\Framework\Application;
use Chubbyphp\Framework\ErrorHandler;
use Chubbyphp\Framework\Middleware\ExceptionMiddleware;
use Chubbyphp\Framework\Middleware\RouterMiddleware;
use Chubbyphp\Framework\RequestHandler\CallbackRequestHandler;
use Chubbyphp\Framework\Router\AuraRouter;
use Chubbyphp\Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;

$loader = require __DIR__.'/vendor/autoload.php';

set_error_handler([ErrorHandler::class, 'handle']);

$responseFactory = new ResponseFactory();

$app = new Application([
    new ExceptionMiddleware($responseFactory, true),
    new RouterMiddleware(new AuraRouter([
        Route::get('/hello/{name}', 'hello', new CallbackRequestHandler(
            function (ServerRequestInterface $request) use ($responseFactory) {
                $name = $request->getAttribute('name');
                $response = $responseFactory->createResponse();
                $response->getBody()->write(sprintf('Hello, %s', $name));

                return $response;
            }
        ))->pathOptions(['tokens' => ['name' => '[a-z]+']])
    ]), $responseFactory),
]);

$app->send($app->handle(ServerRequestFactory::fromGlobals()));
```

### FastRoute

```bash
composer require chubbyphp/chubbyphp-framework "^2.3" \
    nikic/fast-route "^1.3" zendframework/zend-diactoros "^2.0"
```

```php
<?php

declare(strict_types=1);

namespace App;

use Chubbyphp\Framework\Application;
use Chubbyphp\Framework\ErrorHandler;
use Chubbyphp\Framework\Middleware\ExceptionMiddleware;
use Chubbyphp\Framework\Middleware\RouterMiddleware;
use Chubbyphp\Framework\RequestHandler\CallbackRequestHandler;
use Chubbyphp\Framework\Router\FastRouteRouter;
use Chubbyphp\Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;

$loader = require __DIR__.'/vendor/autoload.php';

set_error_handler([ErrorHandler::class, 'handle']);

$responseFactory = new ResponseFactory();

$app = new Application([
    new ExceptionMiddleware($responseFactory, true),
    new RouterMiddleware(new FastRouteRouter([
        Route::get('/hello/{name:[a-z]+}', 'hello', new CallbackRequestHandler(
            function (ServerRequestInterface $request) use ($responseFactory) {
                $name = $request->getAttribute('name');
                $response = $responseFactory->createResponse();
                $response->getBody()->write(sprintf('Hello, %s', $name));

                return $response;
            }
        ))
    ]), $responseFactory),
]);

$app->send($app->handle(ServerRequestFactory::fromGlobals()));
```

#### Advanved example with Middleware before and after routing

This is an example of middleware(s) before and after the routing was done.

If you need to be able to continue without finding a route, I recommend writing a RouterMiddleware that will pass either
the route or the RouteException and at the end another middleware that will convert the RouteException to a http response.

```php
<?php

declare(strict_types=1);

namespace App;

use Chubbyphp\Framework\Application;
use Chubbyphp\Framework\ErrorHandler;
use Chubbyphp\Framework\Middleware\CallbackMiddleware;
use Chubbyphp\Framework\Middleware\ExceptionMiddleware;
use Chubbyphp\Framework\Middleware\RouterMiddleware;
use Chubbyphp\Framework\RequestHandler\CallbackRequestHandler;
use Chubbyphp\Framework\Router\FastRouteRouter;
use Chubbyphp\Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;

$loader = require __DIR__.'/vendor/autoload.php';

set_error_handler([ErrorHandler::class, 'handle']);

$responseFactory = new ResponseFactory();

$app = new Application([
    new ExceptionMiddleware($responseFactory, true),
    new CallbackMiddleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        return $handler->handle($request);
    }),
    new RouterMiddleware(
        new FastRouteRouter([
            Route::get('/hello/{name:[a-z]+}', 'hello', new CallbackRequestHandler(
                function (ServerRequestInterface $request) use ($responseFactory) {
                    $name = $request->getAttribute('name');
                    $response = $responseFactory->createResponse();
                    $response->getBody()->write(sprintf('Hello, %s', $name));

                    return $response;
                }
            ))
        ]),
        $responseFactory
    ),
    new CallbackMiddleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        /** @var Route $route */
        $route = $request->getAttribute('route');

        if ('hello' === $route->getName()) {
            $request = $request->withAttribute('name', 'world');
        }

        return $handler->handle($request);
    }),
]);

$app->send($app->handle(ServerRequestFactory::fromGlobals()));
```

### Middleware

 * [CallbackMiddleware][70]
 * [ExceptionMiddleware][71]
 * [LazyMiddleware][72]
 * [MiddlewareDispatcher][73]
 * [NewRelicRouteMiddleware][74]
 * [RouterMiddleware][75]

### RequestHandler

 * [CallbackRequestHandler][80]
 * [LazyRequestHandler][81]

### Router

 * [AuraRouter][90]
 * [FastRouteRouter][91]
 * [Group][92]
 * [Route][93]

## Webserver

 * [Builtin (development only)][100]
 * [Nginx][101]

## Skeleton

 * [chubbyphp/chubbyphp-framework-skeleton][200]
 * [chubbyphp/petstore][201]

## ReactPHP

```php
<?php

declare(strict_types=1);

namespace App;

use Chubbyphp\Framework\Application;
use React\EventLoop\Factory;
use React\Http\Server;
use React\Socket\Server as Socket;

/** @var Application $app*/
$app = ...;

$loop = Factory::create();

$socket = new Socket(8080, $loop);

$server = new Server($app);
$server->listen($socket);

$loop->run();
```

## Swoole

 * [chubbyphp/chubbyphp-swoole-request-handler][210]

## Migration

Replace the code from the first block with the code of the second ones.

```php
use Chubbyphp\Framework\Application;
use Chubbyphp\Framework\ExceptionHandler;
use Chubbyphp\Framework\Middleware\MiddlewareDispatcher;
use Chubbyphp\Framework\Router\FastRouteRouter;

$app = new Application(
    new FastRouteRouter([$route]),
    new MiddlewareDispatcher(),
    new ExceptionHandler($responseFactory, true)
);
```

```php
use Chubbyphp\Framework\Application;
use Chubbyphp\Framework\Middleware\ExceptionMiddleware;
use Chubbyphp\Framework\Middleware\RouterMiddleware;
use Chubbyphp\Framework\Router\FastRouteRouter;

$app = new Application([
    new ExceptionMiddleware($responseFactory, true),
    new RouterMiddleware(new FastRouteRouter([$route]), $responseFactory),
]);
```

## Copyright

Dominik Zogg 2019

[1]: https://www.php-fig.org/psr/

[2]: https://www.php-fig.org/psr/psr-1
[3]: https://www.php-fig.org/psr/psr-2
[4]: https://www.php-fig.org/psr/psr-3
[5]: https://www.php-fig.org/psr/psr-4
[6]: https://www.php-fig.org/psr/psr-7
[7]: https://www.php-fig.org/psr/psr-11
[8]: https://www.php-fig.org/psr/psr-15
[9]: https://www.php-fig.org/psr/psr-17

[15]: https://travis-ci.org/chubbyphp/chubbyphp-framework

[20]: https://packagist.org/packages/psr/container
[21]: https://packagist.org/packages/psr/http-factory
[22]: https://packagist.org/packages/psr/http-message-implementation
[23]: https://packagist.org/packages/psr/http-message
[24]: https://packagist.org/packages/psr/http-server-handler
[25]: https://packagist.org/packages/psr/http-server-middleware
[26]: https://packagist.org/packages/psr/log

[30]: https://packagist.org/packages/aura/router
[31]: https://packagist.org/packages/nikic/fast-route

[32]: https://packagist.org/packages/guzzlehttp/psr7
[33]: https://packagist.org/packages/http-interop/http-factory-guzzle
[34]: https://packagist.org/packages/nyholm/psr7
[35]: https://packagist.org/packages/slim/psr7
[36]: https://packagist.org/packages/sunrise/http-message
[37]: https://packagist.org/packages/sunrise/http-factory
[38]: https://packagist.org/packages/zendframework/zend-diactoros

[40]: https://packagist.org/packages/chubbyphp/chubbyphp-framework

[70]: doc/Middleware/CallbackMiddleware.md
[71]: doc/Middleware/ExceptionMiddleware.md
[72]: doc/Middleware/LazyMiddleware.md
[73]: doc/Middleware/MiddlewareDispatcher.md
[74]: doc/Middleware/NewRelicRouteMiddleware.md
[75]: doc/Middleware/RouterMiddleware.md

[80]: doc/RequestHandler/CallbackRequestHandler.md
[81]: doc/RequestHandler/LazyRequestHandler.md

[90]: doc/Router/AuraRouter.md
[91]: doc/Router/FastRouteRouter.md
[92]: doc/Router/Group.md
[93]: doc/Router/Route.md

[100]: doc/Webserver/Builtin.md
[101]: doc/Webserver/Nginx.md

[200]: https://packagist.org/packages/chubbyphp/chubbyphp-framework-skeleton
[201]: https://packagist.org/packages/chubbyphp/petstore

[210]: https://packagist.org/packages/chubbyphp/chubbyphp-swoole-request-handler
