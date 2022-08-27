<?php

namespace App\PerformJourney\Domain;

use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventHandler;

class JourneyWillBePerformedHandler implements DomainEventHandler
{
    public function __construct(private ElectricVehiclesRepository $electricVehiclesRepository)
    {
    }

    /**
     * @param JourneyWillBePerformed $domainEvent
     */
    public function handle(DomainEvent $domainEvent): mixed
    {
        $domainEvent->group()->register();
        $electricVehicles = $this->electricVehiclesRepository->getAll();
        foreach ($electricVehicles as $electricVehicle) {
            $peopleInElectricVehicle = 0;
            foreach ($electricVehicle->groups() as $group) {
                $peopleInElectricVehicle += $group->people();
            }

            $pendingSeats = $electricVehicle->seats() - $peopleInElectricVehicle;
            if ($pendingSeats >= $domainEvent->group()->people()) {
                $electricVehicle->assignGroup($domainEvent->group());

                return $domainEvent->group();
            }
        }

        return null;
    }
}
