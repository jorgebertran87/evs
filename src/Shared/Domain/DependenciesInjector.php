<?php

namespace App\Shared\Domain;

interface DependenciesInjector
{
    public function inject(string $objectClass): object;
}
