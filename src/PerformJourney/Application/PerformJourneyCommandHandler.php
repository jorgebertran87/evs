<?php

namespace App\PerformJourney\Application;

use App\PerformJourney\Domain\JourneyWillBePerformed;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\DomainEventsSubscriber;
use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Group;
use App\Shared\Domain\Id;

class PerformJourneyCommandHandler
{
    public function __construct(
        private DependenciesInjector $dependenciesInjector,
        private GroupDataValidator $groupDataValidator
    ) {
    }

    /**
     * @throws NotValidGroupDataException
     */
    public function handle(PerformJourneyCommand $performJourneyCommand): array|null
    {
        $this->groupDataValidator->validate($performJourneyCommand->groupData());
        $group = new Group(new Id($performJourneyCommand->groupData()->id), $performJourneyCommand->groupData()->people);
        $group->performJourney();

        /** @var ElectricVehicle|null $electricVehicle */
        $electricVehicle = DomainEventsSubscriber::handle($this->dependenciesInjector)[JourneyWillBePerformed::class];

        return $electricVehicle?->serialize();
    }
}
