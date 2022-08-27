<?php

namespace App\PerformJourney\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\ElectricVehicle;
use DateTimeImmutable;

class GroupWillBeAssignedToElectricVehicle implements DomainEvent
{
    public function __construct(private ElectricVehicle $electricVehicle)
    {
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function electricVehicle(): ElectricVehicle
    {
        return $this->electricVehicle;
    }
}
