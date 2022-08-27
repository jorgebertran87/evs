<?php

namespace App\LoadElectricVehicles\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventHandler;

class ElectricVehicleWillBeLoadedHandler implements DomainEventHandler
{
    public function __construct(private ElectricVehiclesRepository $electricVehiclesRepository)
    {
    }

    /**
     * @param ElectricVehicleWillBeLoaded $domainEvent
     */
    public function handle(DomainEvent $domainEvent): mixed
    {
        $this->electricVehiclesRepository->load($domainEvent->electricVehicle());

        return $domainEvent->electricVehicle();
    }
}
