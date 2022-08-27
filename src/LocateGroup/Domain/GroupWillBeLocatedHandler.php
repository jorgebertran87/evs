<?php

namespace App\LocateGroup\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventHandler;
use App\Shared\Domain\ElectricVehicle;

class GroupWillBeLocatedHandler implements DomainEventHandler
{
    public function __construct(private ElectricVehiclesRepository $electricVehiclesRepository)
    {
    }

    /**
     * @param GroupWillBeLocated $domainEvent
     *
     * @throws GroupIsWaitingToBeAssignedException
     */
    public function handle(DomainEvent $domainEvent): ElectricVehicle|null
    {
        $electricVehicle = $this->electricVehiclesRepository->locate($domainEvent->group());

        if (is_null($electricVehicle)) {
            throw GroupIsWaitingToBeAssignedException::createFromId($domainEvent->group()->id());
        }

        return $electricVehicle;
    }
}
