<?php

declare(strict_types=1);

namespace Chubbyphp\Framework\Router\Aura;

use Aura\Router\Matcher;
use Aura\Router\RouterContainer;
use Aura\Router\Rule\Allows;
use Chubbyphp\Framework\Router\RouteCollectionInterface;
use Chubbyphp\Framework\Router\RouteDispatcherException;
use Chubbyphp\Framework\Router\RouteDispatcherInterface;
use Chubbyphp\Framework\Router\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RouteDispatcher implements RouteDispatcherInterface
{
    /**
     * @var RouteInterface[]
     */
    private $routes;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @param RouteCollectionInterface $routeCollection
     */
    public function __construct(RouteCollectionInterface $routeCollection)
    {
        $routes = $routeCollection->getRoutes();

        $routerContainer = new RouterContainer();
        $map = $routerContainer->getMap();

        foreach ($routes as $route) {
            $options = $route->getOptions();

            $auraRoute = $map->route($route->getName(), $route->getPattern());
            $auraRoute->allows($route->getMethod());
            $auraRoute->tokens($options['tokens'] ?? []);
            $auraRoute->defaults($options['defaults'] ?? []);
        }

        $this->routes = $routes;
        $this->matcher = $routerContainer->getMatcher();
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return RouteInterface
     */
    public function dispatch(ServerRequestInterface $request): RouteInterface
    {
        if (!$auraRoute = $this->matcher->match($request)) {
            $failedAuraRoute = $this->matcher->getFailedRoute();
            switch ($failedAuraRoute->failedRule) {
                case Allows::class:
                    throw RouteDispatcherException::createForMethodNotAllowed(
                        $request->getMethod(),
                        $failedAuraRoute->allows,
                        $request->getRequestTarget()
                    );
                default:
                    throw RouteDispatcherException::createForNotFound($request->getRequestTarget());
            }
        }

        /** @var RouteInterface $route */
        $route = $this->routes[$auraRoute->name];
        $route = $route->withAttributes($auraRoute->attributes);

        return $route;
    }
}
