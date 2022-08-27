<?php

namespace App\PerformJourney\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventHandler;

class GroupWillBeAssignedToElectricVehicleHandler implements DomainEventHandler
{
    public function __construct(private ElectricVehiclesRepository $electricVehiclesRepository)
    {
    }

    /**
     * @param GroupWillBeAssignedToElectricVehicle $domainEvent
     */
    public function handle(DomainEvent $domainEvent): mixed
    {
        $this->electricVehiclesRepository->save($domainEvent->electricVehicle());

        return null;
    }
}
