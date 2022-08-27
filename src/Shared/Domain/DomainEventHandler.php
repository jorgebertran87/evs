<?php

namespace App\Shared\Domain;

interface DomainEventHandler
{
    public function handle(DomainEvent $domainEvent): mixed;
}
