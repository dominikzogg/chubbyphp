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

### Lines of Code

Values are generated by phploc at the 02.05.2019. For up2date values check [travis-ci][15].

#### Size

```
Lines of Code (LOC)                1648
Comment Lines of Code (CLOC)        550 (33.37%)
Non-Comment Lines of Code (NCLOC)  1098 (66.63%)
Logical Lines of Code (LLOC)        351 (21.30%)
  Classes                           255 (72.65%)
    Average Class Length             13
      Minimum Class Length            1
      Maximum Class Length           43
    Average Method Length             2
      Minimum Method Length           1
      Maximum Method Length          12
  Functions                           0 (0.00%)
    Average Function Length           0
  Not in classes or functions        96 (27.35%)
```

#### Cyclomatic Complexity

```
Average Complexity per LLOC     0.11
Average Complexity per Class    3.11
  Minimum Class Complexity      1.00
  Maximum Class Complexity     15.00
Average Complexity per Method   1.56
  Minimum Method Complexity     1.00
  Maximum Method Complexity     5.00
```

## Requirements

 * php: ^7.2
 * [psr/container][20]: ^1.0
 * [psr/http-factory][21]: ^1.0
 * [psr/http-message-implementation][22]: ^1.0
 * [psr/http-message][23]: ^1.0.1
 * [psr/http-server-middleware][24]: ^1.0.1
 * [psr/log][25]: ^1.1

## Suggest

 * [aura/router][30]: ^3.1
 * [nikic/fast-route][31]: ^1.3
 * [zendframework/zend-diactoros][32]: ^2.1.1

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-framework][40].

Both example using the [zendframework/zend-diactoros][32] [psr/http-message-implementation][22].

### Aura.Router

```sh
composer require \
    aura/router "^3.1" \
    zendframework/zend-diactoros "^2.1.1" \
    chubbyphp/chubbyphp-framework "^1.0@alpha"
```

### FastRoute

```sh
composer require \
    nikic/fast-route "^1.3" \
    zendframework/zend-diactoros "^2.1.1" \
    chubbyphp/chubbyphp-framework "^1.0@alpha"
```

## Workflow

![Application workflow](doc/Resources/workflow.png?raw=true "Application workflow")

## Usage

### Application

```php
<?php

use Chubbyphp\Framework\Application;
use Chubbyphp\Framework\Middleware\MiddlewareDispatcherInterface;
use Chubbyphp\Framework\ResponseHandler\ExceptionResponseHandlerInterface;
use Chubbyphp\Framework\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/** @var RouterInterface $router */
$router = ...;

/** @var MiddlewareDispatcherInterface $middlewareDispatcher */
$middlewareDispatcher = ...;

/** @var ExceptionResponseHandlerInterface $exceptionHandler */
$exceptionHandler = ...;

/** @var LoggerInterface $logger */
$logger = ...;

/** @var ServerRequestInterface $request */
$request = ...;

$app = new Application($router, $middlewareDispatcher, $exceptionHandler, $logger);

/** @var ResponseInterface $response */
$response = $app->handle($request);

$app->send($response);
```

### Examples

 * [AuraRouter][50]
 * [FastRoute][51]

### Middleware

 * [LazyMiddleware][60]
 * [MiddlewareDispatcher][61]

### RequestHandler

 * [CallbackRequestHandler][70]
 * [LazyRequestHandler][71]

### ResponseHandler

 * [ExceptionResponseHandler][80]

### Router

 * [AuraRouter][90]
 * [FastRouteRouter][91]
 * [Group][92]
 * [Route][93]

## Webserver

 * [Builtin (development only)][100]
 * [Nginx][101]

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
[24]: https://packagist.org/packages/psr/http-server-middleware
[25]: https://packagist.org/packages/psr/log

[30]: https://packagist.org/packages/aura/router
[31]: https://packagist.org/packages/nikic/fast-route
[32]: https://packagist.org/packages/zendframework/zend-diactoros

[40]: https://packagist.org/packages/chubbyphp/chubbyphp-framework

[50]: doc/Examples/AuraRouter.md
[51]: doc/Examples/FastRoute.md

[60]: doc/Middleware/LazyMiddleware.md
[61]: doc/Middleware/MiddlewareDispatcher.md

[70]: doc/RequestHandler/CallbackRequestHandler.md
[71]: doc/RequestHandler/LazyRequestHandler.md

[80]: doc/ResponseHandler/ExceptionResponseHandler.md

[90]: doc/Router/AuraRouter.md
[91]: doc/Router/FastRouteRouter.md
[92]: doc/Router/Group.md
[93]: doc/Router/Route.md

[100]: doc/Webserver/Builtin.md
[101]: doc/Webserver/Nginx.md
