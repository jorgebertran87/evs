<?php

namespace App\DropOffGroup\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventHandler;

class GroupWillBeDroppedOffHandler implements DomainEventHandler
{
    public function __construct(private GroupsRepository $groupsRepository)
    {
    }

    /**
     * @param GroupWillBeDroppedOff $domainEvent
     */
    public function handle(DomainEvent $domainEvent): mixed
    {
        $this->groupsRepository->dropOff($domainEvent->group());

        return null;
    }
}
