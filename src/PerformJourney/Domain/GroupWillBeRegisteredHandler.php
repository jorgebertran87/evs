<?php

namespace App\PerformJourney\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventHandler;

class GroupWillBeRegisteredHandler implements DomainEventHandler
{
    public function __construct(private GroupsRepository $groupsRepository)
    {
    }

    /**
     * @param GroupWillBeRegistered $domainEvent
     */
    public function handle(DomainEvent $domainEvent): mixed
    {
        $this->groupsRepository->save($domainEvent->group());

        return null;
    }
}
