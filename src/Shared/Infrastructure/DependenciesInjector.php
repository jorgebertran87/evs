<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\DependenciesInjector as DependenciesInjectorInterface;
use Psr\Container\ContainerInterface;

class DependenciesInjector implements DependenciesInjectorInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function inject(string $objectClass): object
    {
        return $this->container->get($objectClass);
    }
}
