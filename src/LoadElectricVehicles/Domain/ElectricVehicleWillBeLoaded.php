<?php

namespace App\LoadElectricVehicles\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\ElectricVehicle;
use DateTimeImmutable;

class ElectricVehicleWillBeLoaded implements DomainEvent
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
