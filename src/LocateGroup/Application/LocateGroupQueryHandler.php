<?php

namespace App\LocateGroup\Application;

use App\LocateGroup\Domain\GroupNotFoundException;
use App\LocateGroup\Domain\GroupsRepository;
use App\LocateGroup\Domain\GroupWillBeLocated;
use App\Shared\Application\IdDataValidator;
use App\Shared\Application\NotValidIdDataException;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\DomainEventsSubscriber;
use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Id;

class LocateGroupQueryHandler
{
    public function __construct(
        private GroupsRepository $groupsRepository,
        private DependenciesInjector $dependenciesInjector,
        private IdDataValidator $idDataValidator
    ) {
    }

    /**
     * @throws GroupNotFoundException
     * @throws NotValidIdDataException
     */
    public function handle(LocateGroupQuery $locateGroupQuery): array
    {
        $this->idDataValidator->validate($locateGroupQuery->idData());

        $id = new Id($locateGroupQuery->idData()->id);
        $groupToBeLocated = $this->groupsRepository->findOneById($id);
        if (is_null($groupToBeLocated)) {
            throw GroupNotFoundException::createFromId($id);
        }

        $groupToBeLocated->locate();

        /** @var ElectricVehicle $electricVehicle */
        $electricVehicle = DomainEventsSubscriber::handle($this->dependenciesInjector)[GroupWillBeLocated::class];

        return $electricVehicle->serialize();
    }
}
