<?php

namespace App\DropOffGroup\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Group;
use DateTimeImmutable;

class GroupWillBeDroppedOff implements DomainEvent
{
    public function __construct(private Group $group)
    {
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function group(): Group
    {
        return $this->group;
    }
}
