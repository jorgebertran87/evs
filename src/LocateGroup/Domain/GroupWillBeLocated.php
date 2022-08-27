<?php

namespace App\LocateGroup\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Group;
use DateTimeImmutable;

class GroupWillBeLocated implements DomainEvent
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
