<?php

declare(strict_types=1);

namespace Chubbyphp\Framework\Router;

interface UrlGeneratorInterface
{
    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     *
     * @throws UrlGeneratorException
     */
    public function requestTargetFor(string $name, array $parameters = []): string;
}
